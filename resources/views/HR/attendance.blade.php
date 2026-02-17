@extends('HR.layout.app')

@section('title', 'Attendance')

@section('content')

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
            <i class="fa-solid fa-plus"></i>
            Mark Attendance
        </button>
    </div>
</div>

<!-- Filters -->
<div class="bg-white rounded-xl shadow p-4 mb-6">
    <form method="GET" action="{{ route('hr.attendance') }}" class="flex flex-col md:flex-row gap-4">
        <input
            type="date"
            name="date"
            value="{{ request('date') }}"
            class="w-full md:w-1/4 border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:outline-none"
            onchange="this.form.submit()"
        />

        <select
            name="employee_id"
            class="w-full md:w-1/4 border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:outline-none"
            onchange="this.form.submit()">
            <option value="">All Employees</option>
            @foreach($employees ?? [] as $employee)
                <option value="{{ $employee->id }}" {{ request('employee_id') == $employee->id ? 'selected' : '' }}>
                    {{ $employee->first_name }} {{ $employee->last_name }}
                </option>
            @endforeach
        </select>

        <select
            name="status"
            class="w-full md:w-1/4 border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:outline-none"
            onchange="this.form.submit()">
            <option value="">All Status</option>
            <option value="present" {{ request('status') == 'present' ? 'selected' : '' }}>Present</option>
            <option value="absent" {{ request('status') == 'absent' ? 'selected' : '' }}>Absent</option>
            <option value="late" {{ request('status') == 'late' ? 'selected' : '' }}>Late</option>
            <option value="half_day" {{ request('status') == 'half_day' ? 'selected' : '' }}>Half Day</option>
        </select>

        @if(request()->hasAny(['date', 'employee_id', 'status']))
            <a href="{{ route('hr.attendance') }}" class="w-full md:w-auto bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg transition duration-200 flex items-center justify-center gap-2">
                <i class="fa-solid fa-times"></i>
                Clear Filters
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
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($attendances ?? [] as $attendance)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center text-green-700 font-semibold">
                                    {{ strtoupper(substr($attendance->employee->first_name ?? 'U', 0, 1)) }}{{ strtoupper(substr($attendance->employee->last_name ?? 'N', 0, 1)) }}
                                </div>
                                <div>
                                    <div class="font-medium text-gray-800">
                                        {{ $attendance->employee->first_name ?? '-' }} {{ $attendance->employee->last_name ?? '' }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ $attendance->employee->employee_id ?? '' }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            {{ \Carbon\Carbon::parse($attendance->date)->format('d M, Y') }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="font-medium {{ $attendance->check_in ? 'text-green-600' : 'text-gray-400' }}">
                                {{ $attendance->check_in ? \Carbon\Carbon::parse($attendance->check_in)->format('h:i A') : '-' }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="font-medium {{ $attendance->check_out ? 'text-blue-600' : 'text-gray-400' }}">
                                {{ $attendance->check_out ? \Carbon\Carbon::parse($attendance->check_out)->format('h:i A') : '-' }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 text-xs rounded-full font-medium
                                @if($attendance->status === 'present')
                                    bg-green-100 text-green-700
                                @elseif($attendance->status === 'absent')
                                    bg-red-100 text-red-700
                                @elseif($attendance->status === 'late')
                                    bg-yellow-100 text-yellow-700
                                @elseif($attendance->status === 'half_day')
                                    bg-blue-100 text-blue-700
                                @else
                                    bg-gray-100 text-gray-700
                                @endif">
                                {{ ucfirst(str_replace('_', ' ', $attendance->status ?? 'pending')) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('hr.attendance.edit', $attendance->id) }}" class="text-blue-600 hover:text-blue-800 transition" title="Edit">
                                    <i class="fa-solid fa-edit"></i>
                                </a>
                                <form action="{{ route('hr.attendance.destroy', $attendance->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 transition" title="Delete">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-12 text-gray-500">
                            <i class="fa-solid fa-calendar-xmark text-4xl mb-2"></i>
                            <p>No attendance records found</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if(isset($attendances) && $attendances->hasPages())
        <div class="px-6 py-4 border-t">
            {{ $attendances->appends(request()->query())->links() }}
        </div>
    @endif
</div>

<!-- Mark Attendance Modal -->
<div id="markAttendanceModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-md max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between p-6 border-b">
            <h3 class="text-xl font-semibold text-gray-800">Mark Attendance</h3>
            <button onclick="closeMarkAttendanceModal()" class="text-gray-500 hover:text-gray-700 p-1 -m-1">
                <i class="fa-solid fa-times text-xl"></i>
            </button>
        </div>
        
        <form action="{{ route('hr.attendance.store') }}" method="POST" class="p-6">
            @csrf
            
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-semibold mb-2">Employee <span class="text-red-500">*</span></label>
                <select name="employee_id" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:outline-none">
                    <option value="">Select Employee</option>
                    @foreach($employees ?? [] as $employee)
                        <option value="{{ $employee->id }}">{{ $employee->first_name }} {{ $employee->last_name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-semibold mb-2">Date <span class="text-red-500">*</span></label>
                <input type="date" name="date" value="{{ date('Y-m-d') }}" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:outline-none">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-semibold mb-2">Check In <span class="text-red-500">*</span></label>
                <input type="time" name="check_in" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:outline-none">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-semibold mb-2">Check Out</label>
                <input type="time" name="check_out" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:outline-none">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-semibold mb-2">Status <span class="text-red-500">*</span></label>
                <select name="status" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:outline-none">
                    <option value="present">Present</option>
                    <option value="absent">Absent</option>
                    <option value="late">Late</option>
                    <option value="half_day">Half Day</option>
                </select>
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-semibold mb-2">Notes</label>
                <textarea name="notes" rows="3" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:outline-none" placeholder="Optional notes..."></textarea>
            </div>

            <div class="flex gap-3 pt-4 border-t">
                <button type="submit" class="flex-1 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition duration-200 font-medium">
                    Save Attendance
                </button>
                <button type="button" onclick="closeMarkAttendanceModal()" class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg transition duration-200 font-medium">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openMarkAttendanceModal() {
    document.getElementById('markAttendanceModal').classList.remove('hidden');
}

function closeMarkAttendanceModal() {
    document.getElementById('markAttendanceModal').classList.add('hidden');
}

// Close modal on outside click
document.getElementById('markAttendanceModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeMarkAttendanceModal();
    }
});
</script>

@endsection
