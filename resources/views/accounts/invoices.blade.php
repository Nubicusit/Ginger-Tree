@extends('accounts.layout.app')
@section('title', 'Quotations')

@section('content')

{{-- Flash Messages --}}
@if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6 flex items-center justify-between">
        <span>{{ session('success') }}</span>
        <button onclick="this.parentElement.remove()" class="text-green-700 hover:text-green-900">
            <i class="fa-solid fa-times"></i>
        </button>
    </div>
@endif

{{-- Page Header --}}
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Quotations</h1>
        <p class="text-gray-500 text-sm">View and manage all quotations</p>
    </div>
</div>

{{-- Summary Cards --}}
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
    <div class="bg-white rounded-xl shadow p-5 flex items-center gap-4">
        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
            <i class="fa-solid fa-file-invoice text-blue-600 text-lg"></i>
        </div>
        <div>
            <p class="text-sm text-gray-500">Total Quotations</p>
            <p class="text-xl font-bold text-blue-600">{{ $invoices->total() }}</p>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow p-5 flex items-center gap-4">
        <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
            <i class="fa-solid fa-circle-check text-green-600 text-lg"></i>
        </div>
        <div>
            <p class="text-sm text-gray-500">Approved</p>
            <p class="text-xl font-bold text-green-600">
                {{ $invoices->getCollection()->where('status', 'approved')->count() }}
            </p>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow p-5 flex items-center gap-4">
        <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center flex-shrink-0">
            <i class="fa-solid fa-indian-rupee-sign text-yellow-600 text-lg"></i>
        </div>
        <div>
            <p class="text-sm text-gray-500">Total Value</p>
            <p class="text-xl font-bold text-yellow-600">
                ₹{{ number_format($invoices->getCollection()->sum('total'), 2) }}
            </p>
        </div>
    </div>
</div>

{{-- Filters --}}
<div class="bg-white rounded-xl shadow p-4 mb-6">
    <form method="GET" action="{{ route('accounts.invoices.index') }}">
        <div class="flex flex-col md:flex-row gap-4">
            <input type="text" name="search" value="{{ request('search') }}"
                placeholder="Search customer name..."
                class="w-full md:w-1/3 border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none" />
            <select name="status" class="w-full md:w-1/5 border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                <option value="">All Status</option>
                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                <option value="pending"  {{ request('status') == 'pending'  ? 'selected' : '' }}>Pending</option>
                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                <option value="draft"    {{ request('status') == 'draft'    ? 'selected' : '' }}>Draft</option>
            </select>
            <input type="date" name="from_date" value="{{ request('from_date') }}"
                class="w-full md:w-1/5 border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none" />
            <input type="date" name="to_date" value="{{ request('to_date') }}"
                class="w-full md:w-1/5 border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none" />
            <div class="flex gap-2">
                <button type="submit"
                    class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg transition">
                    <i class="fa-solid fa-search"></i> Filter
                </button>
                <a href="{{ route('accounts.invoices.index') }}"
                    class="inline-flex items-center gap-2 border border-gray-300 hover:bg-gray-50 text-gray-700 px-5 py-2 rounded-lg transition">
                    Reset
                </a>
            </div>
        </div>
    </form>
</div>

