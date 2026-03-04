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
                {{ $invoices->getCollection()->where('status', 'Approved')->count() }}
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
                @php
                    $totalValue = $invoices->getCollection()->sum(function($q) {
                        $items = is_array($q->items) ? $q->items : json_decode($q->items, true) ?? [];
                        return collect($items)->sum(fn($i) => floatval($i['total'] ?? 0));
                    });
                @endphp
                ₹{{ number_format($totalValue, 2) }}
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
                <option value="Approved"    {{ request('status') == 'Approved'    ? 'selected' : '' }}>Approved</option>
                <option value="Sent"        {{ request('status') == 'Sent'        ? 'selected' : '' }}>Sent</option>
                <option value="Rejected"    {{ request('status') == 'Rejected'    ? 'selected' : '' }}>Rejected</option>
                <option value="Negotiation" {{ request('status') == 'Negotiation' ? 'selected' : '' }}>Negotiation</option>
                <option value="Draft"       {{ request('status') == 'Draft'       ? 'selected' : '' }}>Draft</option>
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
                    <th class="px-6 py-4">Quotation No</th>
                    <th class="px-6 py-4">Customer</th>
                    <th class="px-6 py-4">Items</th>
                    <th class="px-6 py-4">Grand Total</th>
                    <th class="px-6 py-4">Date</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4">Reason</th>
                    <th class="px-6 py-4 text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($invoices as $invoice)
                @php
                    $items     = is_array($invoice->items) ? $invoice->items : json_decode($invoice->items, true) ?? [];
                    $itemCount = count($items);
                    $subtotal  = collect($items)->sum(fn($i) => floatval($i['total'] ?? 0));
                    $gst       = $subtotal * 0.18;
                    $grand     = $subtotal + $gst;
                @endphp
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">{{ $loop->iteration }}</td>
                    <td class="px-6 py-4 font-medium text-gray-800">{{ $invoice->quotation_no }}</td>
                    <td class="px-6 py-4">{{ $invoice->lead->client_name ?? '-' }}</td>
                    <td class="px-6 py-4 text-gray-500">
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-blue-50 text-blue-600 text-xs rounded-full border border-blue-100">
                            {{ $itemCount }} {{ Str::plural('item', $itemCount) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 font-semibold text-gray-800">₹{{ number_format($grand, 2) }}</td>
                    <td class="px-6 py-4">{{ \Carbon\Carbon::parse($invoice->created_at)->format('d M Y') }}</td>

                    {{-- Status Badge --}}
                    <td class="px-6 py-4">
                        @php
                            $badgeClass = match($invoice->status) {
                                'Approved'    => 'bg-green-100 text-green-700',
                                'Sent'        => 'bg-blue-100 text-blue-700',
                                'Rejected'    => 'bg-red-100 text-red-700',
                                'Negotiation' => 'bg-orange-100 text-orange-700',
                                'Draft'       => 'bg-gray-100 text-gray-600',
                                default       => 'bg-gray-100 text-gray-600',
                            };
                        @endphp
                        <span class="px-3 py-1 text-xs rounded-full {{ $badgeClass }}">
                            {{ $invoice->status }}
                        </span>
                    </td>

                    {{-- Reason Column (Rejection or Negotiation only) --}}
                    <td class="px-6 py-4 text-gray-500 max-w-[160px]">
                        @if($invoice->status === 'Rejected' && $invoice->rejection_reason)
                            <span class="text-red-600 text-xs" title="{{ $invoice->rejection_reason }}">
                                {{ \Str::limit($invoice->rejection_reason, 40) }}
                            </span>
                        @elseif($invoice->status === 'Negotiation' && $invoice->negotiation_reason)
                            <span class="text-orange-600 text-xs" title="{{ $invoice->negotiation_reason }}">
                                {{ \Str::limit($invoice->negotiation_reason, 40) }}
                            </span>
                        @else
                            <span class="text-gray-300 text-xs">—</span>
                        @endif
                    </td>

                    {{-- Actions --}}
                    <td class="px-6 py-4">
                        <div class="flex flex-wrap justify-center gap-2 items-center">

                            {{-- View --}}
                            <button onclick="openViewModal({{ $invoice->id }})"
                                class="inline-flex items-center gap-1 px-3 py-1 text-xs rounded-full bg-blue-50 text-blue-600 hover:bg-blue-100 border border-blue-200 transition font-medium">
                                <i class="fa-solid fa-eye"></i> View
                            </button>

                            {{-- Print --}}
                            <a href="{{ route('accounts.invoices.print', $invoice->id) }}" target="_blank"
                                class="inline-flex items-center gap-1 px-3 py-1 text-xs rounded-full bg-gray-100 text-gray-600 hover:bg-gray-200 border border-gray-200 transition font-medium">
                                <i class="fa-solid fa-print"></i> Print
                            </a>

                            {{-- Approve --}}
                            @if($invoice->status !== 'Approved')
                                <button onclick="openApproveModal({{ $invoice->id }}, '{{ $invoice->quotation_no }}')"
                                    class="inline-flex items-center gap-1 px-3 py-1 text-xs rounded-full bg-green-50 text-green-600 hover:bg-green-100 border border-green-200 transition font-medium">
                                    <i class="fa-solid fa-circle-check"></i> Approve
                                </button>
                            @else
                                <span class="inline-flex items-center gap-1 px-3 py-1 text-xs rounded-full bg-green-500 text-white font-medium cursor-default">
                                    <i class="fa-solid fa-circle-check"></i> Approved
                                </span>
                            @endif

                            {{-- Negotiate --}}
                            @if($invoice->status !== 'Negotiation')
                                <button onclick="openNegotiateModal({{ $invoice->id }}, '{{ $invoice->quotation_no }}')"
                                    class="inline-flex items-center gap-1 px-3 py-1 text-xs rounded-full bg-orange-50 text-orange-600 hover:bg-orange-100 border border-orange-200 transition font-medium">
                                    <i class="fa-solid fa-handshake"></i> Negotiate
                                </button>
                            @else
                                <span class="inline-flex items-center gap-1 px-3 py-1 text-xs rounded-full bg-orange-500 text-white font-medium cursor-default">
                                    <i class="fa-solid fa-handshake"></i> Negotiation
                                </span>
                            @endif

                            {{-- Reject --}}
                            @if($invoice->status !== 'Rejected')
                                <button onclick="openRejectModal({{ $invoice->id }}, '{{ $invoice->quotation_no }}')"
                                    class="inline-flex items-center gap-1 px-3 py-1 text-xs rounded-full bg-red-50 text-red-600 hover:bg-red-100 border border-red-200 transition font-medium">
                                    <i class="fa-solid fa-circle-xmark"></i> Reject
                                </button>
                            @else
                                <span class="inline-flex items-center gap-1 px-3 py-1 text-xs rounded-full bg-red-500 text-white font-medium cursor-default">
                                    <i class="fa-solid fa-circle-xmark"></i> Rejected
                                </span>
                            @endif

                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center py-12 text-gray-500">
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

            <div class="flex justify-between items-center">
                <span id="modal_qt_id" class="text-lg font-bold text-gray-800"></span>
                <span id="modal_status" class="px-3 py-1 text-xs rounded-full font-medium"></span>
            </div>

            {{-- Customer --}}
            <div class="bg-gray-50 rounded-lg p-4">
                <p class="text-xs text-gray-500 uppercase font-semibold mb-2">Customer</p>
                <p id="modal_client" class="font-bold text-gray-800 text-base"></p>
                <p id="modal_phone" class="text-gray-500 text-sm"></p>
                <p id="modal_email" class="text-gray-500 text-sm"></p>
                <p id="modal_location" class="text-gray-500 text-sm"></p>
            </div>

            {{-- Project --}}
            <div class="bg-gray-50 rounded-lg p-4">
                <p class="text-xs text-gray-500 uppercase font-semibold mb-2">Project</p>
                <p class="text-sm text-gray-700">Type: <span id="modal_project_type" class="font-medium"></span></p>
                <p class="text-sm text-gray-700">Budget: <span id="modal_budget" class="font-medium"></span></p>
            </div>

            {{-- Items Table --}}
            <div class="bg-gray-50 rounded-lg p-4">
                <p class="text-xs text-gray-500 uppercase font-semibold mb-3">Items</p>
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-gray-500 text-xs uppercase border-b border-gray-200">
                            <th class="text-left pb-2">#</th>
                            <th class="text-left pb-2">Description</th>
                            <th class="text-right pb-2">Qty</th>
                            <th class="text-right pb-2">Price</th>
                            <th class="text-right pb-2">Total</th>
                        </tr>
                    </thead>
                    <tbody id="modal_items_body"></tbody>
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

            {{-- Rejection Reason --}}
            <div id="modal_rejection_wrap" class="hidden bg-red-50 border border-red-200 rounded-lg p-4">
                <p class="text-xs text-red-500 uppercase font-semibold mb-1">Rejection Reason</p>
                <p id="modal_rejection_reason" class="text-sm text-red-700"></p>
            </div>

            {{-- Negotiation Reason --}}
            <div id="modal_negotiation_wrap" class="hidden bg-orange-50 border border-orange-200 rounded-lg p-4">
                <p class="text-xs text-orange-500 uppercase font-semibold mb-1">Negotiation Notes</p>
                <p id="modal_negotiation_reason" class="text-sm text-orange-700"></p>
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

{{-- ========== APPROVE MODAL ========== --}}
<div id="approveModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-md">

        <div class="flex items-center justify-between p-6 border-b">
            <div>
                <h2 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                    <i class="fa-solid fa-circle-check text-green-600"></i> Approve Quotation
                </h2>
                <p id="approve_modal_subtitle" class="text-sm text-gray-500 mt-0.5"></p>
            </div>
            <button onclick="closeApproveModal()" class="text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
        </div>

        <form id="approveForm" method="POST">
            @csrf
            @method('PATCH')
            <div class="p-6 border-t flex justify-end gap-3">
                <button type="button" onclick="closeApproveModal()"
                    class="px-5 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition text-sm">
                    Cancel
                </button>
                <button type="submit"
                    class="px-5 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition text-sm inline-flex items-center gap-2">
                    <i class="fa-solid fa-circle-check"></i> Confirm Approve
                </button>
            </div>
        </form>

    </div>
</div>

{{-- ========== NEGOTIATE MODAL ========== --}}
<div id="negotiateModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-md">

        <div class="flex items-center justify-between p-6 border-b">
            <div>
                <h2 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                    <i class="fa-solid fa-handshake text-orange-500"></i> Send for Negotiation
                </h2>
                <p id="negotiate_modal_subtitle" class="text-sm text-gray-500 mt-0.5"></p>
            </div>
            <button onclick="closeNegotiateModal()" class="text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
        </div>

        <form id="negotiateForm" method="POST">
            @csrf
            @method('PATCH')
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Negotiation Notes <span class="text-red-500">*</span>
                    </label>
                    <textarea name="negotiation_reason" id="negotiation_reason_input" rows="4"
                        placeholder="Describe what needs to be negotiated (e.g. pricing, scope, timeline)..."
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-orange-400 focus:outline-none resize-none"
                        required></textarea>
                </div>
            </div>
            <div class="p-6 border-t flex justify-end gap-3">
                <button type="button" onclick="closeNegotiateModal()"
                    class="px-5 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition text-sm">
                    Cancel
                </button>
                <button type="submit"
                    class="px-5 py-2 bg-orange-500 hover:bg-orange-600 text-white rounded-lg transition text-sm inline-flex items-center gap-2">
                    <i class="fa-solid fa-handshake"></i> Confirm Negotiation
                </button>
            </div>
        </form>

    </div>
</div>

{{-- ========== REJECT MODAL ========== --}}
<div id="rejectModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-md">

        <div class="flex items-center justify-between p-6 border-b">
            <div>
                <h2 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                    <i class="fa-solid fa-circle-xmark text-red-600"></i> Reject Quotation
                </h2>
                <p id="reject_modal_subtitle" class="text-sm text-gray-500 mt-0.5"></p>
            </div>
            <button onclick="closeRejectModal()" class="text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
        </div>

        <form id="rejectForm" method="POST">
            @csrf
            @method('PATCH')
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Reason for Rejection <span class="text-red-500">*</span>
                    </label>
                    <textarea name="rejection_reason" id="rejection_reason_input" rows="4"
                        placeholder="Enter a reason for rejecting this quotation..."
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-red-400 focus:outline-none resize-none"
                        required></textarea>
                </div>
            </div>
            <div class="p-6 border-t flex justify-end gap-3">
                <button type="button" onclick="closeRejectModal()"
                    class="px-5 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition text-sm">
                    Cancel
                </button>
                <button type="submit"
                    class="px-5 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition text-sm inline-flex items-center gap-2">
                    <i class="fa-solid fa-circle-xmark"></i> Confirm Reject
                </button>
            </div>
        </form>

    </div>
</div>

@endsection

@push('scripts')
<script>
const quotations  = @json($invoices->getCollection());
const printBaseUrl = "{{ url('accounts/invoices') }}";
const baseUrl      = "{{ url('accounts/invoices') }}";

function fmt(val) {
    return '₹' + parseFloat(val ?? 0).toLocaleString('en-IN', { minimumFractionDigits: 2 });
}

// ── VIEW MODAL ──────────────────────────────────────────────
function openViewModal(id) {
    const q = quotations.find(q => q.id === id);
    if (!q) return;

    const lead  = q.lead ?? {};
    const items = Array.isArray(q.items)
        ? q.items
        : (typeof q.items === 'string' ? JSON.parse(q.items) : []);

    const subtotal = items.reduce((s, i) => s + parseFloat(i.total ?? 0), 0);
    const gst      = subtotal * 0.18;
    const grand    = subtotal + gst;

    document.getElementById('modal_qt_id').textContent        = q.quotation_no ?? ('QT-' + String(q.id).padStart(4, '0'));
    document.getElementById('modal_client').textContent       = lead.client_name ?? '-';
    document.getElementById('modal_phone').textContent        = lead.phone ?? '';
    document.getElementById('modal_email').textContent        = lead.email ?? '';
    document.getElementById('modal_location').textContent     = lead.location ?? '';
    document.getElementById('modal_project_type').textContent = lead.project_type ?? '-';
    document.getElementById('modal_budget').textContent       = lead.budget_range ?? '-';

    const tbody = document.getElementById('modal_items_body');
    tbody.innerHTML = '';
    items.forEach((item, idx) => {
        const tr = document.createElement('tr');
        tr.className = 'border-b border-gray-100';
        tr.innerHTML = `
            <td class="py-2 text-gray-400 text-xs">${idx + 1}</td>
            <td class="py-2 text-gray-800">${item.description ?? '-'}</td>
            <td class="py-2 text-right text-gray-700">${item.quantity ?? '-'}</td>
            <td class="py-2 text-right text-gray-700">${fmt(item.price)}</td>
            <td class="py-2 text-right font-semibold text-gray-800">${fmt(item.total)}</td>
        `;
        tbody.appendChild(tr);
    });

    document.getElementById('modal_subtotal').textContent = fmt(subtotal);
    document.getElementById('modal_gst').textContent      = fmt(gst);
    document.getElementById('modal_grand').textContent    = fmt(grand);

    const statusEl  = document.getElementById('modal_status');
    const statusMap = {
        Approved:    'bg-green-100 text-green-700',
        Sent:        'bg-blue-100 text-blue-700',
        Rejected:    'bg-red-100 text-red-700',
        Negotiation: 'bg-orange-100 text-orange-700',
        Draft:       'bg-gray-100 text-gray-600',
    };
    statusEl.textContent = q.status ?? '-';
    statusEl.className   = 'px-3 py-1 text-xs rounded-full font-medium ' + (statusMap[q.status] ?? 'bg-gray-100 text-gray-600');

    // Rejection reason
    const rejWrap = document.getElementById('modal_rejection_wrap');
    const rejText = document.getElementById('modal_rejection_reason');
    if (q.status === 'Rejected' && q.rejection_reason) {
        rejText.textContent = q.rejection_reason;
        rejWrap.classList.remove('hidden');
    } else { rejWrap.classList.add('hidden'); }

    // Negotiation reason
    const negWrap = document.getElementById('modal_negotiation_wrap');
    const negText = document.getElementById('modal_negotiation_reason');
    if (q.status === 'Negotiation' && q.negotiation_reason) {
        negText.textContent = q.negotiation_reason;
        negWrap.classList.remove('hidden');
    } else { negWrap.classList.add('hidden'); }

    document.getElementById('modal_print_btn').href = printBaseUrl + '/' + id + '/print';
    document.getElementById('viewModal').classList.remove('hidden');
}
function closeViewModal() {
    document.getElementById('viewModal').classList.add('hidden');
}

// ── APPROVE MODAL ───────────────────────────────────────────
function openApproveModal(id, qtLabel) {
    document.getElementById('approveForm').action = baseUrl + '/' + id + '/approve';
    document.getElementById('approve_modal_subtitle').textContent = 'Quotation: ' + qtLabel;
    document.getElementById('approveModal').classList.remove('hidden');
}
function closeApproveModal() {
    document.getElementById('approveModal').classList.add('hidden');
}

// ── NEGOTIATE MODAL ─────────────────────────────────────────
function openNegotiateModal(id, qtLabel) {
    document.getElementById('negotiateForm').action = baseUrl + '/' + id + '/negotiate';
    document.getElementById('negotiate_modal_subtitle').textContent = 'Quotation: ' + qtLabel;
    document.getElementById('negotiation_reason_input').value = '';
    document.getElementById('negotiateModal').classList.remove('hidden');
}
function closeNegotiateModal() {
    document.getElementById('negotiateModal').classList.add('hidden');
}

// ── REJECT MODAL ────────────────────────────────────────────
function openRejectModal(id, qtLabel) {
    document.getElementById('rejectForm').action = baseUrl + '/' + id + '/reject';
    document.getElementById('reject_modal_subtitle').textContent = 'Quotation: ' + qtLabel;
    document.getElementById('rejection_reason_input').value = '';
    document.getElementById('rejectModal').classList.remove('hidden');
}
function closeRejectModal() {
    document.getElementById('rejectModal').classList.add('hidden');
}

// ── CLOSE ON BACKDROP CLICK / ESC ───────────────────────────
['viewModal','approveModal','negotiateModal','rejectModal'].forEach(id => {
    document.getElementById(id).addEventListener('click', function(e) {
        if (e.target === this) this.classList.add('hidden');
    });
});
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeViewModal();
        closeApproveModal();
        closeNegotiateModal();
        closeRejectModal();
    }
});
</script>
@endpush