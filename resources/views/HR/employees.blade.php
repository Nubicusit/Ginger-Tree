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
        <p class="text-gray-500 text-sm">Manage all company employees</p>
    </div>
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
        <select class="w-full md:w-1/4 border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:outline-none">
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
                    <th class="px-6 py-4">Email</th>
                    <th class="px-6 py-4">Phone</th>
                    <th class="px-6 py-4">Joining Date</th>
                    <th class="px-6 py-4">Salary</th>
                    <th class="px-6 py-4">Salary Type</th>
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
                    <td class="px-6 py-4">{{ $employee->email }}</td>
                    <td class="px-6 py-4">{{ $employee->phone ?? '-' }}</td>
                    <td class="px-6 py-4">{{ $employee->joining_date }}</td>
                    <td class="px-6 py-4">{{ $employee->salary }}</td>
                    <td class="px-6 py-4">
                        @if($employee->salary_type)
                            <span class="px-3 py-1 text-xs rounded-full
                                {{ $employee->salary_type === 'monthly' ? 'bg-blue-100 text-blue-700' : ($employee->salary_type === 'weekly' ? 'bg-purple-100 text-purple-700' : 'bg-orange-100 text-orange-700') }}">
                                {{ ucfirst($employee->salary_type) }}
                            </span>
                        @else
                            -
                        @endif
                    </td>
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
                        <button onclick="openEditModal({{ $employee->id }})" class="text-yellow-600 hover:text-yellow-800"><i class="fa-solid fa-pen"></i></button>
                        <button onclick="confirmDelete({{ $employee->id }})" class="text-red-600 hover:text-red-800"><i class="fa-solid fa-trash"></i></button>
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