{{-- Table --}}
<div class="bg-white rounded-xl shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
                <tr>
                    <th class="px-6 py-4">#</th>
                    <th class="px-6 py-4">Quotation ID</th>
                    <th class="px-6 py-4">Customer</th>
                    <th class="px-6 py-4">Description</th>
                    <th class="px-6 py-4">Qty</th>
                    <th class="px-6 py-4">Price</th>
                    <th class="px-6 py-4">Total</th>
                    <th class="px-6 py-4">Date</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4 text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($invoices as $invoice)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">{{ $loop->iteration }}</td>
                    <td class="px-6 py-4 font-medium text-gray-800">
                        QT-{{ str_pad($invoice->id, 4, '0', STR_PAD_LEFT) }}
                    </td>
                    <td class="px-6 py-4">{{ $invoice->lead->client_name ?? '-' }}</td>
                    <td class="px-6 py-4 text-gray-500">{{ \Str::limit($invoice->description, 30) }}</td>
                    <td class="px-6 py-4">{{ $invoice->quantity ?? '-' }}</td>
                    <td class="px-6 py-4">₹{{ number_format($invoice->price ?? 0, 2) }}</td>
                    <td class="px-6 py-4 font-semibold text-gray-800">₹{{ number_format($invoice->total ?? 0, 2) }}</td>
                    <td class="px-6 py-4">{{ \Carbon\Carbon::parse($invoice->created_at)->format('d M Y') }}</td>
                    <td class="px-6 py-4">
                        @php
                            $badgeClass = match($invoice->status) {
                                'approved' => 'bg-green-100 text-green-700',
                                'pending'  => 'bg-yellow-100 text-yellow-700',
                                'rejected' => 'bg-red-100 text-red-700',
                                'draft'    => 'bg-gray-100 text-gray-600',
                                default    => 'bg-gray-100 text-gray-600',
                            };
                        @endphp
                        <span class="px-3 py-1 text-xs rounded-full {{ $badgeClass }}">
                            {{ ucfirst($invoice->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <div class="flex justify-center gap-4">
                            <button onclick="openViewModal({{ $invoice->id }})"
                                class="text-blue-600 hover:text-blue-800" title="View">
                                <i class="fa-solid fa-eye"></i>
                            </button>
                            <a href="{{ route('accounts.invoices.print', $invoice->id) }}"
                               target="_blank"
                               class="text-gray-600 hover:text-gray-800" title="Print">
                                <i class="fa-solid fa-print"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" class="text-center py-12 text-gray-500">
                        <i class="fa-solid fa-file-invoice fa-2x mb-2 block"></i>
                        No quotations found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($invoices->hasPages())
    <div class="px-6 py-4 border-t flex flex-col sm:flex-row items-center justify-between gap-3">
        <small class="text-gray-500 text-sm">
            Showing {{ $invoices->firstItem() }} to {{ $invoices->lastItem() }}
            of {{ $invoices->total() }} quotations
        </small>
        {{ $invoices->withQueryString()->links() }}
    </div>
    @endif
</div>

{{-- ========== VIEW MODAL ========== --}}
<div id="viewModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">

        <div class="flex items-center justify-between p-6 border-b sticky top-0 bg-white z-10">
            <h2 class="text-xl font-bold text-gray-800">Quotation Details</h2>
            <button onclick="closeViewModal()" class="text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
        </div>

        <div class="p-6 space-y-4">

            {{-- Quotation ID & Status --}}
            <div class="flex justify-between items-center">
                <span id="modal_qt_id" class="text-lg font-bold text-gray-800"></span>
                <span id="modal_status" class="px-3 py-1 text-xs rounded-full font-medium"></span>
            </div>

            {{-- Customer Info --}}
            <div class="bg-gray-50 rounded-lg p-4">
                <p class="text-xs text-gray-500 uppercase font-semibold mb-2">Customer</p>
                <p id="modal_client" class="font-bold text-gray-800 text-base"></p>
                <p id="modal_phone" class="text-gray-500 text-sm"></p>
                <p id="modal_email" class="text-gray-500 text-sm"></p>
                <p id="modal_location" class="text-gray-500 text-sm"></p>
            </div>

            {{-- Project Info --}}
            <div class="bg-gray-50 rounded-lg p-4">
                <p class="text-xs text-gray-500 uppercase font-semibold mb-2">Project</p>
                <p class="text-sm text-gray-700">Type: <span id="modal_project_type" class="font-medium"></span></p>
                <p class="text-sm text-gray-700">Budget: <span id="modal_budget" class="font-medium"></span></p>
            </div>

            {{-- Item Details --}}
            <div class="bg-gray-50 rounded-lg p-4">
                <p class="text-xs text-gray-500 uppercase font-semibold mb-3">Item</p>
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-gray-500 text-xs uppercase">
                            <th class="text-left pb-2">Description</th>
                            <th class="text-right pb-2">Qty</th>
                            <th class="text-right pb-2">Price</th>
                            <th class="text-right pb-2">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td id="modal_desc" class="text-gray-800 py-1"></td>
                            <td id="modal_qty" class="text-right text-gray-700"></td>
                            <td id="modal_price" class="text-right text-gray-700"></td>
                            <td id="modal_total" class="text-right font-semibold text-gray-800"></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- Grand Total --}}
            <div class="flex justify-end">
                <div class="w-64 space-y-1 text-sm">
                    <div class="flex justify-between text-gray-600">
                        <span>Subtotal</span>
                        <span id="modal_subtotal"></span>
                    </div>
                    <div class="flex justify-between text-gray-600">
                        <span>GST (18%)</span>
                        <span id="modal_gst"></span>
                    </div>
                    <div class="flex justify-between font-bold text-gray-800 text-base border-t pt-2">
                        <span>Grand Total</span>
                        <span id="modal_grand"></span>
                    </div>
                </div>
            </div>

            {{-- Image --}}
            <div id="modal_image_wrap" class="hidden">
                <p class="text-xs text-gray-500 uppercase font-semibold mb-2">Image</p>
                <img id="modal_image" src=""
                     class="w-32 h-24 object-cover rounded border"
                     onerror="this.closest('div').classList.add('hidden')">
            </div>

        </div>

        <div class="p-6 border-t flex justify-end gap-3">
            <button onclick="closeViewModal()"
                class="px-5 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition text-sm">
                Close
            </button>
            <a id="modal_print_btn" href="#" target="_blank"
                class="px-5 py-2 bg-gray-700 hover:bg-gray-800 text-white rounded-lg transition text-sm inline-flex items-center gap-2">
                <i class="fa-solid fa-print"></i> Print
            </a>
        </div>

    </div>
</div>

@endsection

@push('scripts')
<script>
const quotations = @json($invoices->getCollection());
const printBaseUrl = "{{ url('accounts/invoices') }}";

function openViewModal(id) {
    const q = quotations.find(q => q.id === id);
    if (!q) return;

    const lead     = q.lead ?? {};
    const subtotal = parseFloat(q.total ?? 0);
    const gst      = subtotal * 0.18;
    const grand    = subtotal + gst;

    document.getElementById('modal_qt_id').textContent    = 'QT-' + String(q.id).padStart(4, '0');
    document.getElementById('modal_desc').textContent     = q.description ?? '-';
    document.getElementById('modal_qty').textContent      = q.quantity ?? '-';
    document.getElementById('modal_price').textContent    = '₹' + parseFloat(q.price ?? 0).toLocaleString('en-IN', {minimumFractionDigits:2});
    document.getElementById('modal_total').textContent    = '₹' + subtotal.toLocaleString('en-IN', {minimumFractionDigits:2});
    document.getElementById('modal_subtotal').textContent = '₹' + subtotal.toLocaleString('en-IN', {minimumFractionDigits:2});
    document.getElementById('modal_gst').textContent      = '₹' + gst.toLocaleString('en-IN', {minimumFractionDigits:2});
    document.getElementById('modal_grand').textContent    = '₹' + grand.toLocaleString('en-IN', {minimumFractionDigits:2});

    document.getElementById('modal_client').textContent        = lead.client_name ?? '-';
    document.getElementById('modal_phone').textContent         = lead.phone ?? '';
    document.getElementById('modal_email').textContent         = lead.email ?? '';
    document.getElementById('modal_location').textContent      = lead.location ?? '';
    document.getElementById('modal_project_type').textContent  = lead.project_type ?? '-';
    document.getElementById('modal_budget').textContent        = lead.budget_range ?? '-';

    // Status badge
    const statusEl  = document.getElementById('modal_status');
    const statusMap = {
        approved: 'bg-green-100 text-green-700',
        pending:  'bg-yellow-100 text-yellow-700',
        rejected: 'bg-red-100 text-red-700',
        draft:    'bg-gray-100 text-gray-600',
    };
    statusEl.textContent = q.status ? q.status.charAt(0).toUpperCase() + q.status.slice(1) : '-';
    statusEl.className   = 'px-3 py-1 text-xs rounded-full font-medium ' + (statusMap[q.status] ?? 'bg-gray-100 text-gray-600');

    // Image — storage fallback to img/
    const imgEl = document.getElementById('modal_image');
    const wrap  = document.getElementById('modal_image_wrap');

    if (q.image) {
        imgEl.src = '/storage/' + q.image;
        imgEl.onerror = function() {
            this.src = '/img/' + q.image;
            this.onerror = function() {
                wrap.classList.add('hidden');
            };
        };
        wrap.classList.remove('hidden');
    } else {
        wrap.classList.add('hidden');
    }

    // Print link
    document.getElementById('modal_print_btn').href = printBaseUrl + '/' + id + '/print';

    document.getElementById('viewModal').classList.remove('hidden');
}

function closeViewModal() {
    document.getElementById('viewModal').classList.add('hidden');
}

document.getElementById('viewModal').addEventListener('click', function(e) {
    if (e.target === this) closeViewModal();
});

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeViewModal();
});
</script>
@endpush