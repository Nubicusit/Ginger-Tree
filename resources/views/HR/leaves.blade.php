@extends('HR.layout.app')
@section('title', 'Leave Requests')

@section('content')

<!-- Page Header -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Leave Requests</h1>
        <p class="text-gray-500 text-sm">Manage employee leave requests</p>
    </div>
    <button onclick="document.getElementById('addLeaveModal').classList.remove('hidden')"
        class="mt-4 sm:mt-0 inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white px-5 py-2.5 rounded-lg shadow transition">
        <i class="fa-solid fa-plus"></i> Add Leave Request
    </button>
</div>

@if(session('success'))
    <div class="mb-4 bg-green-100 text-green-700 px-4 py-3 rounded-lg flex items-center gap-2">
        <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
    </div>
@endif

<!-- Summary Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-xl shadow p-5 flex items-center gap-4">
        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
            <i class="fa-solid fa-calendar-days text-blue-600 text-lg"></i>
        </div>
        <div>
            <p class="text-sm text-gray-500">Total Requests</p>
            <p class="text-xl font-bold text-gray-800">{{ $leaves->count() }}</p>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow p-5 flex items-center gap-4">
        <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center flex-shrink-0">
            <i class="fa-solid fa-clock text-yellow-500 text-lg"></i>
        </div>
        <div>
            <p class="text-sm text-gray-500">Pending</p>
            <p class="text-xl font-bold text-yellow-500">{{ $leaves->where('status','pending')->count() }}</p>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow p-5 flex items-center gap-4">
        <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
            <i class="fa-solid fa-circle-check text-green-600 text-lg"></i>
        </div>
        <div>
            <p class="text-sm text-gray-500">Approved</p>
            <p class="text-xl font-bold text-green-600">{{ $leaves->where('status','approved')->count() }}</p>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow p-5 flex items-center gap-4">
        <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center flex-shrink-0">
            <i class="fa-solid fa-circle-xmark text-red-500 text-lg"></i>
        </div>
        <div>
            <p class="text-sm text-gray-500">Rejected</p>
            <p class="text-xl font-bold text-red-500">{{ $leaves->where('status','rejected')->count() }}</p>
        </div>
    </div>
</div>

<!-- Filter Tabs -->
<div class="flex gap-2 mb-4 flex-wrap">
    @foreach(['all','pending','approved','rejected','hold'] as $tab)
        <a href="{{ route('hr.leaves', ['status' => $tab]) }}"
           class="px-4 py-2 rounded-full text-sm font-medium border transition
           {{ $status === $tab ? 'bg-gray-800 text-white border-gray-800' : 'bg-white text-gray-600 border-gray-300 hover:bg-gray-50' }}">
            {{ ucfirst($tab) }}
        </a>
    @endforeach
</div>

