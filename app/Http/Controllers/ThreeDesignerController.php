<?php



namespace App\Http\Controllers;

use App\Models\ThreeDDesign;
use App\Models\ThreeDDesignChangeLog;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DesignerController extends Controller
{
    // ─────────────────────────────────────────────
    // INDEX  —  GET /three-d-design
    // ─────────────────────────────────────────────
    public function index(Request $request)
    {
        $query = ThreeDDesign::with('project')
            ->when($request->status,    fn($q, $s) => $q->where('design_status', $s))
            ->when($request->project_id, fn($q, $p) => $q->where('project_id', $p))
            ->when($request->designer,   fn($q, $d) => $q->where('assigned_designer', 'like', "%$d%"))
            ->latest();

        $records = $query->paginate(20)->withQueryString();

        // Attach a helper for the "can proceed" chip
        $records->getCollection()->transform(function ($record) {
            $record->can_proceed = $record->final_3d_approval && $record->design_freeze_confirmation;
            return $record;
        });

        return view('stages.three-d-design.index', compact('records'));
    }

    // ─────────────────────────────────────────────
    // CREATE FORM  —  GET /three-d-design/create
    // ─────────────────────────────────────────────
    public function create()
    {
        $projects = Project::orderBy('name')->get();
        return view('stages.three-d-design.form', compact('projects'));
    }

    // ─────────────────────────────────────────────
    // STORE  —  POST /three-d-design
    // ─────────────────────────────────────────────
    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id'                  => 'required|exists:projects,id',
            'design_start_date'           => 'nullable|date',
            'assigned_designer'           => 'nullable|string|max:150',
            'client_requirements_freeze'  => 'nullable|date',
            'design_status'               => 'required|in:In Progress,Submitted,Revised',
            'client_feedback'             => 'nullable|string',
            'revision_count'              => 'integer|min:0',
            'final_3d_approval'           => 'boolean',
            'approval_date'               => 'nullable|date',
            'design_freeze_confirmation'  => 'boolean',
        ]);

        $validated['created_by'] = Auth::id();
        $validated['updated_by'] = Auth::id();

        ThreeDDesign::create($validated);

        return redirect()
            ->route('three-d-design.index')
            ->with('success', '3D Design record created successfully.');
    }

    // ─────────────────────────────────────────────
    // SHOW  —  GET /three-d-design/{id}
    // ─────────────────────────────────────────────
    public function show(ThreeDDesign $threeDDesign)
    {
        $threeDDesign->load(['project', 'changeLogs.changedBy']);
        $threeDDesign->can_proceed =
            $threeDDesign->final_3d_approval && $threeDDesign->design_freeze_confirmation;

        return view('stages.three-d-design.show', ['record' => $threeDDesign]);
    }

    // ─────────────────────────────────────────────
    // EDIT FORM  —  GET /three-d-design/{id}/edit
    // ─────────────────────────────────────────────
    public function edit(ThreeDDesign $threeDDesign)
    {
        $projects = Project::orderBy('name')->get();
        return view('stages.three-d-design.form', [
            'record'   => $threeDDesign,
            'projects' => $projects,
        ]);
    }

    // ─────────────────────────────────────────────
    // UPDATE  —  PUT /three-d-design/{id}
    // Business Rule: changes after freeze → cost flag + audit log
    // ─────────────────────────────────────────────
    public function update(Request $request, ThreeDDesign $threeDDesign)
    {
        $validated = $request->validate([
            'project_id'                  => 'sometimes|exists:projects,id',
            'design_start_date'           => 'nullable|date',
            'assigned_designer'           => 'nullable|string|max:150',
            'client_requirements_freeze'  => 'nullable|date',
            'design_status'               => 'sometimes|in:In Progress,Submitted,Revised',
            'client_feedback'             => 'nullable|string',
            'revision_count'              => 'integer|min:0',
            'final_3d_approval'           => 'boolean',
            'approval_date'               => 'nullable|date',
            'design_freeze_confirmation'  => 'boolean',
            'change_after_freeze_note'    => 'nullable|string',
        ]);

        DB::transaction(function () use ($validated, $threeDDesign, $request) {

            // ── Business Rule: post-freeze change detection
            if ($threeDDesign->design_freeze_confirmation) {

                if (empty($validated['change_after_freeze_note'])) {
                    abort(422, 'Design is frozen. A change justification note is required.');
                }

                $trackFields = [
                    'design_start_date', 'assigned_designer', 'client_requirements_freeze',
                    'design_status', 'client_feedback', 'revision_count',
                    'final_3d_approval', 'approval_date',
                ];

                foreach ($trackFields as $field) {
                    if (
                        isset($validated[$field]) &&
                        (string) $validated[$field] !== (string) $threeDDesign->$field
                    ) {
                        ThreeDDesignChangeLog::create([
                            'design_id'     => $threeDDesign->id,
                            'changed_by'    => Auth::id(),
                            'change_note'   => $validated['change_after_freeze_note'],
                            'field_changed' => $field,
                            'old_value'     => (string) $threeDDesign->$field,
                            'new_value'     => (string) $validated[$field],
                            'cost_impact'   => true,
                        ]);
                    }
                }

                $threeDDesign->additional_cost_flag       = true;
                $threeDDesign->change_after_freeze_note   = $validated['change_after_freeze_note'];
            }

            $threeDDesign->fill(array_merge($validated, ['updated_by' => Auth::id()]));
            $threeDDesign->save();
        });

        $warning = $threeDDesign->design_freeze_confirmation
            ? '⚠️ Change recorded after Design Freeze. Additional cost has been flagged.'
            : null;

        return redirect()
            ->route('three-d-design.index')
            ->with('success', $warning ?? '3D Design record updated successfully.');
    }

    // ─────────────────────────────────────────────
    // QUICK ACTIONS (AJAX-friendly patch routes)
    // ─────────────────────────────────────────────

    /** PATCH /three-d-design/{id}/submit */
    public function submit(ThreeDDesign $threeDDesign)
    {
        $threeDDesign->update([
            'design_status' => 'Submitted',
            'updated_by'    => Auth::id(),
        ]);

        return back()->with('success', 'Design submitted for client approval.');
    }

    /** PATCH /three-d-design/{id}/approve */
    public function approve(Request $request, ThreeDDesign $threeDDesign)
    {
        $request->validate([
            'approved'      => 'required|boolean',
            'approval_date' => 'nullable|date',
        ]);

        $threeDDesign->update([
            'final_3d_approval' => $request->boolean('approved'),
            'approval_date'     => $request->approval_date ?? now()->toDateString(),
            'updated_by'        => Auth::id(),
        ]);

        $msg = $request->boolean('approved')
            ? '✅ Final 3D Approval granted.'
            : '❌ Design approval rejected.';

        return back()->with('success', $msg);
    }

    /** PATCH /three-d-design/{id}/freeze */
    public function freeze(ThreeDDesign $threeDDesign)
    {
        if (! $threeDDesign->final_3d_approval) {
            return back()->with('error',
                'Cannot freeze: Final 3D Approval must be granted first.'
            );
        }

        $threeDDesign->update([
            'design_freeze_confirmation' => true,
            'updated_by'                 => Auth::id(),
        ]);

        return back()->with('success',
            '🔒 Design Freeze confirmed. Further changes will incur additional cost.'
        );
    }

    /** PATCH /three-d-design/{id}/revise */
    public function revise(Request $request, ThreeDDesign $threeDDesign)
    {
        $threeDDesign->update([
            'revision_count'  => $threeDDesign->revision_count + 1,
            'design_status'   => 'Revised',
            'client_feedback' => $request->client_feedback ?? $threeDDesign->client_feedback,
            'updated_by'      => Auth::id(),
        ]);

        return back()->with('success',
            "Revision #{$threeDDesign->revision_count} recorded."
        );
    }

    // ─────────────────────────────────────────────
    // CAN PROCEED CHECK  —  GET /three-d-design/{id}/can-proceed
    // ─────────────────────────────────────────────
    public function canProceed(ThreeDDesign $threeDDesign)
    {
        $blockers = [];
        if (! $threeDDesign->final_3d_approval)          $blockers[] = 'Final 3D Approval not granted';
        if (! $threeDDesign->design_freeze_confirmation)  $blockers[] = 'Design Freeze not confirmed';

        return response()->json([
            'can_proceed' => empty($blockers),
            'blockers'    => $blockers,
            'message'     => empty($blockers)
                ? '✅ Stage 7 complete. Ready to proceed.'
                : '🚫 Cannot proceed: ' . implode('; ', $blockers) . '.',
        ]);
    }

    // ─────────────────────────────────────────────
    // CHANGE LOG  —  GET /three-d-design/{id}/change-log
    // ─────────────────────────────────────────────
    public function changeLog(ThreeDDesign $threeDDesign)
    {
        $logs = $threeDDesign->changeLogs()->with('changedBy')->latest()->get();
        return view('stages.three-d-design.change-log', compact('logs', 'threeDDesign'));
    }

    // ─────────────────────────────────────────────
    // DESTROY  —  DELETE /three-d-design/{id}
    // ─────────────────────────────────────────────
    public function destroy(ThreeDDesign $threeDDesign)
    {
        $this->authorize('delete', $threeDDesign); // Policy check
        $threeDDesign->delete();

        return redirect()
            ->route('three-d-design.index')
            ->with('success', '3D Design record deleted.');
    }
}
