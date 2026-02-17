@extends('layout.app')

@section('title', 'User Account Management')

@section('page-title', 'User Accounts')

@section('content')
<div class="flex items-center justify-end mb-4 space-x-3">

    <!-- Add Department -->
    <button onclick="openDeptModal()"
        class="inline-flex items-center px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-lg
           hover:bg-gray-700 transition shadow">
        <i class="fas fa-building mr-2"></i>
        + ADD DEPARTMENT
    </button>

    <!-- Add User -->
    <button onclick="openModal()"
        class="inline-flex items-center px-4 py-2 bg-indigo-500 text-white text-sm font-medium rounded-lg
              hover:bg-indigo-700 transition shadow">
        + ADD NEW USER
    </button>
</div>
<!-- Table Section -->
<div class="bg-white rounded-lg shadow-sm overflow-hidden border border-gray-100">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="text-gray-500 text-sm font-semibold bg-gray-50 border-b border-gray-200">
                    <th class="px-6 py-4">Name</th>
                    <th class="px-6 py-4">Departments</th>
                    <th class="px-6 py-4">Email</th>
                    <th class="px-6 py-4">Password</th>
                    <th class="px-6 py-4">Mobile Phone Number</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($users as $user)
                <tr class="text-sm text-gray-700">
                    <td class="px-6 py-4">{{ $user->name }}</td>
                    <td class="px-6 py-4 capitalize">
                        {{ $user->department->name ?? '—' }}
                    </td>
                    <td class="px-6 py-4">{{ $user->email }}</td>
                    <td class="px-6 py-4 font-mono ">
                        {{ $user->visible_password ?? '—' }}
                    </td>
                    <td class="px-6 py-4">
                        {{ $user->contact_no ?? '—' }}
                    </td>
                    <td class="px-6 py-4">
                        <label class="inline-flex items-center cursor-pointer">
                            <input
                                type="checkbox"
                                class="sr-only peer"
                                {{ $user->status ? 'checked' : '' }}
                                onchange="toggleStatus({{ $user->id }}, this.checked)">
                            <div
                                class="w-11 h-6 bg-gray-300 peer-focus:outline-none rounded-full peer
                   peer-checked:bg-green-500 relative
                   after:content-[''] after:absolute after:top-[2px] after:left-[2px]
                   after:bg-white after:rounded-full after:h-5 after:w-5
                   after:transition-all peer-checked:after:translate-x-full">
                            </div>
                        </label>
                    </td>
                    <td class="px-6 py-4">
                        <form action="{{ route('users.destroy', $user->id) }}"
                            method="POST"
                            onsubmit="return confirm('Are you sure you want to delete this user?')">
                            @csrf
                            @method('DELETE')

                            <button type="submit"
                                class="inline-flex items-center px-3 py-1.5
                   bg-red-500 text-white text-xs font-medium
                   rounded-md hover:bg-red-400 transition">
                                DELETE
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-6 text-center text-gray-500">
                        No Users found
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<!-- Add User Modal -->
<div id="standardModal"
    class="fixed inset-0 bg-black/50 hidden z-50 flex items-center justify-center">
    <div class="bg-white w-full max-w-lg rounded-xl shadow-xl overflow-hidden">
        <!-- Header -->
        <div class="flex items-center justify-between px-6 py-4 bg-blue-500 text-white">
            <h2 class="text-lg font-semibold">Add New User</h2>
            <button onclick="closeModal()" class="text-white text-xl leading-none">
                &times;
            </button>
        </div>

        <!-- Body -->
        <form action="{{ route('users.store') }}" method="POST" class="px-6 py-5 space-y-4">
            @csrf

            <!-- Name -->
            <div>
                <label class="block text-gray-700 font-medium mb-1 text-sm">Name <span class="text-red-500">*</span></label>
                <input type="name" name="name" required
                    class="w-full rounded border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-gray-200 focus:ring-1 focus:ring-gray-200" />
            </div>

            <!-- Email -->
            <div>
                <label class="block text-gray-700 font-medium mb-1 text-sm">Email Address <span class="text-red-500">*</span></label>
                <input type="email" name="email" required
                    class="w-full rounded border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-gray-200 focus:ring-1 focus:ring-gray-200" />
            </div>

            <!-- Password -->
            <div>
                <label class="block text-gray-700 font-medium mb-1 text-sm">Password</label>
                <input type="password" name="password" required
                    class="w-full rounded border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-gray-200 focus:ring-1 focus:ring-gray-200" />
            </div>

            <!-- Mobile -->
            <div>
                <div class="flex items-center justify-between mb-1">
                    <label class="text-gray-700 font-medium text-sm">Mobile Number</label>
                </div>
                <input type="text" name="contact_no"
                    class="w-full rounded border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-gray-200 focus:ring-1 focus:ring-gray-200" />
            </div>

            <!-- Role -->
            <div>
                <label class="text-gray-700 font-medium text-sm">
                    Department <span class="text-red-500">*</span>
                </label>
                <select name="department_id" required
                    class="w-full rounded border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:border-gray-200 focus:ring-1 focus:ring-gray-200">

                    <option value="">Select Department</option>
                    @foreach($departments as $department)
                    <option value="{{ $department->id }}">
                        {{ $department->name }}
                    </option>
                    @endforeach
                </select>
            </div>
            
            <!-- Footer -->
            <div class="flex justify-end gap-3 pt-5 border-t">
                <button type="button" onclick="closeModal()"
                    class="px-4 py-2 text-sm rounded-lg border bg-white">
                    Cancel
                </button>
                <button type="submit"
                    class="px-4 py-2 text-sm rounded-lg bg-blue-500 text-white hover:bg-blue-600">
                    Save User
                </button>
            </div>
        </form>
    </div>
</div>
<!-- Add Department Modal -->
<div id="departmentModal"
    class="fixed inset-0 bg-black/50 hidden z-50 flex items-center justify-center">
    <div class="bg-white w-full max-w-sm rounded-xl shadow-xl overflow-hidden">
        <!-- Header -->
        <div class="flex items-center justify-between px-5 py-4 bg-gray-600 text-white">
            <h2 class="text-md font-semibold">Add Department</h2>
            <button onclick="closeDeptModal()" class="text-xl">&times;</button>
        </div>
        <!-- Body -->
        <form action="{{ route('departments.store') }}" method="POST" class="px-5 py-4 space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium mb-1">
                    Department Name <span class="text-red-500">*</span>
                </label>
                <input type="text" name="name" required
                    placeholder="e.g. Accounts"
                    class="w-full rounded px-3 py-2 text-sm focus:ring focus:ring-gray-300">
            </div>
            <div class="flex justify-end gap-3 pt-3">
                <button type="button" onclick="closeDeptModal()"
                    class="px-4 py-2 text-sm border rounded">
                    Cancel
                </button>
                <button type="submit"
                    class="px-4 py-2 text-sm bg-green-600 text-white rounded hover:bg-blue-700">
                    Save
                </button>
            </div>
        </form>
    </div>
</div>
<script>
    function toggleStatus(userId, status) {
        fetch(`/users/${userId}/toggle-status`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    status: status ? 1 : 0
                })
            })
            .then(res => res.json())
            .then(data => {
                if (!data.success) {
                    alert('Something went wrong');
                }
            })
            .catch(() => alert('Server error'));
    }
</script>
<script>
    function openModal() {
        document.getElementById('standardModal').classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('standardModal').classList.add('hidden');
    }

    function openDeptModal() {
        document.getElementById('departmentModal').classList.remove('hidden');
    }

    function closeDeptModal() {
        document.getElementById('departmentModal').classList.add('hidden');
    }
</script>
@endsection
