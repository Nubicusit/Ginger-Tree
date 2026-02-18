@extends('HR.layout.app')

@section('title', 'Attendance')

@section('content')

<style>
    .dropdown-menu {
        display: none;
        position: absolute;
        right: 0;
        top: 100%;
        z-index: 100;
        min-width: 200px;
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.12);
        overflow: hidden;
        animation: dropIn 0.15s ease;
    }
    .dropdown-menu.show { display: block; }
    @keyframes dropIn {
        from { opacity: 0; transform: translateY(-6px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .dropdown-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 16px;
        font-size: 0.85rem;
        color: #374151;
        cursor: pointer;
        transition: background 0.15s;
        border: none;
        background: none;
        width: 100%;
        text-align: left;
        text-decoration: none;
    }
    .dropdown-item:hover { background: #f3f4f6; }
    .dropdown-item.danger { color: #dc2626; }
    .dropdown-item.danger:hover { background: #fef2f2; }
    .dropdown-item .icon-wrap {
        width: 28px; height: 28px; border-radius: 6px;
        display: flex; align-items: center; justify-content: center;
        font-size: 12px; flex-shrink: 0;
    }
    .dropdown-divider { height: 1px; background: #f0f0f0; margin: 4px 0; }
    .modal-overlay {
        display: none; position: fixed; inset: 0;
        background: rgba(0,0,0,0.5); z-index: 1000;
        align-items: center; justify-content: center; padding: 16px;
    }
    .modal-overlay.show { display: flex; }
    .modal-box {
        background: white; border-radius: 14px; width: 100%;
        max-width: 480px; max-height: 90vh; overflow-y: auto;
        box-shadow: 0 20px 60px rgba(0,0,0,0.2); animation: modalIn 0.2s ease;
    }
    @keyframes modalIn {
        from { opacity: 0; transform: scale(0.95); }
        to   { opacity: 1; transform: scale(1); }
    }
    .modal-header {
        display: flex; align-items: center; gap: 12px;
        padding: 20px 24px; border-bottom: 1px solid #f0f0f0;
    }
    .modal-icon {
        width: 40px; height: 40px; border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: 16px; flex-shrink: 0;
    }
    .modal-body { padding: 24px; }
    .modal-footer {
        display: flex; gap: 10px;
        padding: 16px 24px; border-top: 1px solid #f0f0f0;
    }
    .form-group { margin-bottom: 16px; }
    .form-label {
        display: block; font-size: 0.8rem; font-weight: 600;
        color: #4b5563; margin-bottom: 6px;
        text-transform: uppercase; letter-spacing: 0.04em;
    }
    .form-control {
        width: 100%; border: 1.5px solid #e5e7eb; border-radius: 8px;
        padding: 9px 12px; font-size: 0.9rem; color: #111827;
        background: #fafafa; transition: border-color 0.15s, box-shadow 0.15s;
        box-sizing: border-box;
    }
    .form-control:focus {
        outline: none; border-color: #16a34a;
        box-shadow: 0 0 0 3px rgba(22,163,74,0.12); background: white;
    }
    .btn {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 9px 18px; border-radius: 8px; font-weight: 600;
        font-size: 0.875rem; border: none; cursor: pointer; transition: all 0.15s;
    }
    .btn-primary { background: #16a34a; color: white; flex: 1; justify-content: center; }
    .btn-primary:hover { background: #15803d; }
    .btn-secondary { background: #f3f4f6; color: #374151; flex: 1; justify-content: center; }
    .btn-secondary:hover { background: #e5e7eb; }
    .btn-danger { background: #dc2626; color: white; flex: 1; justify-content: center; }
    .btn-danger:hover { background: #b91c1c; }
    .btn-warning { background: #f59e0b; color: white; flex: 1; justify-content: center; }
    .btn-warning:hover { background: #d97706; }
    .employee-info-card {
        background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 10px;
        padding: 12px 16px; margin-bottom: 20px;
        display: flex; align-items: center; gap: 12px;
    }
    .avatar-sm {
        width: 36px; height: 36px; border-radius: 50%; background: #16a34a;
        color: white; display: flex; align-items: center; justify-content: center;
        font-size: 13px; font-weight: 700; flex-shrink: 0;
    }
    .leave-type-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; margin-top: 6px; }
    .leave-type-option {
        border: 1.5px solid #e5e7eb; border-radius: 8px; padding: 10px 12px;
        cursor: pointer; display: flex; align-items: center; gap: 8px;
        font-size: 0.85rem; transition: all 0.15s;
    }
    .leave-type-option:hover { border-color: #16a34a; background: #f0fdf4; }
    .leave-type-option input[type="radio"] { accent-color: #16a34a; }
    .action-btn {
        display: inline-flex; align-items: center; gap: 4px;
        padding: 6px 12px; border-radius: 6px; font-size: 0.78rem;
        font-weight: 600; border: none; cursor: pointer; transition: all 0.15s;
        background: #f3f4f6; color: #374151;
    }
    .action-btn:hover { background: #e5e7eb; }
    .dropdown-wrapper { position: relative; display: inline-block; }
    .chevron-icon { transition: transform 0.2s; }
    .chevron-icon.rotated { transform: rotate(180deg); }
</style>

<!-- Success/Error Messages -->
@if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6 flex items-center justify-between">
        <span>{{ session('success') }}</span>
        <button onclick="this.parentElement.remove()" class="text-green-700 hover:text-green-900">
            <i class="fa-solid fa-times"></i>
        </button>
    </div>
@endif

@if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6 flex items-center justify-between">
        <span>{{ session('error') }}</span>
        <button onclick="this.parentElement.remove()" class="text-red-700 hover:text-red-900">
            <i class="fa-solid fa-times"></i>
        </button>
    </div>
@endif

@if ($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
        <ul class="list-disc list-inside">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<!-- Page Header -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Attendance</h1>
        <p class="text-gray-500 text-sm">Manage employee attendance records</p>
    </div>
    <div class="mt-4 sm:mt-0">
        <button onclick="openMarkAttendanceModal()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center gap-2">
            <i class="fa-solid fa-plus"></i> Mark Attendance
        </button>
    </div>
</div>

<!-- Filters -->
<div class="bg-white rounded-xl shadow p-4 mb-6">
    <form method="GET" action="{{ route('hr.attendance') }}" class="flex flex-col md:flex-row gap-4">
        <input type="date" name="date" value="{{ request('date') }}"
            class="w-full md:w-1/4 border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:outline-none"
            onchange="this.form.submit()" />

        <select name="employee_id"
            class="w-full md:w-1/4 border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:outline-none"
            onchange="this.form.submit()">
            <option value="">All Employees</option>
            @foreach($employees ?? [] as $employee)
                <option value="{{ $employee->id }}" {{ request('employee_id') == $employee->id ? 'selected' : '' }}>
                    {{ $employee->first_name }} {{ $employee->last_name }}
                </option>
            @endforeach
        </select>

        <select name="status"
            class="w-full md:w-1/4 border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:outline-none"
            onchange="this.form.submit()">
            <option value="">All Status</option>
            <option value="present"  {{ request('status') == 'present'  ? 'selected' : '' }}>Present</option>
            <option value="absent"   {{ request('status') == 'absent'   ? 'selected' : '' }}>Absent</option>
            <option value="late"     {{ request('status') == 'late'     ? 'selected' : '' }}>Late</option>
            <option value="half_day" {{ request('status') == 'half_day' ? 'selected' : '' }}>Half Day</option>
            <option value="leave"    {{ request('status') == 'leave'    ? 'selected' : '' }}>On Leave</option>
        </select>

        @if(request()->hasAny(['date', 'employee_id', 'status']))
            <a href="{{ route('hr.attendance') }}" class="w-full md:w-auto bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg transition duration-200 flex items-center justify-center gap-2">
                <i class="fa-solid fa-times"></i> Clear Filters
            </a>
        @endif
    </form>
</div>

<!-- Attendance Table -->
<div class="bg-white rounded-xl shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
                <tr>
                    <th class="px-6 py-4">Employee</th>
                    <th class="px-6 py-4">Date</th>
                    <th class="px-6 py-4">Check In</th>
                    <th class="px-6 py-4">Check Out</th>
                    <th class="px-6 py-4">Status / Details</th>
                    <th class="px-6 py-4">Notes</th>
                    <th class="px-6 py-4">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($attendances ?? [] as $attendance)
                    @php
                        $firstName = $attendance->employee->first_name ?? 'U';
                        $lastName  = $attendance->employee->last_name  ?? 'N';
                        $fullName  = trim("$firstName $lastName");
                        $initials  = strtoupper(substr($firstName, 0, 1)) . strtoupper(substr($lastName, 0, 1));
                        $empId     = $attendance->employee->employee_id ?? '';
                        $dept      = $attendance->employee->department->name ?? null;
                        $position  = $attendance->employee->position ?? null;
                    @endphp
                    <tr class="hover:bg-gray-50">

                        <!-- Employee -->
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center text-green-700 font-bold text-sm flex-shrink-0">
                                    {{ $initials }}
                                </div>
                                <div>
                                    <div class="font-semibold text-gray-800 leading-tight">{{ $fullName }}</div>
                                    <div class="flex items-center gap-2 mt-0.5 flex-wrap">
                                        @if($empId)
                                            <span class="text-xs text-gray-400 font-mono">{{ $empId }}</span>
                                        @endif
                                        @if($dept)
                                            <span class="text-xs bg-gray-100 text-gray-500 px-1.5 py-0.5 rounded">{{ $dept }}</span>
                                        @endif
                                        @if($position)
                                            <span class="text-xs text-gray-400 italic">{{ $position }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </td>

                        <!-- Date -->
                        <td class="px-6 py-4 text-gray-700 whitespace-nowrap">
                            {{ \Carbon\Carbon::parse($attendance->date)->format('d M, Y') }}
                        </td>

                        <!-- Check In -->
                        <td class="px-6 py-4">
                            <span class="font-medium {{ $attendance->check_in ? 'text-green-600' : 'text-gray-400' }}">
                                {{ $attendance->check_in ? \Carbon\Carbon::parse($attendance->check_in)->format('h:i A') : '—' }}
                            </span>
                        </td>

                        <!-- Check Out -->
                        <td class="px-6 py-4">
                            <span class="font-medium {{ $attendance->check_out ? 'text-blue-600' : 'text-gray-400' }}">
                                {{ $attendance->check_out ? \Carbon\Carbon::parse($attendance->check_out)->format('h:i A') : '—' }}
                            </span>
                        </td>

                        <!-- ✅ Status + extra details (leave_type, leave_status, late_minutes) -->
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 text-xs rounded-full font-semibold
                                @if($attendance->status === 'present')   bg-green-100 text-green-700
                                @elseif($attendance->status === 'absent') bg-red-100 text-red-700
                                @elseif($attendance->status === 'late')   bg-yellow-100 text-yellow-700
                                @elseif($attendance->status === 'half_day') bg-blue-100 text-blue-700
                                @elseif($attendance->status === 'leave')  bg-purple-100 text-purple-700
                                @else bg-gray-100 text-gray-700 @endif">
                                @if($attendance->status === 'half_day') Half Day
                                @elseif($attendance->status === 'leave') On Leave
                                @else {{ ucfirst($attendance->status ?? 'pending') }}
                                @endif
                            </span>

                            {{-- ✅ Late minutes --}}
                            @if($attendance->late_minutes)
                                <div class="text-xs text-yellow-600 mt-1 flex items-center gap-1">
                                    <i class="fa-solid fa-clock"></i>
                                    {{ $attendance->late_minutes }} mins late
                                </div>
                            @endif

                            {{-- ✅ Leave type + status --}}
                            @if($attendance->leave_type)
                                <div class="text-xs text-purple-600 mt-1 flex items-center gap-1">
                                    <i class="fa-solid fa-umbrella-beach"></i>
                                    {{ ucfirst($attendance->leave_type) }} Leave
                                    @if($attendance->leave_status)
                                        &middot;
                                        <span class="
                                            @if($attendance->leave_status === 'approved') text-green-600
                                            @elseif($attendance->leave_status === 'pending') text-yellow-600
                                            @else text-gray-500
                                            @endif">
                                            {{ ucfirst($attendance->leave_status) }}
                                        </span>
                                    @endif
                                </div>
                            @endif
                        </td>

                        {{-- ✅ Notes column --}}
                        <td class="px-6 py-4 max-w-xs">
                            @if($attendance->notes)
                                <span class="text-xs text-gray-500 italic" title="{{ $attendance->notes }}">
                                    {{ \Illuminate\Support\Str::limit($attendance->notes, 50) }}
                                </span>
                            @else
                                <span class="text-gray-300">—</span>
                            @endif
                        </td>

                        <!-- Actions -->
                        <td class="px-6 py-4">
                            <div class="dropdown-wrapper">
                                <button class="action-btn" onclick="toggleDropdown('dd-{{ $attendance->id }}', this)">
                                    <i class="fa-solid fa-ellipsis-vertical"></i>
                                    Actions
                                    <i class="fa-solid fa-chevron-down chevron-icon" style="font-size:10px"></i>
                                </button>
                                <div class="dropdown-menu" id="dd-{{ $attendance->id }}">

                                    <a href="{{ route('hr.attendance.edit', $attendance->id) }}" class="dropdown-item">
                                        <span class="icon-wrap" style="background:#eff6ff;color:#2563eb">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </span>
                                        Correct Attendance
                                    </a>

                                    {{-- ✅ Fixed: removed double >> --}}
                                    <button class="dropdown-item"
                                        onclick="openQuickStatusModal({{ $attendance->id }}, '{{ addslashes($fullName) }}', '{{ $initials }}', '{{ \Carbon\Carbon::parse($attendance->date)->format('d M, Y') }}', 'present', {{ $attendance->employee_id ?? 'null' }}, '{{ \Carbon\Carbon::parse($attendance->date)->format('Y-m-d') }}'); closeDropdown('dd-{{ $attendance->id }}')">
                                        <span class="icon-wrap" style="background:#f0fdf4;color:#16a34a">
                                            <i class="fa-solid fa-circle-check"></i>
                                        </span>
                                        Mark Present
                                    </button>

                                    <button class="dropdown-item"
                                        onclick="openQuickStatusModal({{ $attendance->id }}, '{{ addslashes($fullName) }}', '{{ $initials }}', '{{ \Carbon\Carbon::parse($attendance->date)->format('d M, Y') }}', 'absent', {{ $attendance->employee_id ?? 'null' }}, '{{ \Carbon\Carbon::parse($attendance->date)->format('Y-m-d') }}'); closeDropdown('dd-{{ $attendance->id }}')">
                                        <span class="icon-wrap" style="background:#fef2f2;color:#dc2626">
                                            <i class="fa-solid fa-circle-xmark"></i>
                                        </span>
                                        Mark Absent
                                    </button>

                                    <button class="dropdown-item"
                                        onclick="openLateModal({{ $attendance->id }}, '{{ addslashes($fullName) }}', '{{ $initials }}', '{{ \Carbon\Carbon::parse($attendance->date)->format('d M, Y') }}', '{{ $attendance->check_in ? \Carbon\Carbon::parse($attendance->check_in)->format('H:i') : '' }}', {{ $attendance->employee_id ?? 'null' }}, '{{ \Carbon\Carbon::parse($attendance->date)->format('Y-m-d') }}'); closeDropdown('dd-{{ $attendance->id }}')">
                                        <span class="icon-wrap" style="background:#fffbeb;color:#d97706">
                                            <i class="fa-solid fa-clock"></i>
                                        </span>
                                        Mark as Late
                                    </button>

                                    <button class="dropdown-item"
                                        onclick="openLeaveModal({{ $attendance->id }}, '{{ addslashes($fullName) }}', '{{ $initials }}', '{{ \Carbon\Carbon::parse($attendance->date)->format('d M, Y') }}', {{ $attendance->employee_id ?? 'null' }}, '{{ \Carbon\Carbon::parse($attendance->date)->format('Y-m-d') }}'); closeDropdown('dd-{{ $attendance->id }}')">
                                        <span class="icon-wrap" style="background:#faf5ff;color:#7c3aed">
                                            <i class="fa-solid fa-umbrella-beach"></i>
                                        </span>
                                        Mark Leave
                                    </button>

                                    <button class="dropdown-item"
                                        onclick="openCheckInModal({{ $attendance->id }}, '{{ addslashes($fullName) }}', '{{ $initials }}', '{{ \Carbon\Carbon::parse($attendance->date)->format('d M, Y') }}', '{{ $attendance->check_in ? \Carbon\Carbon::parse($attendance->check_in)->format('H:i') : '' }}', '{{ $attendance->check_out ? \Carbon\Carbon::parse($attendance->check_out)->format('H:i') : '' }}', {{ $attendance->employee_id ?? 'null' }}, '{{ \Carbon\Carbon::parse($attendance->date)->format('Y-m-d') }}'); closeDropdown('dd-{{ $attendance->id }}')">
                                        <span class="icon-wrap" style="background:#ecfdf5;color:#059669">
                                            <i class="fa-solid fa-fingerprint"></i>
                                        </span>
                                        Add Manual Check-In
                                    </button>

                                    <div class="dropdown-divider"></div>

                                    <form action="{{ route('hr.attendance.destroy', $attendance->id) }}" method="POST" onsubmit="return confirm('Delete this attendance record?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="dropdown-item danger">
                                            <span class="icon-wrap" style="background:#fef2f2;color:#dc2626">
                                                <i class="fa-solid fa-trash"></i>
                                            </span>
                                            Delete Record
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-12 text-gray-500">
                            <i class="fa-solid fa-calendar-xmark text-4xl mb-2 block"></i>
                            <p>No attendance records found</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if(isset($attendances) && $attendances->hasPages())
        <div class="px-6 py-4 border-t">
            {{ $attendances->appends(request()->query())->links() }}
        </div>
    @endif
</div>


{{-- ═══════════════════════════════════════════════════════
     MODAL: Mark Attendance (new record)
══════════════════════════════════════════════════════════ --}}
<div id="markAttendanceModal" class="modal-overlay">
    <div class="modal-box">
        <div class="modal-header">
            <div class="modal-icon" style="background:#f0fdf4;color:#16a34a">
                <i class="fa-solid fa-plus"></i>
            </div>
            <div>
                <h3 class="text-lg font-bold text-gray-800">Mark Attendance</h3>
                <p class="text-xs text-gray-500">Add a new attendance record</p>
            </div>
            <button onclick="closeModal('markAttendanceModal')" class="ml-auto text-gray-400 hover:text-gray-600 p-1">
                <i class="fa-solid fa-times text-lg"></i>
            </button>
        </div>
        <form action="{{ route('hr.attendance.store') }}" method="POST">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Employee <span class="text-red-500">*</span></label>
                    <select name="employee_id" required class="form-control">
                        <option value="">Select Employee</option>
                        @foreach($employees ?? [] as $employee)
                            <option value="{{ $employee->id }}">{{ $employee->first_name }} {{ $employee->last_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Date <span class="text-red-500">*</span></label>
                    <input type="date" name="date" value="{{ date('Y-m-d') }}" required class="form-control">
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
                    <div class="form-group">
                        <label class="form-label">Check In</label>
                        <input type="time" name="check_in" class="form-control">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Check Out</label>
                        <input type="time" name="check_out" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Status <span class="text-red-500">*</span></label>
                    <select name="status" required class="form-control" id="newRecordStatus" onchange="toggleNewRecordFields()">
                        <option value="present">Present</option>
                        <option value="absent">Absent</option>
                        <option value="late">Late</option>
                        <option value="half_day">Half Day</option>
                        <option value="leave">On Leave</option>
                    </select>
                </div>

                {{-- ✅ Late minutes (shown when status = late) --}}
                <div class="form-group" id="newLateMinutesGroup" style="display:none">
                    <label class="form-label">Minutes Late</label>
                    <input type="number" name="late_minutes" min="1" class="form-control" placeholder="e.g. 30">
                </div>

                {{-- ✅ Leave fields (shown when status = leave) --}}
                <div id="newLeaveFields" style="display:none">
                    <div class="form-group">
                        <label class="form-label">Leave Type <span class="text-red-500">*</span></label>
                        <div class="leave-type-grid">
                            <label class="leave-type-option">
                                <input type="radio" name="leave_type" value="annual">
                                <i class="fa-solid fa-sun text-yellow-500"></i> Annual
                            </label>
                            <label class="leave-type-option">
                                <input type="radio" name="leave_type" value="sick">
                                <i class="fa-solid fa-kit-medical text-red-400"></i> Sick
                            </label>
                            <label class="leave-type-option">
                                <input type="radio" name="leave_type" value="emergency">
                                <i class="fa-solid fa-triangle-exclamation text-orange-500"></i> Emergency
                            </label>
                            <label class="leave-type-option">
                                <input type="radio" name="leave_type" value="unpaid">
                                <i class="fa-solid fa-ban text-gray-400"></i> Unpaid
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Leave Status</label>
                        <select name="leave_status" class="form-control">
                            <option value="approved">Approved</option>
                            <option value="pending">Pending Approval</option>
                            <option value="applied">Self-Applied</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Notes / Reason</label>
                    <textarea name="notes" rows="2" class="form-control" placeholder="Optional notes or reason..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save"></i> Save Attendance</button>
                <button type="button" onclick="closeModal('markAttendanceModal')" class="btn btn-secondary">Cancel</button>
            </div>
        </form>
    </div>
</div>


{{-- ═══════════════════════════════════════════════════════
     MODAL: Quick Status (Mark Absent / Mark Present)
══════════════════════════════════════════════════════════ --}}
<div id="quickStatusModal" class="modal-overlay">
    <div class="modal-box">
        <div class="modal-header">
            <div class="modal-icon" id="quickStatusIcon" style="background:#fef2f2;color:#dc2626">
                <i class="fa-solid fa-circle-xmark"></i>
            </div>
            <div>
                <h3 class="text-lg font-bold text-gray-800" id="quickStatusTitle">Mark Absent</h3>
                <p class="text-xs text-gray-500">Update attendance status</p>
            </div>
            <button onclick="closeModal('quickStatusModal')" class="ml-auto text-gray-400 hover:text-gray-600 p-1">
                <i class="fa-solid fa-times text-lg"></i>
            </button>
        </div>
        <form id="quickStatusForm" action="" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" name="status"      id="quickStatusValue">
            <input type="hidden" name="employee_id" id="quickStatusEmployeeId">
            <input type="hidden" name="date"        id="quickStatusDateValue">
            <div class="modal-body">
                <div class="employee-info-card">
                    <div class="avatar-sm" id="quickStatusAvatar">UN</div>
                    <div>
                        <div class="font-semibold text-gray-800 text-sm" id="quickStatusName">Employee Name</div>
                        <div class="text-xs text-gray-500"               id="quickStatusDate">Date</div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Reason / Notes</label>
                    <textarea name="notes" rows="3" class="form-control" placeholder="Add a reason or notes (optional)..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn" id="quickStatusBtn" style="flex:1;justify-content:center;background:#dc2626;color:white">
                    <i class="fa-solid fa-circle-xmark"></i> Confirm
                </button>
                <button type="button" onclick="closeModal('quickStatusModal')" class="btn btn-secondary">Cancel</button>
            </div>
        </form>
    </div>
</div>


{{-- ═══════════════════════════════════════════════════════
     MODAL: Mark as Late
══════════════════════════════════════════════════════════ --}}
<div id="lateModal" class="modal-overlay">
    <div class="modal-box">
        <div class="modal-header">
            <div class="modal-icon" style="background:#fffbeb;color:#d97706">
                <i class="fa-solid fa-clock"></i>
            </div>
            <div>
                <h3 class="text-lg font-bold text-gray-800">Mark as Late</h3>
                <p class="text-xs text-gray-500">Record a late arrival</p>
            </div>
            <button onclick="closeModal('lateModal')" class="ml-auto text-gray-400 hover:text-gray-600 p-1">
                <i class="fa-solid fa-times text-lg"></i>
            </button>
        </div>
        <form id="lateForm" action="" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" name="status" value="late">
            <input type="hidden" name="employee_id" id="lateEmployeeId">
            <input type="hidden" name="date"        id="lateDateValue">
            <div class="modal-body">
                <div class="employee-info-card" style="background:#fffbeb;border-color:#fde68a">
                    <div class="avatar-sm" id="lateAvatar" style="background:#d97706">UN</div>
                    <div>
                        <div class="font-semibold text-gray-800 text-sm" id="lateName">Employee Name</div>
                        <div class="text-xs text-gray-500"               id="lateDate">Date</div>
                    </div>
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
                    <div class="form-group">
                        <label class="form-label">Actual Check-In Time <span class="text-red-500">*</span></label>
                        <input type="time" name="check_in" id="lateCheckIn" required class="form-control">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Minutes Late</label>
                        {{-- ✅ FIXED: was name="minutes_late", must be name="late_minutes" --}}
                        <input type="number" name="late_minutes" id="minutesLate" min="1" class="form-control" placeholder="Auto-calculated" readonly>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Scheduled Start Time</label>
                    <input type="time" id="scheduledStart" class="form-control" value="09:00" onchange="calcMinutesLate()">
                    <p class="text-xs text-gray-400 mt-1">Default is 09:00. Adjust to employee's schedule.</p>
                </div>
                <div class="form-group">
                    <label class="form-label">Reason for Late Arrival</label>
                    <textarea name="notes" rows="2" class="form-control" placeholder="e.g. Traffic, personal emergency..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-warning"><i class="fa-solid fa-clock"></i> Save Late Record</button>
                <button type="button" onclick="closeModal('lateModal')" class="btn btn-secondary">Cancel</button>
            </div>
        </form>
    </div>
</div>


{{-- ═══════════════════════════════════════════════════════
     MODAL: Mark Leave
══════════════════════════════════════════════════════════ --}}
<div id="leaveModal" class="modal-overlay">
    <div class="modal-box">
        <div class="modal-header">
            <div class="modal-icon" style="background:#faf5ff;color:#7c3aed">
                <i class="fa-solid fa-umbrella-beach"></i>
            </div>
            <div>
                <h3 class="text-lg font-bold text-gray-800">Mark Leave</h3>
                <p class="text-xs text-gray-500">Record an approved or applied leave</p>
            </div>
            <button onclick="closeModal('leaveModal')" class="ml-auto text-gray-400 hover:text-gray-600 p-1">
                <i class="fa-solid fa-times text-lg"></i>
            </button>
        </div>
        <form id="leaveForm" action="" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" name="status" value="leave">
            <input type="hidden" name="employee_id" id="leaveEmployeeId">
            <input type="hidden" name="date"        id="leaveDateValue">
            <div class="modal-body">
                <div class="employee-info-card" style="background:#faf5ff;border-color:#ddd6fe">
                    <div class="avatar-sm" id="leaveAvatar" style="background:#7c3aed">UN</div>
                    <div>
                        <div class="font-semibold text-gray-800 text-sm" id="leaveName">Employee Name</div>
                        <div class="text-xs text-gray-500"               id="leaveDate">Date</div>
                    </div>
                </div>
                {{-- ✅ leave_type --}}
                <div class="form-group">
                    <label class="form-label">Leave Type <span class="text-red-500">*</span></label>
                    <div class="leave-type-grid">
                        <label class="leave-type-option">
                            <input type="radio" name="leave_type" value="annual" required>
                            <i class="fa-solid fa-sun text-yellow-500"></i> Annual Leave
                        </label>
                        <label class="leave-type-option">
                            <input type="radio" name="leave_type" value="sick">
                            <i class="fa-solid fa-kit-medical text-red-400"></i> Sick Leave
                        </label>
                        <label class="leave-type-option">
                            <input type="radio" name="leave_type" value="emergency">
                            <i class="fa-solid fa-triangle-exclamation text-orange-500"></i> Emergency
                        </label>
                        <label class="leave-type-option">
                            <input type="radio" name="leave_type" value="unpaid">
                            <i class="fa-solid fa-ban text-gray-400"></i> Unpaid Leave
                        </label>
                    </div>
                </div>
                {{-- ✅ leave_status --}}
                <div class="form-group">
                    <label class="form-label">Leave Status <span class="text-red-500">*</span></label>
                    <select name="leave_status" class="form-control">
                        <option value="approved">Approved</option>
                        <option value="pending">Pending Approval</option>
                        <option value="applied">Self-Applied</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Notes / Remarks</label>
                    <textarea name="notes" rows="2" class="form-control" placeholder="Reason for leave, approval reference, etc."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn" style="flex:1;justify-content:center;background:#7c3aed;color:white">
                    <i class="fa-solid fa-umbrella-beach"></i> Save Leave Record
                </button>
                <button type="button" onclick="closeModal('leaveModal')" class="btn btn-secondary">Cancel</button>
            </div>
        </form>
    </div>
</div>


{{-- ═══════════════════════════════════════════════════════
     MODAL: Add Manual Check-In
══════════════════════════════════════════════════════════ --}}
<div id="checkInModal" class="modal-overlay">
    <div class="modal-box">
        <div class="modal-header">
            <div class="modal-icon" style="background:#ecfdf5;color:#059669">
                <i class="fa-solid fa-fingerprint"></i>
            </div>
            <div>
                <h3 class="text-lg font-bold text-gray-800">Add Manual Check-In</h3>
                <p class="text-xs text-gray-500">Manually log check-in/check-out times</p>
            </div>
            <button onclick="closeModal('checkInModal')" class="ml-auto text-gray-400 hover:text-gray-600 p-1">
                <i class="fa-solid fa-times text-lg"></i>
            </button>
        </div>
        <form id="checkInForm" action="" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" name="employee_id" id="checkInEmployeeId">
            <input type="hidden" name="date"        id="checkInDateValue">
            <div class="modal-body">
                <div class="employee-info-card">
                    <div class="avatar-sm" id="checkInAvatar">UN</div>
                    <div>
                        <div class="font-semibold text-gray-800 text-sm" id="checkInName">Employee Name</div>
                        <div class="text-xs text-gray-500"               id="checkInDate">Date</div>
                    </div>
                </div>
                <div style="background:#fffbeb;border:1px solid #fde68a;border-radius:8px;padding:10px 14px;margin-bottom:16px;font-size:0.82rem;color:#92400e;display:flex;align-items:center;gap:8px">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    This will overwrite any existing check-in/out times for this record.
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
                    <div class="form-group">
                        <label class="form-label">Check-In Time <span class="text-red-500">*</span></label>
                        <input type="time" name="check_in" id="manualCheckIn" required class="form-control">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Check-Out Time</label>
                        <input type="time" name="check_out" id="manualCheckOut" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Updated Status <span class="text-red-500">*</span></label>
                    <select name="status" required class="form-control">
                        <option value="present">Present</option>
                        <option value="late">Late</option>
                        <option value="half_day">Half Day</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Reason for Manual Entry <span class="text-red-500">*</span></label>
                    <textarea name="notes" rows="2" class="form-control" required placeholder="e.g. Biometric machine failure, forgot to punch in..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn" style="flex:1;justify-content:center;background:#059669;color:white">
                    <i class="fa-solid fa-fingerprint"></i> Save Check-In
                </button>
                <button type="button" onclick="closeModal('checkInModal')" class="btn btn-secondary">Cancel</button>
            </div>
        </form>
    </div>
</div>


<script>
/* ──────────────── DROPDOWN ──────────────── */
let activeDropdown = null;

function toggleDropdown(id, btn) {
    const menu    = document.getElementById(id);
    const chevron = btn.querySelector('.chevron-icon');
    if (activeDropdown && activeDropdown !== menu) {
        activeDropdown.classList.remove('show');
        activeDropdown.previousElementSibling?.querySelector('.chevron-icon')?.classList.remove('rotated');
    }
    menu.classList.toggle('show');
    chevron?.classList.toggle('rotated');
    activeDropdown = menu.classList.contains('show') ? menu : null;
}

function closeDropdown(id) {
    const menu = document.getElementById(id);
    menu?.classList.remove('show');
    menu?.previousElementSibling?.querySelector('.chevron-icon')?.classList.remove('rotated');
    if (activeDropdown === menu) activeDropdown = null;
}

document.addEventListener('click', function(e) {
    if (!e.target.closest('.dropdown-wrapper')) {
        document.querySelectorAll('.dropdown-menu.show').forEach(m => {
            m.classList.remove('show');
            m.previousElementSibling?.querySelector('.chevron-icon')?.classList.remove('rotated');
        });
        activeDropdown = null;
    }
});


/* ──────────────── MODAL HELPERS ──────────────── */
function openModal(id) {
    document.getElementById(id).classList.add('show');
    document.body.style.overflow = 'hidden';
}
function closeModal(id) {
    document.getElementById(id).classList.remove('show');
    document.body.style.overflow = '';
}

document.querySelectorAll('.modal-overlay').forEach(overlay => {
    overlay.addEventListener('click', function(e) {
        if (e.target === this) closeModal(this.id);
    });
});


/* ──────────────── NEW RECORD: toggle leave/late fields ──────────────── */
function toggleNewRecordFields() {
    const status = document.getElementById('newRecordStatus').value;
    document.getElementById('newLeaveFields').style.display    = (status === 'leave') ? 'block' : 'none';
    document.getElementById('newLateMinutesGroup').style.display = (status === 'late')  ? 'block' : 'none';
}


/* ──────────────── MARK ATTENDANCE (new) ──────────────── */
function openMarkAttendanceModal() { openModal('markAttendanceModal'); }


/* ──────────────── QUICK STATUS (absent / present) ──────────────── */
function openQuickStatusModal(id, name, initials, date, status, employeeId, rawDate) {
    document.getElementById('quickStatusForm').action            = `/hr/attendance/${id}`;
    document.getElementById('quickStatusValue').value            = status;
    document.getElementById('quickStatusEmployeeId').value       = employeeId;
    document.getElementById('quickStatusDateValue').value        = rawDate;
    document.getElementById('quickStatusName').textContent       = name;
    document.getElementById('quickStatusDate').textContent       = date;
    document.getElementById('quickStatusAvatar').textContent     = initials;

    const icon  = document.getElementById('quickStatusIcon');
    const title = document.getElementById('quickStatusTitle');
    const btn   = document.getElementById('quickStatusBtn');
    const avatar = document.getElementById('quickStatusAvatar');

    if (status === 'absent') {
        icon.style.cssText   = 'background:#fef2f2;color:#dc2626;width:40px;height:40px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:16px;flex-shrink:0';
        icon.innerHTML       = '<i class="fa-solid fa-circle-xmark"></i>';
        title.textContent    = 'Mark Absent';
        btn.style.background = '#dc2626';
        btn.innerHTML        = '<i class="fa-solid fa-circle-xmark"></i> Mark Absent';
        avatar.style.background = '#dc2626';
    } else {
        icon.style.cssText   = 'background:#f0fdf4;color:#16a34a;width:40px;height:40px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:16px;flex-shrink:0';
        icon.innerHTML       = '<i class="fa-solid fa-circle-check"></i>';
        title.textContent    = 'Mark Present';
        btn.style.background = '#16a34a';
        btn.innerHTML        = '<i class="fa-solid fa-circle-check"></i> Mark Present';
        avatar.style.background = '#16a34a';
    }
    openModal('quickStatusModal');
}


/* ──────────────── LATE MODAL ──────────────── */
function openLateModal(id, name, initials, date, existingCheckIn, employeeId, rawDate) {
    document.getElementById('lateForm').action            = `/hr/attendance/${id}`;
    document.getElementById('lateEmployeeId').value       = employeeId;
    document.getElementById('lateDateValue').value        = rawDate;
    document.getElementById('lateName').textContent       = name;
    document.getElementById('lateDate').textContent       = date;
    document.getElementById('lateAvatar').textContent     = initials;
    document.getElementById('lateCheckIn').value          = existingCheckIn || '';
    document.getElementById('minutesLate').value          = '';
    document.getElementById('scheduledStart').value       = '09:00';
    if (existingCheckIn) calcMinutesLate();
    openModal('lateModal');
}

document.getElementById('lateCheckIn')?.addEventListener('change', calcMinutesLate);

function calcMinutesLate() {
    const checkIn   = document.getElementById('lateCheckIn').value;
    const scheduled = document.getElementById('scheduledStart').value;
    if (!checkIn || !scheduled) return;
    const [ch, cm] = checkIn.split(':').map(Number);
    const [sh, sm] = scheduled.split(':').map(Number);
    const diff = (ch * 60 + cm) - (sh * 60 + sm);
    document.getElementById('minutesLate').value = diff > 0 ? diff : 0;
}


/* ──────────────── LEAVE MODAL ──────────────── */
function openLeaveModal(id, name, initials, date, employeeId, rawDate) {
    document.getElementById('leaveForm').action           = `/hr/attendance/${id}`;
    document.getElementById('leaveEmployeeId').value      = employeeId;
    document.getElementById('leaveDateValue').value       = rawDate;
    document.getElementById('leaveName').textContent      = name;
    document.getElementById('leaveDate').textContent      = date;
    document.getElementById('leaveAvatar').textContent    = initials;
    // Reset radio buttons
    document.querySelectorAll('#leaveForm input[name="leave_type"]').forEach(r => r.checked = false);
    openModal('leaveModal');
}


/* ──────────────── MANUAL CHECK-IN MODAL ──────────────── */
function openCheckInModal(id, name, initials, date, existingIn, existingOut, employeeId, rawDate) {
    document.getElementById('checkInForm').action         = `/hr/attendance/${id}`;
    document.getElementById('checkInEmployeeId').value    = employeeId;
    document.getElementById('checkInDateValue').value     = rawDate;
    document.getElementById('checkInName').textContent    = name;
    document.getElementById('checkInDate').textContent    = date;
    document.getElementById('checkInAvatar').textContent  = initials;
    document.getElementById('manualCheckIn').value        = existingIn  || '';
    document.getElementById('manualCheckOut').value       = existingOut || '';
    openModal('checkInModal');
}
</script>

@endsection