<!-- Leave Table -->
<div class="bg-white rounded-xl shadow overflow-hidden">
    <div class="px-6 py-4 border-b flex items-center justify-between">
        <h2 class="text-lg font-semibold text-gray-700">Leave Requests</h2>
        <span class="text-sm text-gray-400">{{ $leaves->count() }} records</span>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
                <tr>
                    <th class="px-6 py-4">#</th>
                    <th class="px-6 py-4">Employee</th>
                    <th class="px-6 py-4">Leave Type</th>
                    <th class="px-6 py-4">From</th>
                    <th class="px-6 py-4">To</th>
                    <th class="px-6 py-4">Days</th>
                    <th class="px-6 py-4">Reason</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($leaves as $index => $leave)
                @php
                    $colors = [
                        'pending'  => 'bg-yellow-100 text-yellow-700',
                        'approved' => 'bg-green-100 text-green-700',
                        'rejected' => 'bg-red-100 text-red-600',
                        'hold'     => 'bg-blue-100 text-blue-700',
                    ];
                @endphp
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">{{ $index + 1 }}</td>
                    <td class="px-6 py-4 font-medium text-gray-800">
                        {{ $leave->employee->first_name }} {{ $leave->employee->last_name }}
                        <div class="text-xs text-gray-400">{{ $leave->employee->employee_id }}</div>
                    </td>
                    <td class="px-6 py-4 capitalize">{{ $leave->leave_type }}</td>
                    <td class="px-6 py-4">{{ \Carbon\Carbon::parse($leave->from_date)->format('d M Y') }}</td>
                    <td class="px-6 py-4">{{ \Carbon\Carbon::parse($leave->to_date)->format('d M Y') }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-700">
                            {{ $leave->days }} day{{ $leave->days > 1 ? 's' : '' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-gray-500 max-w-xs truncate">{{ $leave->reason ?? '—' }}</td>
                    <td class="px-6 py-4">
                        <span class="px-3 py-1 text-xs rounded-full font-medium {{ $colors[$leave->status] ?? '' }}">
                            {{ ucfirst($leave->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex gap-1 flex-wrap">
                            @if($leave->status !== 'approved')
                            <form method="POST" action="{{ route('hr.leaves.status', $leave) }}">
                                @csrf @method('PATCH')
                                <input type="hidden" name="status" value="approved">
                                <button class="px-3 py-1 bg-green-500 hover:bg-green-600 text-white text-xs rounded-lg transition">
                                    <i class="fa-solid fa-check"></i> Approve
                                </button>
                            </form>
                            @endif
                            @if($leave->status !== 'hold')
                            <form method="POST" action="{{ route('hr.leaves.status', $leave) }}">
                                @csrf @method('PATCH')
                                <input type="hidden" name="status" value="hold">
                                <button class="px-3 py-1 bg-blue-500 hover:bg-blue-600 text-white text-xs rounded-lg transition">
                                    <i class="fa-solid fa-pause"></i> Hold
                                </button>
                            </form>
                            @endif
                            @if($leave->status !== 'rejected')
                            <form method="POST" action="{{ route('hr.leaves.status', $leave) }}">
                                @csrf @method('PATCH')
                                <input type="hidden" name="status" value="rejected">
                                <button class="px-3 py-1 bg-red-500 hover:bg-red-600 text-white text-xs rounded-lg transition">
                                    <i class="fa-solid fa-xmark"></i> Reject
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center py-12 text-gray-500">
                        <i class="fa-solid fa-calendar-xmark text-3xl mb-2 block text-gray-300"></i>
                        No leave requests found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Add Leave Modal -->
<div id="addLeaveModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-bold text-gray-800">Add Leave Request</h2>
            <button onclick="document.getElementById('addLeaveModal').classList.add('hidden')"
                class="text-gray-400 hover:text-gray-600 text-xl">&times;</button>
        </div>
        <form method="POST" action="{{ route('hr.leaves.store') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Employee</label>
                <select name="employee_id" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500 focus:outline-none">
                    <option value="">Select Employee</option>
                    @foreach($employees as $emp)
                        <option value="{{ $emp->id }}">{{ $emp->first_name }} {{ $emp->last_name }} ({{ $emp->employee_id }})</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Leave Type</label>
                <select name="leave_type" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500 focus:outline-none">
                    <option value="sick">Sick</option>
                    <option value="casual">Casual</option>
                    <option value="annual">Annual</option>
                    <option value="unpaid">Unpaid</option>
                    <option value="other">Other</option>
                </select>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">From Date</label>
                    <input type="date" name="from_date" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500 focus:outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">To Date</label>
                    <input type="date" name="to_date" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500 focus:outline-none">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Reason</label>
                <textarea name="reason" rows="3" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500 focus:outline-none" placeholder="Optional..."></textarea>
            </div>
            <div class="flex justify-end gap-2 pt-2">
                <button type="button" onclick="document.getElementById('addLeaveModal').classList.add('hidden')"
                    class="px-4 py-2 text-sm text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50">Cancel</button>
                <button type="submit" class="px-4 py-2 text-sm bg-green-600 text-white rounded-lg hover:bg-green-700">
                    <i class="fa-solid fa-paper-plane mr-1"></i> Submit
                </button>
            </div>
        </form>
    </div>
</div>

@endsection