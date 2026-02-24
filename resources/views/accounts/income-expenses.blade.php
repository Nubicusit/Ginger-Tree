@extends('accounts.layout.app')
@section('title', 'Income & Expenses')

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
        <h1 class="text-2xl font-bold text-gray-800">Income & Expenses</h1>
        <p class="text-gray-500 text-sm">Track all company income and expenditure</p>
    </div>
    <button
        onclick="openTransactionModal()"
        class="mt-4 sm:mt-0 inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white px-5 py-2.5 rounded-lg shadow transition">
        <i class="fa-solid fa-plus"></i>
        Add Transaction
    </button>
</div>

<!-- Summary Cards -->
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
    <div class="bg-white rounded-xl shadow p-5 flex items-center gap-4">
        <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
            <i class="fa-solid fa-arrow-trend-up text-green-600 text-lg"></i>
        </div>
        <div>
            <p class="text-sm text-gray-500">Total Income</p>
            <p class="text-xl font-bold text-green-600">₹{{ number_format($totalIncome ?? 0, 2) }}</p>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow p-5 flex items-center gap-4">
        <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center flex-shrink-0">
            <i class="fa-solid fa-arrow-trend-down text-red-500 text-lg"></i>
        </div>
        <div>
            <p class="text-sm text-gray-500">Total Expenses</p>
            <p class="text-xl font-bold text-red-500">₹{{ number_format($totalExpenses ?? 0, 2) }}</p>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow p-5 flex items-center gap-4">
        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
            <i class="fa-solid fa-scale-balanced text-blue-600 text-lg"></i>
        </div>
        <div>
            <p class="text-sm text-gray-500">Net Balance</p>
            <p class="text-xl font-bold text-blue-600">₹{{ number_format(($totalIncome ?? 0) - ($totalExpenses ?? 0), 2) }}</p>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="bg-white rounded-xl shadow p-4 mb-6">
    <div class="flex flex-col md:flex-row gap-4">
        <input
            type="text"
            placeholder="Search transactions..."
            class="w-full md:w-1/3 border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:outline-none"
        />
        <select class="w-full md:w-1/4 border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:outline-none">
            <option value="">All Types</option>
            <option value="income">Income</option>
            <option value="expense">Expense</option>
        </select>
        <select class="w-full md:w-1/4 border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:outline-none">
            <option value="">All Categories</option>
            <option value="salary">Salary</option>
            <option value="sales">Sales</option>
            <option value="rent">Rent</option>
            <option value="utilities">Utilities</option>
            <option value="other">Other</option>
        </select>
        <input
            type="month"
            class="w-full md:w-1/4 border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:outline-none"
        />
    </div>
</div>

