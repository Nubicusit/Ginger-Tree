@extends('designer.layouts.app')

@section('title', '3D Design')

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=DM+Mono:wght@400;500;600&display=swap');

    :root {
        --indigo: #6366F1;
        --indigo-light: #EEF2FF;
        --purple: #8B5CF6;
        --green: #15803D;
        --green-bg: #F0FDF4;
        --green-border: #86EFAC;
        --red: #BE123C;
        --red-bg: #FFF1F2;
        --red-border: #FECDD3;
        --amber: #B45309;
        --amber-bg: #FFFBEB;
        --border: #E5E7EB;
        --text-muted: #6B7280;
        --text-dark: #111827;
        --surface: #F8F7F4;
        --mono: 'DM Mono', monospace;
        --serif: 'Playfair Display', serif;
    }

    body { background: var(--surface); }

    .page-header h1 {
        font-family: var(--serif);
        font-size: 28px;
        font-weight: 700;
        color: var(--text-dark);
        letter-spacing: -0.5px;
    }

    .stage-badge {
        background: linear-gradient(135deg, var(--indigo), var(--purple));
        color: #fff;
        border-radius: 999px;
        padding: 3px 14px;
        font-family: var(--mono);
        font-size: 11px;
        font-weight: 600;
        letter-spacing: 0.06em;
    }

    /* Rule Banner */
    .rule-banner {
        background: linear-gradient(135deg, #FFF7ED, #FFFBEB);
        border: 1px solid #FDE68A;
        border-left: 4px solid #F59E0B;
        border-radius: 10px;
        padding: 13px 18px;
        font-size: 13px;
        color: #92400E;
        margin-bottom: 24px;
        display: flex;
        align-items: flex-start;
        gap: 10px;
    }
    .rule-banner strong { font-family: var(--mono); font-size: 11px; letter-spacing: 0.05em; }

    /* Filter Buttons */
    .filter-bar { display: flex; gap: 8px; flex-wrap: wrap; margin-bottom: 18px; align-items: center; }
    .filter-btn {
        padding: 6px 16px;
        border-radius: 999px;
        border: 1.5px solid var(--border);
        background: #fff;
        color: var(--text-muted);
        font-family: var(--mono);
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.15s;
        text-decoration: none;
        letter-spacing: 0.04em;
    }
    .filter-btn:hover, .filter-btn.active {
        border-color: var(--indigo);
        background: var(--indigo);
        color: #fff;
    }

    /* Table Card */
    .table-card {
        background: #fff;
        border-radius: 16px;
        border: 1px solid var(--border);
        box-shadow: 0 4px 24px rgba(0,0,0,0.05), 0 1px 3px rgba(0,0,0,0.04);
        overflow-x: auto;
    }
    .design-table { width: 100%; border-collapse: collapse; }
    .design-table thead tr {
        background: linear-gradient(to right, #FAFAFA, #F5F3FF);
        border-bottom: 2px solid var(--border);
    }
    .design-table th {
        padding: 12px 14px;
        text-align: left;
        font-family: var(--mono);
        font-size: 10.5px;
        font-weight: 600;
        color: #9CA3AF;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        white-space: nowrap;
    }
    .design-table tbody tr { border-bottom: 1px solid #F3F4F6; transition: background 0.15s; }
    .design-table tbody tr:nth-child(even) { background: #FAFAFA; }
    .design-table tbody tr:hover { background: #F5F3FF; }
    .design-table tbody tr:hover .btn-edit { opacity: 1; }
    .design-table td { padding: 13px 14px; vertical-align: middle; }

    /* Badges */
    .badge {
        border-radius: 999px;
        padding: 3px 10px;
        font-size: 11.5px;
        font-weight: 600;
        font-family: var(--mono);
        display: inline-flex;
        align-items: center;
        gap: 5px;
        white-space: nowrap;
        border: 1px solid transparent;
    }
    .badge-dot { width: 6px; height: 6px; border-radius: 50%; display: inline-block; }

    /* Status badges */
    .badge-in-progress  { background: #FFF7ED; color: #C2410C; border-color: rgba(251,146,60,0.3); }
    .badge-in-progress .badge-dot { background: #FB923C; }
    .badge-submitted    { background: #EFF6FF; color: #1D4ED8; border-color: rgba(96,165,250,0.3); }
    .badge-submitted .badge-dot { background: #60A5FA; }
    .badge-revised      { background: #FAF5FF; color: #7E22CE; border-color: rgba(192,132,252,0.3); }
    .badge-revised .badge-dot { background: #C084FC; }

    /* Approval badges */
    .badge-yes { background: var(--green-bg); color: var(--green); border-color: rgba(134,239,172,0.5); }
    .badge-yes .badge-dot { background: #4ADE80; }
    .badge-no  { background: var(--red-bg); color: var(--red); border-color: rgba(254,205,211,0.5); }
    .badge-no .badge-dot { background: #FB7185; }

    /* Proceed chips */
    .chip-go {
        background: var(--green-bg); color: var(--green);
        border: 1px solid var(--green-border);
        border-radius: 6px; padding: 3px 9px;
        font-family: var(--mono); font-size: 11px; font-weight: 700;
    }
    .chip-hold {
        background: var(--red-bg); color: var(--red);
        border: 1px solid var(--red-border);
        border-radius: 6px; padding: 3px 9px;
        font-family: var(--mono); font-size: 11px; font-weight: 700;
    }

    /* Revision pips */
    .revision-pips { display: inline-flex; align-items: center; gap: 3px; }
    .pip { width: 8px; height: 8px; border-radius: 2px; }
    .pip-high { background: #FCD34D; }
    .pip-low  { background: #D1D5DB; }
    .pip-label { font-family: var(--mono); font-size: 12px; color: #9CA3AF; }

    /* Project name */
    .project-name { font-family: 'Georgia', serif; font-size: 13px; font-weight: 600; color: var(--text-dark); }
    .text-mono    { font-family: var(--mono); font-size: 12px; color: var(--text-muted); }
    .text-mono-dark { font-family: var(--mono); font-size: 12px; color: #374151; }
    .text-feedback  { font-size: 12px; color: var(--text-muted); font-style: italic; }

    /* Edit Button */
    .btn-edit {
        opacity: 0;
        padding: 5px 12px;
        border-radius: 7px;
        border: 1.5px solid var(--border);
        background: #fff;
        cursor: pointer;
        font-family: var(--mono);
        font-size: 11px;
        color: var(--indigo);
        font-weight: 600;
        transition: opacity 0.2s, box-shadow 0.2s;
        text-decoration: none;
    }
    .btn-edit:hover { box-shadow: 0 2px 8px rgba(99,102,241,0.2); }

    /* New record button */
    .btn-primary {
        padding: 9px 22px;
        border-radius: 9px;
        border: none;
        background: linear-gradient(135deg, var(--indigo), var(--purple));
        color: #fff;
        font-family: var(--mono);
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        box-shadow: 0 4px 12px rgba(99,102,241,0.35);
        transition: opacity 0.2s;
    }
    .btn-primary:hover { opacity: 0.9; color: #fff; }

    /* Stats */
    .stat-row { display: flex; gap: 20px; font-family: var(--mono); font-size: 12px; color: #9CA3AF; align-items: center; }
    .stat-row strong { color: var(--text-dark); }

    /* Legend */
    .legend { display: flex; gap: 20px; flex-wrap: wrap; font-family: var(--mono); font-size: 11px; color: #9CA3AF; margin-top: 16px; }

    /* Modal */
    .modal-title { font-family: var(--serif); font-size: 20px; font-weight: 700; color: var(--text-dark); }
    .form-label {
        font-size: 11px; font-family: var(--mono); color: var(--text-muted);
        font-weight: 600; letter-spacing: 0.07em; text-transform: uppercase;
        display: block; margin-bottom: 4px;
    }
    .form-control-mono {
        width: 100%; padding: 8px 12px; border: 1.5px solid var(--border);
        border-radius: 8px; font-family: var(--mono); font-size: 13px;
        color: var(--text-dark); background: #FAFAFA; outline: none;
        transition: border 0.2s; box-sizing: border-box;
    }
    .form-control-mono:focus { border-color: var(--indigo); background: #fff; }

    /* Alert */
    .alert-success { background: var(--green-bg); border: 1px solid var(--green-border); border-left: 4px solid #22C55E; border-radius: 10px; padding: 12px 16px; color: var(--green); font-size: 13px; }
    .alert-danger  { background: var(--red-bg);   border: 1px solid var(--red-border);   border-left: 4px solid #F43F5E; border-radius: 10px; padding: 12px 16px; color: var(--red);   font-size: 13px; }
</style>
@endpush

@section('content')
<div class="container-fluid py-4 px-4">

    {{-- Page Header --}}
    <div class="page-header mb-4">
        <div class="d-flex align-items-center gap-3 mb-1">
            <span style="font-size:28px">🎨</span>
            <h1 class="mb-0">Stage 7 — 3D Design</h1>
            <span class="stage-badge">DESIGN FINALIZATION</span>
        </div>
        <p class="mb-0" style="color:var(--text-muted);font-size:14px;">
            Track design progress, client approvals, and freeze status before execution handoff.
        </p>
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="alert-success mb-3">✅ {{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert-danger mb-3">❌ {{ session('error') }}</div>
    @endif

    {{-- Business Rule Banner --}}
    <div class="rule-banner">
        <span style="font-size:18px;margin-top:-2px">⚠️</span>
        <div>
            <strong>BUSINESS RULE</strong><br>
            Any change after <strong>Design Freeze</strong> incurs <strong>Additional Cost</strong>.
            Next stage proceeds only after <strong>Final 3D Approval = Yes</strong>
            &amp; <strong>Design Freeze confirmed</strong>.
        </div>
    </div>

    {{-- Filters + Actions Row --}}
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-3">
        <div class="filter-bar">
            <a href="{{ route('three-d-design.index') }}"
               class="filter-btn {{ !request('status') ? 'active' : '' }}">All</a>
            @foreach(['In Progress', 'Submitted', 'Revised'] as $s)
                <a href="{{ route('three-d-design.index', ['status' => $s]) }}"
                   class="filter-btn {{ request('status') === $s ? 'active' : '' }}">{{ $s }}</a>
            @endforeach
        </div>

        <div class="d-flex align-items-center gap-4">
            <div class="stat-row">
                <span><strong>{{ $records->where('can_proceed', true)->count() }}</strong> Ready</span>
                <span><strong>{{ $records->total() }}</strong> Total</span>
            </div>
            <a href="{{ route('three-d-design.create') }}" class="btn-primary">+ New Record</a>
        </div>
    </div>

    {{-- Table --}}
    <div class="table-card">
        <table class="design-table">
            <thead>
                <tr>
                    <th>Project</th>
                    <th>Start Date</th>
                    <th>Designer</th>
                    <th>Req. Freeze</th>
                    <th>Status</th>
                    <th>Client Feedback</th>
                    <th>Revisions</th>
                    <th>Approval</th>
                    <th>Approval Date</th>
                    <th>Frozen</th>
                    <th>Proceed</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($records as $record)
                    <tr>
                        {{-- Project Name --}}
                        <td>
                            <span class="project-name">{{ $record->project->name ?? '—' }}</span>
                        </td>

                        {{-- Design Start Date --}}
                        <td class="text-mono">
                            {{ $record->design_start_date
                                ? \Carbon\Carbon::parse($record->design_start_date)->format('d M Y')
                                : '—' }}
                        </td>

                        {{-- Assigned Designer --}}
                        <td class="text-mono-dark">{{ $record->assigned_designer ?? '—' }}</td>

                        {{-- Client Requirements Freeze --}}
                        <td class="text-mono">
                            {{ $record->client_requirements_freeze
                                ? \Carbon\Carbon::parse($record->client_requirements_freeze)->format('d M Y')
                                : '—' }}
                        </td>

                        {{-- Design Status Badge --}}
                        <td>
                            @php
                                $statusClass = match($record->design_status) {
                                    'In Progress' => 'badge-in-progress',
                                    'Submitted'   => 'badge-submitted',
                                    'Revised'     => 'badge-revised',
                                    default       => '',
                                };
                            @endphp
                            <span class="badge {{ $statusClass }}">
                                <span class="badge-dot"></span>
                                {{ $record->design_status }}
                            </span>
                        </td>

                        {{-- Client Feedback --}}
                        <td style="max-width:180px;">
                            @if($record->client_feedback)
                                <span style="font-size:12px;color:#374151;">
                                    {{ \Illuminate\Support\Str::limit($record->client_feedback, 60) }}
                                </span>
                            @else
                                <span class="text-feedback">No feedback yet</span>
                            @endif
                        </td>

                        {{-- Revision Count --}}
                        <td>
                            @if($record->revision_count === 0)
                                <span class="pip-label">None</span>
                            @else
                                <div class="revision-pips">
                                    @for($i = 0; $i < min($record->revision_count, 5); $i++)
                                        <span class="pip {{ $record->revision_count > 1 ? 'pip-high' : 'pip-low' }}"></span>
                                    @endfor
                                    @if($record->revision_count > 5)
                                        <span class="pip-label">+{{ $record->revision_count - 5 }}</span>
                                    @endif
                                </div>
                            @endif
                        </td>

                        {{-- Final 3D Approval --}}
                        <td>
                            <span class="badge {{ $record->final_3d_approval ? 'badge-yes' : 'badge-no' }}">
                                <span class="badge-dot"></span>
                                {{ $record->final_3d_approval ? 'Yes' : 'No' }}
                            </span>
                        </td>

                        {{-- Approval Date --}}
                        <td class="text-mono">
                            {{ $record->approval_date
                                ? \Carbon\Carbon::parse($record->approval_date)->format('d M Y')
                                : '—' }}
                        </td>

                        {{-- Design Freeze --}}
                        <td style="text-align:center;font-size:17px;"
                            title="{{ $record->design_freeze_confirmation ? 'Frozen' : 'Not Frozen' }}">
                            {{ $record->design_freeze_confirmation ? '🔒' : '🔓' }}
                        </td>

                        {{-- Can Proceed --}}
                        <td>
                            @if($record->final_3d_approval && $record->design_freeze_confirmation)
                                <span class="chip-go">✓ GO</span>
                            @else
                                <span class="chip-hold">HOLD</span>
                            @endif
                        </td>

                        {{-- Actions --}}
                        <td>
                            <a href="{{ route('three-d-design.edit', $record->id) }}" class="btn-edit">Edit</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="12" style="text-align:center;padding:40px;color:var(--text-muted);font-family:var(--mono);font-size:13px;">
                            No 3D design records found.
                            <a href="{{ route('three-d-design.create') }}" style="color:var(--indigo);margin-left:8px;">Create one →</a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($records->hasPages())
        <div class="mt-3 d-flex justify-content-end">
            {{ $records->links() }}
        </div>
    @endif

    {{-- Legend --}}
    <div class="legend">
        <span>🔒 Design Frozen</span>
        <span>🔓 Not Frozen</span>
        <span style="color:var(--green);font-weight:700;">✓ GO</span>
        <span>= Approval Yes + Frozen</span>
        <span style="color:var(--red);font-weight:700;">HOLD</span>
        <span>= Pending approval or freeze</span>
        <span>■ = Revision (amber = 2+)</span>
    </div>

</div>
@endsection


{{-- ============================================================
     EDIT / CREATE FORM  →  resources/views/stages/three-d-design/form.blade.php
     ============================================================ --}}
{{--
@extends('layouts.app')
@section('title', isset($record) ? 'Edit 3D Design' : 'New 3D Design')
@section('content')
<div class="container py-4" style="max-width:680px;">

    <div class="mb-4">
        <a href="{{ route('three-d-design.index') }}"
           style="font-family:var(--mono);font-size:12px;color:var(--indigo);text-decoration:none;">
            ← Back to 3D Design Stage
        </a>
        <h1 class="mt-2" style="font-family:var(--serif);font-size:24px;color:var(--text-dark);">
            {{ isset($record) ? 'Edit Design Record' : 'New 3D Design Record' }}
        </h1>
    </div>

    <div style="background:#fff;border-radius:16px;border:1px solid var(--border);padding:32px;
                box-shadow:0 4px 24px rgba(0,0,0,0.05);">

        <form action="{{ isset($record)
                            ? route('three-d-design.update', $record->id)
                            : route('three-d-design.store') }}"
              method="POST">
            @csrf
            @if(isset($record)) @method('PUT') @endif

            @if ($errors->any())
                <div class="alert-danger mb-4">
                    <ul class="mb-0" style="padding-left:18px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            ── Design Details ────────────────────────────

            <div class="mb-3">
                <label class="form-label">Project</label>
                <select name="project_id" class="form-control-mono">
                    <option value="">Select project…</option>
                    @foreach($projects as $project)
                        <option value="{{ $project->id }}"
                            {{ old('project_id', $record->project_id ?? '') == $project->id ? 'selected' : '' }}>
                            {{ $project->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Design Start Date</label>
                    <input type="date" name="design_start_date" class="form-control-mono"
                           value="{{ old('design_start_date', optional($record->design_start_date ?? null)->format('Y-m-d')) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Client Requirements Freeze</label>
                    <input type="date" name="client_requirements_freeze" class="form-control-mono"
                           value="{{ old('client_requirements_freeze', optional($record->client_requirements_freeze ?? null)->format('Y-m-d')) }}">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Assigned Designer</label>
                <input type="text" name="assigned_designer" class="form-control-mono"
                       value="{{ old('assigned_designer', $record->assigned_designer ?? '') }}"
                       placeholder="Full name">
            </div>

            ── 3D Design Tracking ────────────────────────

            <div class="mb-3">
                <label class="form-label">Design Status</label>
                <select name="design_status" class="form-control-mono">
                    @foreach(['In Progress', 'Submitted', 'Revised'] as $s)
                        <option value="{{ $s }}"
                            {{ old('design_status', $record->design_status ?? 'In Progress') === $s ? 'selected' : '' }}>
                            {{ $s }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Client Feedback</label>
                <textarea name="client_feedback" class="form-control-mono" rows="3"
                          style="resize:vertical;">{{ old('client_feedback', $record->client_feedback ?? '') }}</textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Revision Count</label>
                <input type="number" name="revision_count" class="form-control-mono"
                       style="width:100px;" min="0"
                       value="{{ old('revision_count', $record->revision_count ?? 0) }}">
            </div>

            ── Design Approval ───────────────────────────

            <div class="mb-3">
                <label class="form-label">Final 3D Approval</label>
                <select name="final_3d_approval" class="form-control-mono">
                    <option value="0" {{ !old('final_3d_approval', $record->final_3d_approval ?? false) ? 'selected' : '' }}>No</option>
                    <option value="1" {{ old('final_3d_approval', $record->final_3d_approval ?? false) ? 'selected' : '' }}>Yes</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Approval Date</label>
                <input type="date" name="approval_date" class="form-control-mono"
                       value="{{ old('approval_date', optional($record->approval_date ?? null)->format('Y-m-d')) }}">
            </div>

            <div class="mb-4">
                <label style="display:flex;align-items:center;gap:10px;cursor:pointer;font-family:var(--mono);font-size:13px;color:#374151;">
                    <input type="checkbox" name="design_freeze_confirmation" value="1"
                           style="width:16px;height:16px;accent-color:var(--indigo);"
                           {{ old('design_freeze_confirmation', $record->design_freeze_confirmation ?? false) ? 'checked' : '' }}>
                    Design Freeze Confirmed
                </label>
            </div>

            @if(isset($record) && $record->design_freeze_confirmation)
            <div style="background:var(--amber-bg);border:1px solid #FDE68A;border-left:4px solid #F59E0B;
                        border-radius:8px;padding:12px 16px;margin-bottom:20px;font-size:13px;color:#92400E;">
                <strong style="font-family:var(--mono);font-size:11px;">⚠️ DESIGN FROZEN</strong><br>
                Changes after freeze will incur additional cost. Provide a justification note below.
            </div>
            <div class="mb-4">
                <label class="form-label">Change Justification Note (required if frozen)</label>
                <textarea name="change_after_freeze_note" class="form-control-mono" rows="2"
                          placeholder="Reason for change after design freeze…"></textarea>
            </div>
            @endif

            <div class="d-flex gap-3 justify-content-end">
                <a href="{{ route('three-d-design.index') }}"
                   style="padding:9px 20px;border-radius:8px;border:1.5px solid var(--border);
                          background:#fff;font-family:var(--mono);font-size:13px;
                          color:var(--text-muted);text-decoration:none;">
                    Cancel
                </a>
                <button type="submit" class="btn-primary">
                    {{ isset($record) ? 'Save Changes' : 'Create Record' }}
                </button>
            </div>

        </form>
    </div>
</div>


@endsection
