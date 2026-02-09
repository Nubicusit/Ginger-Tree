@extends('HR.layout.app')

@section('title', 'Employees')

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
        <h1 class="text-2xl font-bold text-gray-800">Employees</h1>
        <p class="text-gray-500 text-sm">
            Manage all company employees
        </p>
    </div>

    <!-- Modal Trigger Button -->
    <button
        onclick="openEmployeeModal()"
        class="mt-4 sm:mt-0 inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white px-5 py-2.5 rounded-lg shadow transition">
        <i class="fa-solid fa-plus"></i>
        Add Employee
    </button>
</div>

<!-- Filters -->
<div class="bg-white rounded-xl shadow p-4 mb-6">
    <div class="flex flex-col md:flex-row gap-4">
        <input
            type="text"
            placeholder="Search employees"
            class="w-full md:w-1/3 border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:outline-none"
        />

        <select
            class="w-full md:w-1/4 border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:outline-none">
            <option value="">All Departments</option>
            @foreach($departments ?? [] as $department)
                <option value="{{ $department->id }}">{{ $department->name }}</option>
            @endforeach
        </select>
    </div>
</div>

<!-- Employees Table -->
<div class="bg-white rounded-xl shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
           <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
<tr>
    <th class="px-6 py-4">Employee ID</th>
    <th class="px-6 py-4">Name</th>
    <th class="px-6 py-4">Department</th>
    <th class="px-6 py-4">Designation</th>
    <th class="px-6 py-4">Email</th>
    <th class="px-6 py-4">Phone</th>
    <th class="px-6 py-4">Joining Date</th>
    <th class="px-6 py-4">Salary</th>
    <th class="px-6 py-4">Status</th>
    <th class="px-6 py-4">Address</th>
    <th class="px-6 py-4">Emergency Contact</th>
    <th class="px-6 py-4">Blood Group</th>
    <th class="px-6 py-4">PAN</th>
    <th class="px-6 py-4">Bank Account</th>
    <th class="px-6 py-4">IFSC</th>
    <th class="px-6 py-4 text-right">Action</th>
</tr>
</thead>


          <tbody class="divide-y">