<!-- Transactions Table -->
<div class="bg-white rounded-xl shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
                <tr>
                    <th class="px-6 py-4">#</th>
                    <th class="px-6 py-4">Date</th>
                    <th class="px-6 py-4">Title</th>
                    <th class="px-6 py-4">Category</th>
                    <th class="px-6 py-4">Type</th>
                    <th class="px-6 py-4">Amount</th>
                    <th class="px-6 py-4">Payment Method</th>
                    <th class="px-6 py-4">Reference</th>
                    <th class="px-6 py-4">Note</th>
                    <th class="px-6 py-4 text-right">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($transactions ?? [] as $transaction)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">{{ $loop->iteration }}</td>
                    <td class="px-6 py-4">{{ \Carbon\Carbon::parse($transaction->date)->format('d M Y') }}</td>
                    <td class="px-6 py-4 font-medium text-gray-800">{{ $transaction->title }}</td>
                    <td class="px-6 py-4">{{ ucfirst($transaction->category ?? '-') }}</td>
                    <td class="px-6 py-4">
                        <span class="px-3 py-1 text-xs rounded-full
                            {{ $transaction->type === 'income' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            {{ ucfirst($transaction->type) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 font-semibold
                        {{ $transaction->type === 'income' ? 'text-green-600' : 'text-red-500' }}">
                        {{ $transaction->type === 'income' ? '+' : '-' }}₹{{ number_format($transaction->amount, 2) }}
                    </td>
                    <td class="px-6 py-4">{{ ucfirst($transaction->payment_method ?? '-') }}</td>
                    <td class="px-6 py-4">{{ $transaction->reference ?? '-' }}</td>
                    <td class="px-6 py-4 text-gray-500">{{ $transaction->note ?? '-' }}</td>
                    <td class="px-6 py-4 text-right space-x-3">
                        <button onclick="openEditModal({{ $transaction->id }})" class="text-yellow-600 hover:text-yellow-800">
                            <i class="fa-solid fa-pen"></i>
                        </button>
                        <button onclick="confirmDelete({{ $transaction->id }})" class="text-red-600 hover:text-red-800">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" class="text-center py-12 text-gray-500">No transactions found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- ==================== ADD TRANSACTION MODAL ==================== -->
<div id="transactionModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between p-6 border-b sticky top-0 bg-white z-10">
            <h2 class="text-xl font-bold text-gray-800">Add New Transaction</h2>
            <button onclick="closeTransactionModal()" class="text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
        </div>

        <form action="{{ route('accounts.transactions.store') }}" method="POST" class="p-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Title <span class="text-red-500">*</span></label>
                    <input type="text" name="title" value="{{ old('title') }}" required
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:outline-none" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Type <span class="text-red-500">*</span></label>
                    <select name="type" required
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:outline-none">
                        <option value="">Select Type</option>
                        <option value="income"  {{ old('type') == 'income'  ? 'selected' : '' }}>Income</option>
                        <option value="expense" {{ old('type') == 'expense' ? 'selected' : '' }}>Expense</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Category <span class="text-red-500">*</span></label>
                    <select name="category" required
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:outline-none">
                        <option value="">Select Category</option>
                        <option value="salary"    {{ old('category') == 'salary'    ? 'selected' : '' }}>Salary</option>
                        <option value="sales"     {{ old('category') == 'sales'     ? 'selected' : '' }}>Sales</option>
                        <option value="rent"      {{ old('category') == 'rent'      ? 'selected' : '' }}>Rent</option>
                        <option value="utilities" {{ old('category') == 'utilities' ? 'selected' : '' }}>Utilities</option>
                        <option value="other"     {{ old('category') == 'other'     ? 'selected' : '' }}>Other</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Amount <span class="text-red-500">*</span></label>
                    <input type="number" name="amount" step="0.01" min="0" value="{{ old('amount') }}" required
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:outline-none" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Date <span class="text-red-500">*</span></label>
                    <input type="date" name="date" value="{{ old('date', date('Y-m-d')) }}" required
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:outline-none" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Payment Method</label>
                    <select name="payment_method"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:outline-none">
                        <option value="">Select Method</option>
                        <option value="cash"   {{ old('payment_method') == 'cash'   ? 'selected' : '' }}>Cash</option>
                        <option value="bank"   {{ old('payment_method') == 'bank'   ? 'selected' : '' }}>Bank Transfer</option>
                        <option value="upi"    {{ old('payment_method') == 'upi'    ? 'selected' : '' }}>UPI</option>
                        <option value="cheque" {{ old('payment_method') == 'cheque' ? 'selected' : '' }}>Cheque</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Reference No.</label>
                    <input type="text" name="reference" value="{{ old('reference') }}"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:outline-none" />
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Note</label>
                    <textarea name="note" rows="2"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:outline-none">{{ old('note') }}</textarea>
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-6 border-t sticky bottom-0 bg-white">
                <button type="button" onclick="closeTransactionModal()"
                    class="px-5 py-2.5 border border-gray-300 rounded-lg hover:bg-gray-50 transition">Cancel</button>
                <button type="submit"
                    class="px-5 py-2.5 bg-green-600 hover:bg-green-700 text-white rounded-lg transition">
                    <i class="fa-solid fa-plus mr-2"></i>Add Transaction
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ==================== EDIT TRANSACTION MODAL ==================== -->
<div id="editTransactionModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between p-6 border-b sticky top-0 bg-white z-10">
            <h2 class="text-xl font-bold text-gray-800">Edit Transaction</h2>
            <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
        </div>

        <form id="editTransactionForm" action="" method="POST" class="p-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Title <span class="text-red-500">*</span></label>
                    <input type="text" name="title" id="edit_title" required
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:outline-none" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Type <span class="text-red-500">*</span></label>
                    <select name="type" id="edit_type" required
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:outline-none">
                        <option value="">Select Type</option>
                        <option value="income">Income</option>
                        <option value="expense">Expense</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Category <span class="text-red-500">*</span></label>
                    <select name="category" id="edit_category" required
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:outline-none">
                        <option value="">Select Category</option>
                        <option value="salary">Salary</option>
                        <option value="sales">Sales</option>
                        <option value="rent">Rent</option>
                        <option value="utilities">Utilities</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Amount <span class="text-red-500">*</span></label>
                    <input type="number" name="amount" id="edit_amount" step="0.01" min="0" required
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:outline-none" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Date <span class="text-red-500">*</span></label>
                    <input type="date" name="date" id="edit_date" required
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:outline-none" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Payment Method</label>
                    <select name="payment_method" id="edit_payment_method"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:outline-none">
                        <option value="">Select Method</option>
                        <option value="cash">Cash</option>
                        <option value="bank">Bank Transfer</option>
                        <option value="upi">UPI</option>
                        <option value="cheque">Cheque</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Reference No.</label>
                    <input type="text" name="reference" id="edit_reference"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:outline-none" />
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Note</label>
                    <textarea name="note" id="edit_note" rows="2"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:outline-none"></textarea>
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-6 border-t sticky bottom-0 bg-white">
                <button type="button" onclick="closeEditModal()"
                    class="px-5 py-2.5 border border-gray-300 rounded-lg hover:bg-gray-50 transition">Cancel</button>
                <button type="submit"
                    class="px-5 py-2.5 bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg transition">
                    <i class="fa-solid fa-pen mr-2"></i>Update Transaction
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
                <h3 class="text-lg font-bold text-gray-800">Delete Transaction</h3>
                <p class="text-gray-500 text-sm">This action cannot be undone.</p>
            </div>
        </div>
        <p class="text-gray-600 mb-6">Are you sure you want to delete this transaction? All associated data will be permanently removed.</p>
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
    const transactions = @json($transactions ?? []);

    // ── Add Modal ──────────────────────────────────────────────
    function openTransactionModal() {
        document.getElementById('transactionModal').classList.remove('hidden');
    }
    function closeTransactionModal() {
        document.getElementById('transactionModal').classList.add('hidden');
    }
    document.getElementById('transactionModal')?.addEventListener('click', function(e) {
        if (e.target === this) closeTransactionModal();
    });

    // ── Edit Modal ─────────────────────────────────────────────
    function openEditModal(id) {
        const transaction = transactions.find(t => t.id === id);
        if (!transaction) return;

        document.getElementById('editTransactionForm').action = `/accounts/transactions/${id}`;

        document.getElementById('edit_title').value          = transaction.title ?? '';
        document.getElementById('edit_type').value           = transaction.type ?? '';
        document.getElementById('edit_category').value       = transaction.category ?? '';
        document.getElementById('edit_amount').value         = transaction.amount ?? '';
        document.getElementById('edit_date').value           = (transaction.date ?? '').substring(0, 10);
        document.getElementById('edit_payment_method').value = transaction.payment_method ?? '';
        document.getElementById('edit_reference').value      = transaction.reference ?? '';
        document.getElementById('edit_note').value           = transaction.note ?? '';

        document.getElementById('editTransactionModal').classList.remove('hidden');
    }
    function closeEditModal() {
        document.getElementById('editTransactionModal').classList.add('hidden');
    }
    document.getElementById('editTransactionModal')?.addEventListener('click', function(e) {
        if (e.target === this) closeEditModal();
    });

    // ── Delete Modal ───────────────────────────────────────────
    function confirmDelete(id) {
        document.getElementById('deleteForm').action = `/accounts/transactions/${id}`;
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
            closeTransactionModal();
            closeEditModal();
            closeDeleteModal();
        }
    });
</script>
@endpush