<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\Project;
use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\Leave;
use App\Models\Department;
use App\Models\Attendance;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class SalesController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();

        // Match employee using email instead
        $employee = Employee::where('email', $user->email)->first();

        $leaves = [];

        if ($employee) {
            $leaves = Leave::where('employee_id', $employee->id)
                ->latest()
                ->get();
        }

        return view('sales_executive.dashboard', compact('leaves'));
    }

    public function leads(Request $request)
    {
        $user = Auth::user();

        if (!$user->department || $user->department->slug !== 'sales_executive') {
            abort(403);
        }

         $query = Lead::where('sales_executive_id', $user->id);
         // Search by client name
    if ($request->search) {
        $query->where('client_name', 'LIKE', '%' . $request->search . '%');
    }

    // Filter by created date
    if ($request->created_date) {
        $query->whereDate('created_at', $request->created_date);
    }

    // Filter by status
    if ($request->status) {
        $query->where('status', $request->status);
    }
     $leads = $query->latest()->get();

        $totalLeads = Lead::count();
        $convertedLeads = Lead::where('status', 'Won')->count();
        $failedLeads = Lead::where('status', 'Lost')->count();
        $totalarrivedLeads = Lead::where('sales_executive_id', $user->id)->count();

        return view('sales_executive.leads', compact(
            'leads',
            'failedLeads',
            'convertedLeads',
            'totalLeads',
            'totalarrivedLeads'
        ));
    }

    public function showJson(Lead $lead)
    {
        $lead->load('tasks');
        return response()->json([
            'client_name' => $lead->client_name,
            'phone' => $lead->phone,
            'email' => $lead->email,
            'location' => $lead->location,
            'project_type' => $lead->project_type,
            'budget_range' => $lead->budget_range,
            'lead_source' => $lead->lead_source,
            'notes' => $lead->notes,
            'site_visit' => $lead->site_visit,
            'expected_start_date' => $lead->expected_start_date,
            'tasks' => $lead->tasks
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'lead_id' => 'required|exists:leads,id',
            'title' => 'required',
            'followup_date' => 'nullable|date',
        ]);

        Task::create([
            'lead_id' => $request->lead_id,
            'sales_executive_id' => Auth::id(),
            'title' => $request->title,
            'followup_date' => $request->followup_date,
        ]);

        return response()->json([
            'success' => true
        ]);
    }

    // public function updateTask(Request $request, $id)
    // {
    //     $task = Task::findOrFail($id);

    //     $task->update([
    //         'title' => $request->title,
    //         'followup_date' => $request->followup_date,
    //     ]);

    //     return response()->json([
    //         'success' => true
    //     ]);
    // }

    public function show($id)
    {
        $lead = Lead::with(['tasks' => function ($q) {
            $q->orderBy('followup_date', 'asc');
        }])->findOrFail($id);

        return response()->json($lead);
    }

    public function leadTasks($leadId)
    {
        $tasks = Task::where('lead_id', $leadId)
            ->orderBy('followup_date')
            ->get();

        return response()->json($tasks);
    }

    public function deleteTask($id)
    {
        Task::findOrFail($id)->delete();
        return response()->json(['success' => true]);
    }

    public function updateTask(Request $request, $id)
    {
        $task = Task::findOrFail($id);
        $task->update([
            'title' => $request->title
        ]);

        return response()->json(['success' => true]);
    }

    public function update(Request $request, Lead $lead)
    {
        $data = [];

        // Update notes
        if ($request->has('notes')) {
            $data['notes'] = $request->notes;

            if ($lead->status === 'New') {
                $data['status'] = 'Contacted';
            }
        }

        // Site visit handling
        if ($request->has('site_visit')) {
            $data['site_visit'] = $request->site_visit;

            if ($request->site_visit == 1) {
                $data['status'] = 'Site Visit';
            }

            if ($request->site_visit == 0 && $lead->status === 'Site Visit') {
                $data['status'] = 'Contacted';
            }
        }

        // If status manually updated
        if ($request->has('status')) {
            $data['status'] = $request->status;
        }

        if (!empty($data)) {
            $lead->update($data);
        }

        // ✅ If Lead becomes WON → Create Project
        if (($data['status'] ?? $lead->status) === 'Won') {

            $projectExists = Project::where('lead_id', $lead->id)->exists();

            if (!$projectExists) {
                Project::create([
                    'lead_id'      => $lead->id,
                    'project_code' => 'PRJ-' . str_pad($lead->id, 4, '0', STR_PAD_LEFT),
                    'status'       => 'Ongoing'
                ]);
            }
        }

        return response()->json(['success' => true]);
    }

    public function selfCheckIn(Request $request)
    {
        try {
            $user     = Auth::user();
            $employee = $user->employee
                ?? Employee::where('email', $user->email)
                ->orWhere('user_id', $user->id)
                ->first();

            if (!$employee) {
                // ✅ Fixed: use withTrashed()->max() here too
                $lastEmployee = Employee::withTrashed()->max('employee_id');
                $nextNumber   = $lastEmployee ? (int) substr($lastEmployee, 3) + 1 : 1;

                $employee = Employee::create([
                    'user_id'       => $user->id,
                    'first_name'    => explode(' ', $user->name)[0] ?? 'Staff',
                    'last_name'     => '',
                    'email'         => $user->email,
                    'employee_id'   => 'EMP' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT),
                    'status'        => 'active',
                    'joining_date'  => now(),
                    'salary'        => 0,
                    'salary_type'   => 'monthly',
                    'department_id' => Department::first()?->id ?? 1,
                ]);
            }

            $today    = Carbon::today();
            $existing = Attendance::where('employee_id', $employee->id)
                ->whereDate('date', $today)
                ->first();

            if ($existing && $existing->check_in) {
                return response()->json([
                    'success' => false,
                    'message' => 'Already checked in at ' . Carbon::parse($existing->check_in)->format('H:i'),
                ]);
            }

            Attendance::updateOrCreate(
                ['employee_id' => $employee->id, 'date' => $today],
                ['check_in' => now(), 'status' => 'present']
            );

            return response()->json([
                'success' => true,
                'message' => 'Check-in successful at ' . now()->format('H:i'),
                'time'    => now()->format('H:i'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Check-in failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function selfCheckOut(Request $request)
    {
        try {
            $user     = Auth::user();
            $employee = $user->employee
                ?? Employee::where('email', $user->email)
                ->orWhere('user_id', $user->id)
                ->first();

            if (!$employee) {
                return response()->json(['success' => false, 'message' => 'Employee profile not found'], 400);
            }

            $today      = Carbon::today();
            $attendance = Attendance::where('employee_id', $employee->id)
                ->whereDate('date', $today)
                ->first();

            if (!$attendance || !$attendance->check_in) {
                return response()->json(['success' => false, 'message' => 'No check-in found for today'], 400);
            }

            if ($attendance->check_out) {
                return response()->json(['success' => false, 'message' => 'Already checked out'], 400);
            }

            $checkOutTime = now();
            $attendance->update([
                'check_out' => $checkOutTime,
                'status'    => $this->calculateStatus($attendance->check_in, $checkOutTime),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Check-out successful at ' . $checkOutTime->format('H:i'),
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Check-out failed: ' . $e->getMessage()], 500);
        }
    }

    private function calculateStatus($checkIn, $checkOut)
    {
        if (!$checkIn) return 'absent';

        $checkInTime = Carbon::parse($checkIn);
        $officeStart = Carbon::parse('09:00');

        if ($checkInTime->gt($officeStart->copy()->addMinutes(15))) {
            return 'late';
        }

        if ($checkOut && $checkInTime->diffInHours(Carbon::parse($checkOut)) < 4) {
            return 'half_day';
        }

        return 'present';
    }

    public function attendanceStatus()
    {
        $user     = Auth::user();
        $employee = Employee::where('email', $user->email)->first();

        if (!$employee) {
            return response()->json(['checked_in_today' => false, 'checkin_time' => null]);
        }

        $today      = Carbon::today();
        $attendance = Attendance::where('employee_id', $employee->id)
            ->whereDate('date', $today)
            ->first();

        return response()->json([
            'checked_in_today' => $attendance && $attendance->check_in,
            'checkin_time'     => $attendance?->check_in ? Carbon::parse($attendance->check_in)->format('H:i') : null,
        ]);
    }

    public function salesLeave(Request $request)
    {
        $request->validate([
            'leave_type' => 'required|in:sick,casual,annual,unpaid,other',
            'from_date'  => 'required|date',
            'to_date'    => 'required|date|after_or_equal:from_date',
            'reason'     => 'nullable|string'
        ]);

        $user = Auth::user();

        $employee = $user->employee
            ?? Employee::where('email', $user->email)
            ->orWhere('user_id', $user->id)
            ->first();

        if (!$employee) {
            return back()->with('error', 'Employee profile not found.');
        }

        $from = Carbon::parse($request->from_date);
        $to   = Carbon::parse($request->to_date);

        $days = $from->diffInDays($to) + 1;

        Leave::create([
            'employee_id' => $employee->id,
            'leave_type'  => $request->leave_type,
            'from_date'   => $request->from_date,
            'to_date'     => $request->to_date,
            'days'        => $days,
            'reason'      => $request->reason,
            'status'      => 'pending',
        ]);

        return back()->with('success', 'Leave request submitted successfully.');
    }

    public function salesProject()
    {
        $user = Auth::user();

        $projects = Project::with('lead')
            ->whereHas('lead', function ($q) use ($user) {
                $q->where('sales_executive_id', $user->id);
            })
            ->latest()
            ->get();

        return view('sales_executive.projects', compact('projects'));
    }
    public function showProject($id)
    {
        $project = Project::with('lead')->findOrFail($id);

        return response()->json([
            'expected_start_date' => $project->lead->expected_start_date,
            'expected_end_date'   => $project->expected_end_date ?? null,
            'materials'           => json_decode($project->materials ?? '[]')
        ]);
    }
    public function updateProject(Request $request, $id)
    {
        $project = Project::findOrFail($id);

        $project->expected_start_date = $request->expected_start_date;
        $project->expected_end_date   = $request->expected_end_date;
        $project->materials           = $request->materials;

        $project->save();

        return response()->json([
            'success' => true,
            'message' => 'Project updated successfully'
        ]);
    }
    public function getEstimators()
    {
        $estimators = Employee::whereHas('department', function ($q) {
            $q->where('name', 'estimator');
        })->select('id', 'name')->get();

        return response()->json($estimators);
    }
}