<!-- ==================== ADD EMPLOYEE MODAL ==================== -->
<div id="employeeModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between p-6 border-b sticky top-0 bg-white z-10">
            <h2 class="text-xl font-bold text-gray-800">Add New Employee</h2>
            <button onclick="closeEmployeeModal()" class="text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
        </div>

        <form action="{{ route('hr.employees.store') }}" method="POST" class="p-6">
            @csrf

            <!-- Personal Information -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-700 mb-4 border-b pb-2">Personal Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">First Name <span class="text-red-500">*</span></label>
                        <input type="text" name="first_name" value="{{ old('first_name') }}" required
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:outline-none" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Last Name <span class="text-red-500">*</span></label>
                        <input type="text" name="last_name" value="{{ old('last_name') }}" required
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:outline-none" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email <span class="text-red-500">*</span></label>
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
                            @foreach(['A+','A-','B+','B-','O+','O-','AB+','AB-'] as $bg)
                                <option value="{{ $bg }}" {{ old('blood_group') == $bg ? 'selected' : '' }}>{{ $bg }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- Employment Information -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-700 mb-4 border-b pb-2">Employment Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Department <span class="text-red-500">*</span></label>
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
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Joining Date <span class="text-red-500">*</span></label>
                        <input type="date" name="joining_date" value="{{ old('joining_date') }}" required
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:outline-none" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Salary <span class="text-red-500">*</span></label>
                        <input type="number" name="salary" step="0.01" min="0" value="{{ old('salary') }}" required
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:outline-none" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Salary Type <span class="text-red-500">*</span></label>
                        <select name="salary_type" required
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:outline-none">
                            <option value="">Select Salary Type</option>
                            <option value="monthly"  {{ old('salary_type') == 'monthly'  ? 'selected' : '' }}>Monthly</option>
                            <option value="weekly"   {{ old('salary_type') == 'weekly'   ? 'selected' : '' }}>Weekly</option>
                            <option value="hourly"   {{ old('salary_type') == 'hourly'   ? 'selected' : '' }}>Hourly</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status <span class="text-red-500">*</span></label>
                        <select name="status" required
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:outline-none">
                            <option value="active"   {{ old('status') == 'active'   ? 'selected' : '' }}>Active</option>
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

            <div class="flex justify-end gap-3 pt-6 border-t sticky bottom-0 bg-white">
                <button type="button" onclick="closeEmployeeModal()"
                    class="px-5 py-2.5 border border-gray-300 rounded-lg hover:bg-gray-50 transition">Cancel</button>
                <button type="submit"
                    class="px-5 py-2.5 bg-green-600 hover:bg-green-700 text-white rounded-lg transition">
                    <i class="fa-solid fa-plus mr-2"></i>Add Employee
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ==================== EDIT EMPLOYEE MODAL ==================== -->
<div id="editEmployeeModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between p-6 border-b sticky top-0 bg-white z-10">
            <h2 class="text-xl font-bold text-gray-800">Edit Employee</h2>
            <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
        </div>

        <form id="editEmployeeForm" action="" method="POST" class="p-6">
            @csrf
            @method('PUT')

            <!-- Personal Information -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-700 mb-4 border-b pb-2">Personal Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">First Name <span class="text-red-500">*</span></label>
                        <input type="text" name="first_name" id="edit_first_name" required
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:outline-none" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Last Name <span class="text-red-500">*</span></label>
                        <input type="text" name="last_name" id="edit_last_name" required
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:outline-none" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email <span class="text-red-500">*</span></label>
                        <input type="email" name="email" id="edit_email" required
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:outline-none" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
                        <input type="text" name="phone" id="edit_phone"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:outline-none" />
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                        <textarea name="address" id="edit_address" rows="2"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:outline-none"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Emergency Contact</label>
                        <input type="text" name="emergency_contact" id="edit_emergency_contact"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:outline-none" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Blood Group</label>
                        <select name="blood_group" id="edit_blood_group"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:outline-none">
                            <option value="">Select Blood Group</option>
                            @foreach(['A+','A-','B+','B-','O+','O-','AB+','AB-'] as $bg)
                                <option value="{{ $bg }}">{{ $bg }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- Employment Information -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-700 mb-4 border-b pb-2">Employment Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Department <span class="text-red-500">*</span></label>
                        <select name="department_id" id="edit_department_id" required
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:outline-none">
                            <option value="">Select Department</option>
                            @foreach($departments ?? [] as $department)
                                <option value="{{ $department->id }}">{{ $department->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Joining Date <span class="text-red-500">*</span></label>
                        <input type="date" name="joining_date" id="edit_joining_date" required
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:outline-none" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Salary <span class="text-red-500">*</span></label>
                        <input type="number" name="salary" id="edit_salary" step="0.01" min="0" required
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:outline-none" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Salary Type <span class="text-red-500">*</span></label>
                        <select name="salary_type" id="edit_salary_type" required
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:outline-none">
                            <option value="">Select Salary Type</option>
                            <option value="monthly">Monthly</option>
                            <option value="weekly">Weekly</option>
                            <option value="hourly">Hourly</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status <span class="text-red-500">*</span></label>
                        <select name="status" id="edit_status" required
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:outline-none">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
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
                        <input type="text" name="pan_number" id="edit_pan_number" maxlength="10" placeholder="ABCDE1234F"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:outline-none" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Bank Account Number</label>
                        <input type="text" name="bank_account" id="edit_bank_account" maxlength="20"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:outline-none" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">IFSC Code</label>
                        <input type="text" name="ifsc_code" id="edit_ifsc_code" maxlength="11" placeholder="SBIN0001234"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:outline-none" />
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-6 border-t sticky bottom-0 bg-white">
                <button type="button" onclick="closeEditModal()"
                    class="px-5 py-2.5 border border-gray-300 rounded-lg hover:bg-gray-50 transition">Cancel</button>
                <button type="submit"
                    class="px-5 py-2.5 bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg transition">
                    <i class="fa-solid fa-pen mr-2"></i>Update Employee
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ==================== DELETE CONFIRMATION MODAL ==================== -->
<div id="deleteModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full p-6">
        <div class="flex items-center gap-4 mb-4">
            <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center flex-shrink-0">
                <i class="fa-solid fa-trash text-red-600 text-lg"></i>
            </div>
            <div>
                <h3 class="text-lg font-bold text-gray-800">Delete Employee</h3>
                <p class="text-gray-500 text-sm">This action cannot be undone.</p>
            </div>
        </div>
        <p class="text-gray-600 mb-6">Are you sure you want to delete this employee? All associated data will be permanently removed.</p>
        <div class="flex justify-end gap-3">
            <button onclick="closeDeleteModal()"
                class="px-5 py-2.5 border border-gray-300 rounded-lg hover:bg-gray-50 transition">Cancel</button>
            <form id="deleteForm" action="" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit"
                    class="px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white rounded-lg transition">
                    <i class="fa-solid fa-trash mr-2"></i>Delete
                </button>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    const employees = @json($employees ?? []);

    // ── Add Modal ──────────────────────────────────────────────
    function openEmployeeModal() {
        document.getElementById('employeeModal').classList.remove('hidden');
    }
    function closeEmployeeModal() {
        document.getElementById('employeeModal').classList.add('hidden');
    }
    document.getElementById('employeeModal')?.addEventListener('click', function(e) {
        if (e.target === this) closeEmployeeModal();
    });

    // ── Edit Modal ─────────────────────────────────────────────
    function openEditModal(id) {
        const employee = employees.find(e => e.id === id);
        if (!employee) return;

        document.getElementById('editEmployeeForm').action = `/hr/employees/${id}`;

        document.getElementById('edit_first_name').value        = employee.first_name ?? '';
        document.getElementById('edit_last_name').value         = employee.last_name ?? '';
        document.getElementById('edit_email').value             = employee.email ?? '';
        document.getElementById('edit_phone').value             = employee.phone ?? '';
        document.getElementById('edit_address').value           = employee.address ?? '';
        document.getElementById('edit_emergency_contact').value = employee.emergency_contact ?? '';
        document.getElementById('edit_blood_group').value       = employee.blood_group ?? '';
        document.getElementById('edit_department_id').value     = employee.department_id ?? '';

        // ✅ Fix: strip time portion so <input type="date"> renders correctly
        const rawDate = employee.joining_date ?? '';
        document.getElementById('edit_joining_date').value = rawDate ? rawDate.substring(0, 10) : '';

        document.getElementById('edit_salary').value       = employee.salary ?? '';
        document.getElementById('edit_salary_type').value  = employee.salary_type ?? '';
        document.getElementById('edit_status').value       = employee.status ?? 'active';
        document.getElementById('edit_pan_number').value   = employee.pan_number ?? '';
        document.getElementById('edit_bank_account').value = employee.bank_account ?? '';
        document.getElementById('edit_ifsc_code').value    = employee.ifsc_code ?? '';

        document.getElementById('editEmployeeModal').classList.remove('hidden');
    }
    function closeEditModal() {
        document.getElementById('editEmployeeModal').classList.add('hidden');
    }
    document.getElementById('editEmployeeModal')?.addEventListener('click', function(e) {
        if (e.target === this) closeEditModal();
    });

    // ── Delete Modal ───────────────────────────────────────────
    function confirmDelete(id) {
        document.getElementById('deleteForm').action = `/hr/employees/${id}`;
        document.getElementById('deleteModal').classList.remove('hidden');
    }
    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
    }
    document.getElementById('deleteModal')?.addEventListener('click', function(e) {
        if (e.target === this) closeDeleteModal();
    });

    // ── Escape closes all modals ───────────────────────────────
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeEmployeeModal();
            closeEditModal();
            closeDeleteModal();
        }
    });
</script>
@endpush