@forelse($employees ?? [] as $employee)
<tr class="hover:bg-gray-50">
    <td class="px-6 py-4">{{ $employee->employee_id }}</td>
    <td class="px-6 py-4">{{ $employee->first_name }} {{ $employee->last_name }}</td>
    <td class="px-6 py-4">{{ $employee->department->name ?? '-' }}</td>
    <td class="px-6 py-4">{{ $employee->designation->name ?? '-' }}</td>
    <td class="px-6 py-4">{{ $employee->email }}</td>
    <td class="px-6 py-4">{{ $employee->phone ?? '-' }}</td>
    <td class="px-6 py-4">{{ $employee->joining_date }}</td>
    <td class="px-6 py-4">{{ $employee->salary }}</td>
    <td class="px-6 py-4">
        <span class="px-3 py-1 text-xs rounded-full
            {{ $employee->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
            {{ ucfirst($employee->status) }}
        </span>
    </td>
    <td class="px-6 py-4">{{ $employee->address ?? '-' }}</td>
    <td class="px-6 py-4">{{ $employee->emergency_contact ?? '-' }}</td>
    <td class="px-6 py-4">{{ $employee->blood_group ?? '-' }}</td>
    <td class="px-6 py-4">{{ $employee->pan_number ?? '-' }}</td>
    <td class="px-6 py-4">{{ $employee->bank_account ?? '-' }}</td>
    <td class="px-6 py-4">{{ $employee->ifsc_code ?? '-' }}</td>
    <td class="px-6 py-4 text-right space-x-3">
        <button class="text-blue-600 hover:text-blue-800"><i class="fa-solid fa-eye"></i></button>
        <button class="text-yellow-600 hover:text-yellow-800"><i class="fa-solid fa-pen"></i></button>
        <button class="text-red-600 hover:text-red-800"><i class="fa-solid fa-trash"></i></button>
    </td>
</tr>
@empty
<tr>
    <td colspan="16" class="text-center py-12 text-gray-500">No employees found</td>
</tr>
@endforelse
</tbody>

        </table>
    </div>
</div>

<!-- Employee Modal -->
<div id="employeeModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
        <!-- Modal Header -->
        <div class="flex items-center justify-between p-6 border-b sticky top-0 bg-white z-10">
            <h2 class="text-xl font-bold text-gray-800">Add New Employee</h2>
            <button onclick="closeEmployeeModal()" class="text-gray-400 hover:text-gray-600 text-2xl">
                &times;
            </button>
        </div>

        <!-- Modal Body -->
        <form action="{{ route('hr.employees.store') }}" method="POST" class="p-6">
            @csrf
            
            <!-- Personal Information -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-700 mb-4 border-b pb-2">Personal Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            First Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="first_name" value="{{ old('first_name') }}" required
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:outline-none" />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Last Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="last_name" value="{{ old('last_name') }}" required
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:outline-none" />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Email <span class="text-red-500">*</span>
                        </label>
                        <input type="email" name="email" value="{{ old('email') }}" required
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:outline-none" />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
                        <input type="text" name="phone" value="{{ old('phone') }}"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:outline-none" />
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                        <textarea name="address" rows="2"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:outline-none">{{ old('address') }}</textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Emergency Contact</label>
                        <input type="text" name="emergency_contact" value="{{ old('emergency_contact') }}"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:outline-none" />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Blood Group</label>
                        <select name="blood_group"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:outline-none">
                            <option value="">Select Blood Group</option>
                            <option value="A+" {{ old('blood_group') == 'A+' ? 'selected' : '' }}>A+</option>
                            <option value="A-" {{ old('blood_group') == 'A-' ? 'selected' : '' }}>A-</option>
                            <option value="B+" {{ old('blood_group') == 'B+' ? 'selected' : '' }}>B+</option>
                            <option value="B-" {{ old('blood_group') == 'B-' ? 'selected' : '' }}>B-</option>
                            <option value="O+" {{ old('blood_group') == 'O+' ? 'selected' : '' }}>O+</option>
                            <option value="O-" {{ old('blood_group') == 'O-' ? 'selected' : '' }}>O-</option>
                            <option value="AB+" {{ old('blood_group') == 'AB+' ? 'selected' : '' }}>AB+</option>
                            <option value="AB-" {{ old('blood_group') == 'AB-' ? 'selected' : '' }}>AB-</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Employment Information -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-700 mb-4 border-b pb-2">Employment Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Department <span class="text-red-500">*</span>
                        </label>
                        <select name="department_id" required
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:outline-none">
                            <option value="">Select Department</option>
                            @foreach($departments ?? [] as $department)
                                <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>
                                    {{ $department->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Designation <span class="text-red-500">*</span>
                        </label>
                        <select name="designation_id" required
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:outline-none">
                            <option value="">Select Designation</option>
                            @foreach($designations ?? [] as $designation)
                                <option value="{{ $designation->id }}" {{ old('designation_id') == $designation->id ? 'selected' : '' }}>
                                    {{ $designation->name }}
                                </option>
                            @endforeach
                        </select>
                    </div> -->

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Joining Date <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="joining_date" value="{{ old('joining_date') }}" required
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:outline-none" />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Salary <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="salary" step="0.01" min="0" value="{{ old('salary') }}" required
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:outline-none" />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Status <span class="text-red-500">*</span>
                        </label>
                        <select name="status" required
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:outline-none">
                            <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Financial Information -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-700 mb-4 border-b pb-2">Financial Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">PAN Number</label>
                        <input type="text" name="pan_number" maxlength="10" placeholder="ABCDE1234F" value="{{ old('pan_number') }}"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:outline-none" />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Bank Account Number</label>
                        <input type="text" name="bank_account" maxlength="20" value="{{ old('bank_account') }}"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:outline-none" />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">IFSC Code</label>
                        <input type="text" name="ifsc_code" maxlength="11" placeholder="SBIN0001234" value="{{ old('ifsc_code') }}"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:outline-none" />
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="flex justify-end gap-3 pt-6 border-t sticky bottom-0 bg-white">
                <button type="button" onclick="closeEmployeeModal()"
                    class="px-5 py-2.5 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                    Cancel
                </button>
                <button type="submit"
                    class="px-5 py-2.5 bg-green-600 hover:bg-green-700 text-white rounded-lg transition">
                    <i class="fa-solid fa-plus mr-2"></i>
                    Add Employee
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function openEmployeeModal() {
        document.getElementById('employeeModal').classList.remove('hidden');
    }

    function closeEmployeeModal() {
        document.getElementById('employeeModal').classList.add('hidden');
    }

    // Close modal when clicking outside
    document.getElementById('employeeModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            closeEmployeeModal();
        }
    });

    // Close modal with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeEmployeeModal();
        }
    });
</script>
@endpush