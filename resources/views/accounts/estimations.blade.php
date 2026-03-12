{{-- resources/views/accounts/estimations.blade.php --}}
@extends('accounts.layout.app')

@section('title', 'Estimations')

@section('content')

<style>
.est-page { padding: 24px; }
.est-title { font-size: 1.35rem; font-weight: 700; color: #1e293b; margin-bottom: 20px; }

.est-stats {
    display: grid; grid-template-columns: repeat(4,1fr);
    gap: 16px; margin-bottom: 24px;
}
.est-stat {
    background: #fff; border: 1px solid #e2e8f0; border-radius: 10px;
    padding: 16px 20px; box-shadow: 0 1px 4px rgba(0,0,0,.06);
}
.est-stat small {
    display: block; font-size: .73rem; color: #64748b;
    text-transform: uppercase; letter-spacing: .05em; margin-bottom: 6px;
}
.est-stat h4 { margin: 0; font-size: 1.4rem; font-weight: 700; color: #1e293b; }
.est-stat h4.green { color: #16a34a; }
.est-stat h4.blue  { color: #2563eb; }
.est-stat h4.amber { color: #d97706; }

.est-card {
    background: #fff; border: 1px solid #e2e8f0; border-radius: 10px;
    box-shadow: 0 1px 4px rgba(0,0,0,.06); margin-bottom: 24px; overflow: hidden;
}
.est-card-head {
    padding: 14px 20px; background: #1e293b; color: #fff;
    display: flex; align-items: center; justify-content: space-between;
}
.est-card-head h5 { margin: 0; font-size: .95rem; font-weight: 600; }
.est-card-body { padding: 20px 24px; }

.est-table { width: 100%; border-collapse: collapse; font-size: .88rem; }
.est-table thead tr { background: #f8fafc; }
.est-table th {
    padding: 10px 12px; text-align: left; font-weight: 600;
    color: #475569; border-bottom: 2px solid #e2e8f0; white-space: nowrap;
}
.est-table td {
    padding: 10px 12px; border-bottom: 1px solid #f1f5f9;
    color: #334155; vertical-align: middle;
}
.est-table tbody tr:last-child td { border-bottom: none; }
.est-table tbody tr:hover { background: #f8fafc; }
.est-table .muted { color: #94a3b8; font-size: .8rem; }
.est-table .center { text-align: center; }

.badge {
    display: inline-block; padding: 2px 10px; border-radius: 20px;
    font-size: .72rem; font-weight: 700; letter-spacing: .04em;
}
.badge-draft    { background: #f1f5f9; color: #475569; }
.badge-sent     { background: #dbeafe; color: #1d4ed8; }
.badge-approved { background: #dcfce7; color: #15803d; }
.badge-rejected { background: #fee2e2; color: #dc2626; }
.badge-revised  { background: #fef3c7; color: #92400e; }

.btn-primary {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 9px 20px; background: #1e293b; color: #fff; border: none;
    border-radius: 7px; font-size: .88rem; font-weight: 600;
    cursor: pointer; text-decoration: none; transition: background .15s;
}
.btn-primary:hover { background: #0f172a; color: #fff; }

.btn-sm {
    display: inline-flex; align-items: center; gap: 3px;
    padding: 4px 10px; border: none; border-radius: 5px;
    font-size: .76rem; font-weight: 600; cursor: pointer; transition: background .15s;
    text-decoration: none;
}
.btn-view   { background: #e0f2fe; color: #0369a1; }
.btn-edit   { background: #fef3c7; color: #92400e; }
.btn-pdf    { background: #f0fdf4; color: #15803d; }
.btn-del    { background: #fee2e2; color: #dc2626; }
.btn-view:hover { background: #bae6fd; }
.btn-edit:hover { background: #fde68a; }
.btn-pdf:hover  { background: #dcfce7; }
.btn-del:hover  { background: #fecaca; }

.est-form-grid {
    display: grid; grid-template-columns: repeat(3,1fr);
    gap: 14px; margin-bottom: 16px;
}
.est-form-grid.cols2 { grid-template-columns: repeat(2,1fr); }
.est-form-grid.cols4 { grid-template-columns: repeat(4,1fr); }
.est-form-grid.cols1 { grid-template-columns: 1fr; }

.fg label {
    display: block; font-size: .8rem; font-weight: 600;
    color: #475569; margin-bottom: 5px;
}
.fg input, .fg select, .fg textarea {
    width: 100%; padding: 8px 12px; border: 1px solid #cbd5e1;
    border-radius: 7px; font-size: .88rem; color: #1e293b;
    background: #fff; box-sizing: border-box; font-family: inherit;
    transition: border-color .15s;
}
.fg input:focus, .fg select:focus, .fg textarea:focus {
    outline: none; border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59,130,246,.13);
}

.section-divider {
    background: #f1f5f9; border: 1px solid #e2e8f0;
    border-radius: 8px; padding: 10px 14px; margin-bottom: 10px;
    display: flex; align-items: center; justify-content: space-between;
    font-weight: 700; font-size: .88rem; color: #1e293b;
}

.items-table { width: 100%; border-collapse: collapse; font-size: .85rem; margin-bottom: 8px; }
.items-table th {
    background: #f8fafc; padding: 8px 10px; text-align: left;
    font-weight: 600; color: #475569; border-bottom: 2px solid #e2e8f0;
    font-size: .78rem;
}
.items-table td { padding: 6px 6px; border-bottom: 1px solid #f1f5f9; vertical-align: middle; }
.items-table input, .items-table select {
    width: 100%; padding: 6px 8px; border: 1px solid #cbd5e1;
    border-radius: 5px; font-size: .82rem; font-family: inherit;
    box-sizing: border-box; background: #fff;
}
.items-table input:focus, .items-table select:focus {
    outline: none; border-color: #3b82f6;
}
.items-table .amt-cell { font-weight: 600; color: #16a34a; text-align: right; min-width: 80px; padding-right: 10px; }
.items-table .del-cell { text-align: center; width: 36px; }
.del-row-btn {
    background: #fee2e2; border: none; border-radius: 4px;
    color: #dc2626; cursor: pointer; padding: 3px 7px; font-size: .8rem;
}
.del-row-btn:hover { background: #fecaca; }

.add-row-btn {
    background: none; border: 1px dashed #94a3b8; border-radius: 6px;
    color: #64748b; padding: 6px 14px; font-size: .82rem; cursor: pointer;
    transition: all .15s; width: 100%; margin-bottom: 16px;
}
.add-row-btn:hover { border-color: #3b82f6; color: #3b82f6; background: #eff6ff; }

.add-section-btn {
    background: none; border: 1px dashed #3b82f6; border-radius: 6px;
    color: #3b82f6; padding: 7px 16px; font-size: .82rem; cursor: pointer;
    transition: all .15s;
}
.add-section-btn:hover { background: #eff6ff; }

.est-totals { display: flex; justify-content: flex-end; margin-top: 16px; }
.est-totals-box {
    background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px;
    padding: 14px 20px; min-width: 280px;
}
.est-totals-box .trow {
    display: flex; justify-content: space-between;
    font-size: .88rem; padding: 4px 0; color: #475569;
}
.est-totals-box .trow.total {
    border-top: 2px solid #e2e8f0; margin-top: 8px; padding-top: 10px;
    font-weight: 700; font-size: 1rem; color: #1e293b;
}

.pp-alert {
    border-radius: 10px; padding: 14px 18px; margin-bottom: 20px;
    font-size: .88rem; display: flex; align-items: flex-start; gap: 10px;
}
.pp-alert-error   { background: #fee2e2; border: 1px solid #fca5a5; color: #b91c1c; }
.pp-alert-success { background: #dcfce7; border: 1px solid #86efac; color: #15803d; }
.pp-alert ul { margin: 6px 0 0 0; padding-left: 18px; }

.est-modal-overlay {
    display: none; position: fixed; inset: 0;
    background: rgba(15,23,42,.55); z-index: 9999;
    align-items: flex-start; justify-content: center;
    padding: 24px 16px; overflow-y: auto;
}
.est-modal-overlay.show { display: flex; }
.est-modal {
    background: #fff; border-radius: 14px; padding: 26px;
    width: 100%; max-width: 980px; margin: auto;
    box-shadow: 0 24px 60px rgba(0,0,0,.22);
    position: relative; animation: estIn .18s ease;
}
@keyframes estIn {
    from { transform: translateY(14px) scale(.98); opacity: 0; }
    to   { transform: none; opacity: 1; }
}
.est-modal-close {
    position: absolute; top: 14px; right: 16px;
    background: #f1f5f9; border: none; border-radius: 50%;
    width: 30px; height: 30px; font-size: 1rem;
    color: #64748b; cursor: pointer; line-height: 30px; text-align: center;
}
.est-modal-close:hover { background: #fee2e2; color: #ef4444; }
.est-modal h4 { margin: 0 0 20px; font-size: 1.05rem; font-weight: 700; color: #1e293b; }
.est-modal-footer {
    display: flex; gap: 10px; justify-content: flex-end;
    margin-top: 20px; padding-top: 16px; border-top: 1px solid #e2e8f0;
}
.btn-save {
    padding: 9px 24px; background: #16a34a; color: #fff; border: none;
    border-radius: 7px; font-size: .9rem; font-weight: 600; cursor: pointer;
}
.btn-save:hover { background: #15803d; }
.btn-cancel-modal {
    padding: 9px 18px; background: #f1f5f9; color: #475569; border: none;
    border-radius: 7px; font-size: .9rem; font-weight: 600; cursor: pointer;
}
.btn-cancel-modal:hover { background: #e2e8f0; }

.view-section-title {
    background: #1e293b; color: #fff; padding: 8px 14px;
    border-radius: 6px; font-weight: 700; font-size: .88rem; margin: 14px 0 6px;
}

.view-items-table { width: 100%; border-collapse: collapse; font-size: .84rem; margin-bottom: 12px; }
.view-items-table th {
    background: #f1f5f9; padding: 8px 10px; text-align: left;
    font-weight: 600; color: #475569; border-bottom: 2px solid #e2e8f0;
    font-size: .77rem; white-space: nowrap;
}
.view-items-table td {
    padding: 8px 10px; border-bottom: 1px solid #f1f5f9;
    color: #334155; vertical-align: middle;
}
.view-items-table tbody tr:hover { background: #f8fafc; }
.view-items-table .price-cell { color: #2563eb; font-weight: 600; }
.view-items-table .amt-cell   { color: #16a34a; font-weight: 700; text-align: right; }
.view-items-table .meas-cell  { color: #7c3aed; font-size: .78rem; line-height: 1.5; }

.view-info-grid {
    display: grid; grid-template-columns: repeat(4,1fr);
    gap: 10px; margin-bottom: 16px; font-size: .84rem;
    background: #f8fafc; padding: 14px; border-radius: 8px; border: 1px solid #e2e8f0;
}
.view-info-grid .info-item span.label {
    color: #64748b; font-size: .72rem; font-weight: 700;
    text-transform: uppercase; letter-spacing: .04em;
    display: block; margin-bottom: 3px;
}
.view-info-grid .info-item span.value {
    color: #1e293b; font-weight: 600; display: block;
    word-break: break-word;
}

/* Site Visit accordion styles */
.sv-accordion {
    border: 1px solid #e2e8f0; border-radius: 10px;
    margin-bottom: 6px; overflow: hidden;
}
.sv-accordion summary {
    padding: 10px 14px; cursor: pointer; font-size: .84rem;
    font-weight: 600; color: #334155; background: #f8fafc;
    list-style: none; display: flex; justify-content: space-between;
    align-items: center; user-select: none;
}
.sv-accordion summary::-webkit-details-marker { display: none; }
.sv-accordion summary .sv-arrow {
    color: #94a3b8; font-size: .7rem; transition: transform .2s;
}
.sv-accordion[open] summary .sv-arrow { transform: rotate(180deg); }
.sv-accordion .sv-body {
    padding: 10px 14px; font-size: .83rem; color: #475569;
    line-height: 1.6; background: #fff; border-top: 1px solid #e2e8f0;
}

@media (max-width: 768px) {
    .est-stats { grid-template-columns: repeat(2,1fr); }
    .est-form-grid { grid-template-columns: 1fr 1fr; }
    .est-form-grid.cols4 { grid-template-columns: 1fr 1fr; }
    .view-info-grid { grid-template-columns: repeat(2,1fr); }
}
@media (max-width: 480px) {
    .est-stats { grid-template-columns: 1fr; }
    .est-form-grid { grid-template-columns: 1fr; }
    .view-info-grid { grid-template-columns: 1fr; }
}
</style>

<div class="est-page">

@if(session('success'))
    <div class="pp-alert pp-alert-success">
        <span>✅</span><div><strong>Success:</strong> {{ session('success') }}</div>
    </div>
@endif
@if(session('error'))
    <div class="pp-alert pp-alert-error">
        <span>❌</span><div><strong>Error:</strong> {{ session('error') }}</div>
    </div>
@endif
@if($errors->any())
    <div class="pp-alert pp-alert-error">
        <span>⚠️</span>
        <div><strong>Please fix:</strong>
            <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    </div>
@endif

<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;flex-wrap:wrap;gap:10px;">
    <h3 class="est-title" style="margin:0;">📋 Estimations</h3>
    <button class="btn-primary" onclick="openCreateModal()">➕ New Estimation</button>
</div>

<div class="est-stats">
    <div class="est-stat">
        <small>Total</small>
        <h4>{{ $estimations->count() }}</h4>
    </div>
    <div class="est-stat">
        <small>Draft</small>
        <h4 class="amber">{{ $estimations->whereIn('status',['draft','Draft'])->count() }}</h4>
    </div>
    <div class="est-stat">
        <small>Approved</small>
        <h4 class="green">{{ $estimations->whereIn('status',['approved','Approved'])->count() }}</h4>
    </div>
    <div class="est-stat">
        <small>Total Value</small>
        <h4 class="blue">₹{{ number_format($estimations->sum('grand_total')) }}</h4>
    </div>
</div>

<div class="est-card">
    <div class="est-card-head">
        <h5>📄 All Estimations</h5>
        <span style="font-size:.82rem;opacity:.8;">{{ $estimations->count() }} record(s)</span>
    </div>
    <div class="est-card-body" style="padding:0;">
        <table class="est-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Est. No</th>
                    <th>Client</th>
                    <th>Title</th>
                    <th>Lead / Project</th>
                    <th>Valid Till</th>
                    <th>Subtotal</th>
                    <th>GST</th>
                    <th>Grand Total</th>
                    <th>Status</th>
                    <th class="center">Actions</th>
                </tr>
            </thead>
            <tbody>
            @forelse($estimations as $i => $est)
            <tr>
                <td class="muted">{{ $i+1 }}</td>
                <td><strong>{{ $est->estimation_no }}</strong></td>
                <td>
                    {{ $est->client_name }}
                    @if($est->client_email)
                        <div class="muted">{{ $est->client_email }}</div>
                    @endif
                </td>
                <td>{{ $est->title ?? '—' }}</td>
                <td>
                    @if($est->lead_id)<div style="font-size:.78rem;">🔗 Lead #{{ $est->lead_id }}</div>@endif
                    @if($est->project_id)<div style="font-size:.78rem;">🏗 Project #{{ $est->project_id }}</div>@endif
                    @if(!$est->lead_id && !$est->project_id) — @endif
                </td>
                <td>{{ $est->valid_till ? \Carbon\Carbon::parse($est->valid_till)->format('d M Y') : '—' }}</td>
                <td>₹{{ number_format($est->subtotal) }}</td>
                <td>{{ $est->gst_pct ?? 0 }}% = ₹{{ number_format($est->gst_amount) }}</td>
                <td><strong>₹{{ number_format($est->grand_total) }}</strong></td>
                <td>
                    @php $st = strtolower($est->status ?? 'draft'); @endphp
                    <span class="badge badge-{{ $st }}">{{ ucfirst($st) }}</span>
                </td>
                <td class="center" style="white-space:nowrap;">
                    <button class="btn-sm btn-view" onclick="openViewModal({{ $est->id }})">👁 View</button>
                    <button class="btn-sm btn-edit" onclick="openEditModal({{ $est->id }})">✏️ Edit</button>
                    <a href="{{ route('accounts.estimations.pdf', $est->id) }}" target="_blank" class="btn-sm btn-pdf">📄 PDF</a>
                    <form method="POST"
                          action="{{ route('accounts.estimations.destroy', $est->id) }}"
                          style="display:inline;"
                          onsubmit="return confirm('Delete this estimation?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-sm btn-del">🗑</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="11" class="center" style="padding:32px;color:#94a3b8;">
                    No estimations yet. Click <strong>➕ New Estimation</strong> to create one.
                </td>
            </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

</div>

{{-- ═══ MODAL: Create / Edit ═══ --}}
<div class="est-modal-overlay" id="modal-est-form">
<div class="est-modal">
    <button class="est-modal-close" onclick="closeModal('modal-est-form')">✕</button>
    <h4 id="modal-form-title">➕ New Estimation</h4>

    <form method="POST" id="est-form">
        @csrf
        <span id="est-method-field"></span>
        <input type="hidden" id="h-subtotal"    name="subtotal"    value="0">
        <input type="hidden" id="h-gst-amount"  name="gst_amount"  value="0">
        <input type="hidden" id="h-grand-total" name="grand_total" value="0">

        {{-- Row 1: Est No, Status, Valid Till, GST --}}
        <div class="est-form-grid cols4">
            <div class="fg">
                <label>Estimation No <span style="color:red">*</span></label>
                <input type="text" name="estimation_no" id="f-est-no" required placeholder="EST-001">
            </div>
            <div class="fg">
                <label>Status</label>
                <select name="status" id="f-status">
                    <option value="Draft">Draft</option>
                    <option value="Sent">Sent</option>
                    <option value="Approved">Approved</option>
                    <option value="Rejected">Rejected</option>
                    <option value="Revised">Revised</option>
                </select>
            </div>
            <div class="fg">
                <label>Valid Till</label>
                <input type="date" name="valid_till" id="f-valid-till">
            </div>
            <div class="fg">
                <label>GST %</label>
                <input type="number" name="gst_pct" id="f-gst-pct" value="18" min="0" max="100" step="0.01" oninput="recalcTotals()">
            </div>
        </div>

        {{-- Row 2: Client fields --}}
        <div class="est-form-grid cols4">
            <div class="fg">
                <label>Client Name <span style="color:red">*</span></label>
                <input type="text" name="client_name" id="f-client-name" required>
            </div>
            <div class="fg">
                <label>Client Email</label>
                <input type="email" name="client_email" id="f-client-email">
            </div>
            <div class="fg">
                <label>Client Phone</label>
                <input type="text" name="client_phone" id="f-client-phone">
            </div>
            <div class="fg">
                <label>Site Address</label>
                <input type="text" name="site_address" id="f-site-address">
            </div>
        </div>

        {{-- Row 3: Lead, Project, Title, Discount --}}
        <div class="est-form-grid cols4">
            <div class="fg">
                <label>Lead ID</label>
                <input type="number" name="lead_id" id="f-lead-id" placeholder="Optional">
            </div>
            <div class="fg">
                <label>Project ID</label>
                <input type="number" name="project_id" id="f-project-id" placeholder="Optional">
            </div>
            <div class="fg">
                <label>Title</label>
                <input type="text" name="title" id="f-title" placeholder="e.g. Living Room Design">
            </div>
            <div class="fg">
                <label>Discount (₹)</label>
                <input type="number" name="discount" id="f-discount" value="0" min="0" step="0.01" oninput="recalcTotals()">
            </div>
        </div>

        {{-- Row 4: Scope, Notes --}}
        <div class="est-form-grid cols2">
            <div class="fg">
                <label>Scope of Work</label>
                <input type="text" name="scope" id="f-scope" placeholder="Brief scope...">
            </div>
            <div class="fg">
                <label>Notes</label>
                <input type="text" name="notes" id="f-notes" placeholder="Additional notes...">
            </div>
        </div>

        {{-- Items Section --}}
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;">
            <strong style="font-size:.95rem;color:#1e293b;">📦 Items by Section</strong>
            <button type="button" class="add-section-btn" onclick="addSection()">+ Add Section</button>
        </div>

        <div id="sections-container"></div>

        {{-- Totals --}}
        <div class="est-totals">
            <div class="est-totals-box">
                <div class="trow"><span>Subtotal</span><span id="t-subtotal">₹0</span></div>
                <div class="trow"><span>Discount</span><span id="t-discount">−₹0</span></div>
                <div class="trow"><span id="t-gst-label">GST (18%)</span><span id="t-gst">₹0</span></div>
                <div class="trow total"><span>Grand Total</span><span id="t-grand">₹0</span></div>
            </div>
        </div>

        <div class="est-modal-footer">
            <button type="button" class="btn-cancel-modal" onclick="closeModal('modal-est-form')">Cancel</button>
            <button type="submit" class="btn-save">💾 Save Estimation</button>
        </div>
    </form>
</div>
</div>

{{-- ═══ MODAL: View ═══ --}}
<div class="est-modal-overlay" id="modal-est-view">
<div class="est-modal" style="max-width:1100px;">
    <button class="est-modal-close" onclick="closeModal('modal-est-view')">✕</button>
    <div id="view-content">
        <p style="text-align:center;padding:40px;color:#94a3b8;">Loading...</p>
    </div>
</div>
</div>

<script>
/* ════════════════════════════════════
   Modal helpers
   ════════════════════════════════════ */
function openModal(id)  { document.getElementById(id).classList.add('show'); }
function closeModal(id) { document.getElementById(id).classList.remove('show'); }
document.querySelectorAll('.est-modal-overlay').forEach(el => {
    el.addEventListener('click', e => { if (e.target === el) el.classList.remove('show'); });
});

let sectionCount = 0;
let rowCount     = 0;

/* ════════════════════════════════════
   Create / Edit form helpers
   ════════════════════════════════════ */
function addSection(name, items) {
    name  = name  || '';
    items = items || [];
    sectionCount++;
    const sid = 'sec-' + sectionCount;
    const sc  = sectionCount;
    const container = document.getElementById('sections-container');
    const div = document.createElement('div');
    div.id = sid;
    div.style.marginBottom = '16px';
    div.innerHTML = `
        <div class="section-divider">
            <input type="text" name="sections[${sc}][name]"
                   value="${escHtml(name)}"
                   placeholder="Section name (e.g. Living Room)"
                   style="background:transparent;border:none;font-weight:700;font-size:.9rem;
                          color:#1e293b;width:65%;outline:none;">
            <button type="button" onclick="removeSection('${sid}')"
                    style="background:#ef4444;border:none;color:#fff;border-radius:4px;
                           padding:3px 10px;font-size:.75rem;cursor:pointer;">✕ Remove</button>
        </div>
        <table class="items-table">
            <thead><tr>
                <th style="width:25%">Description</th>
                <th style="width:14%">Category</th>
                <th style="width:9%">Unit</th>
                <th style="width:8%">Qty</th>
                <th style="width:13%">Unit Price (₹)</th>
                <th style="width:11%;text-align:right;">Amount</th>
                <th style="width:4%"></th>
            </tr></thead>
            <tbody id="${sid}-tbody"></tbody>
        </table>
        <button type="button" class="add-row-btn" onclick="addRow('${sid}', ${sc})">+ Add Row</button>
    `;
    container.appendChild(div);
    if (items.length > 0) {
        items.forEach(item => addRow(sid, sc, item));
    } else {
        addRow(sid, sc);
    }
}

function removeSection(sid) {
    document.getElementById(sid)?.remove();
    recalcTotals();
}

function addRow(sid, sc, data) {
    data = data || {};
    rowCount++;
    const rid = 'row-' + rowCount;
    const tbody = document.getElementById(sid + '-tbody');
    if (!tbody) return;
    const units = ['nos','sqft','rft','kg','m','m2','m3','ltr','set','lot'];
    const unitOpts = units.map(u =>
        `<option value="${u}" ${(data.unit||'nos')===u?'selected':''}>${u}</option>`
    ).join('');
    const tr = document.createElement('tr');
    tr.id = rid;
    tr.innerHTML = `
        <td><input type="text"   name="sections[${sc}][items][${rowCount}][description]" value="${escHtml(data.description||data.item_name||'')}" placeholder="Description"></td>
        <td><input type="text"   name="sections[${sc}][items][${rowCount}][category]"    value="${escHtml(data.category||'')}" placeholder="Category"></td>
        <td><select name="sections[${sc}][items][${rowCount}][unit]">${unitOpts}</select></td>
        <td><input type="number" name="sections[${sc}][items][${rowCount}][qty]"        value="${data.qty||data.quantity||1}" min="0" step="0.01" oninput="calcRowAmt('${rid}',${sc})"></td>
        <td><input type="number" name="sections[${sc}][items][${rowCount}][unit_price]" value="${data.unit_price||data.price||0}" min="0" step="0.01" oninput="calcRowAmt('${rid}',${sc})"></td>
        <td class="amt-cell" id="${rid}-amt">₹0</td>
        <input type="hidden" name="sections[${sc}][items][${rowCount}][amount]"     id="${rid}-amount" value="0">
        <input type="hidden" name="sections[${sc}][items][${rowCount}][sort_order]" value="${rowCount}">
        <td class="del-cell"><button type="button" class="del-row-btn" onclick="removeRow('${rid}')">✕</button></td>
    `;
    tbody.appendChild(tr);
    calcRowAmt(rid, sc, data.amount);
}

function removeRow(rid) {
    document.getElementById(rid)?.remove();
    recalcTotals();
}

function calcRowAmt(rid, sc, preset) {
    const row = document.getElementById(rid);
    if (!row) return;
    const qty   = parseFloat(row.querySelector(`input[name*="[qty]"]`)?.value)        || 0;
    const price = parseFloat(row.querySelector(`input[name*="[unit_price]"]`)?.value) || 0;
    const amt   = (preset !== undefined && preset !== null && preset !== '')
                  ? parseFloat(preset) : (qty * price);
    const amtEl = document.getElementById(rid + '-amt');
    const hidEl = document.getElementById(rid + '-amount');
    if (amtEl) amtEl.textContent = '₹' + fmt(amt);
    if (hidEl) hidEl.value = amt;
    recalcTotals();
}

function recalcTotals() {
    let subtotal = 0;
    document.querySelectorAll('input[name*="[amount]"]').forEach(el => {
        subtotal += parseFloat(el.value) || 0;
    });
    const discount = parseFloat(document.getElementById('f-discount')?.value) || 0;
    const gstPct   = parseFloat(document.getElementById('f-gst-pct')?.value)  || 0;
    const taxable  = Math.max(0, subtotal - discount);
    const gstAmt   = taxable * gstPct / 100;
    const grand    = taxable + gstAmt;

    setText('t-subtotal',  '₹'  + fmt(subtotal));
    setText('t-discount',  '−₹' + fmt(discount));
    setText('t-gst-label', 'GST (' + gstPct + '%)');
    setText('t-gst',       '₹'  + fmt(gstAmt));
    setText('t-grand',     '₹'  + fmt(grand));

    document.getElementById('h-subtotal').value    = subtotal;
    document.getElementById('h-gst-amount').value  = gstAmt;
    document.getElementById('h-grand-total').value = grand;
}

function setText(id, val) {
    const el = document.getElementById(id);
    if (el) el.textContent = val;
}
function fmt(n) {
    return parseFloat(n || 0).toLocaleString('en-IN', { maximumFractionDigits: 2 });
}
function escHtml(s) {
    return String(s || '')
        .replace(/&/g,'&amp;')
        .replace(/"/g,'&quot;')
        .replace(/</g,'&lt;')
        .replace(/>/g,'&gt;');
}
function setVal(id, val) {
    const el = document.getElementById(id);
    if (!el) return;
    if (el.tagName === 'SELECT') {
        [...el.options].forEach(o => o.selected = (o.value === String(val || '')));
    } else {
        el.value = (val !== null && val !== undefined) ? val : '';
    }
}

/* ════════════════════════════════════
   Open Create Modal
   ════════════════════════════════════ */
function openCreateModal() {
    document.getElementById('modal-form-title').textContent = '➕ New Estimation';
    document.getElementById('est-form').action = "{{ route('accounts.estimations.store') }}";
    document.getElementById('est-method-field').innerHTML = '';
    resetForm();
    openModal('modal-est-form');
}

function resetForm() {
    ['f-est-no','f-client-name','f-client-email','f-client-phone',
     'f-site-address','f-lead-id','f-project-id','f-title','f-scope','f-notes']
        .forEach(id => { const el = document.getElementById(id); if (el) el.value = ''; });
    setVal('f-status', 'Draft');
    document.getElementById('f-gst-pct').value    = '18';
    document.getElementById('f-discount').value   = '0';
    document.getElementById('f-valid-till').value = '';
    document.getElementById('sections-container').innerHTML = '';
    sectionCount = 0;
    rowCount     = 0;
    addSection('Section 1');
    recalcTotals();
}

/* ════════════════════════════════════
   Open Edit Modal
   ════════════════════════════════════ */
function openEditModal(id) {
    fetch('/accounts/estimations/' + id + '/edit-data')
        .then(r => r.json())
        .then(data => {
            document.getElementById('modal-form-title').textContent = '✏️ Edit Estimation';
            document.getElementById('est-form').action = '/accounts/estimations/' + id;
            document.getElementById('est-method-field').innerHTML =
                '<input type="hidden" name="_method" value="PUT">';

            setVal('f-est-no',       data.estimation_no);
            setVal('f-status',       data.status       || 'Draft');
            setVal('f-valid-till',   data.valid_till   || '');
            setVal('f-gst-pct',      data.gst_pct      || 18);
            setVal('f-client-name',  data.client_name);
            setVal('f-client-email', data.client_email || '');
            setVal('f-client-phone', data.client_phone || '');
            setVal('f-site-address', data.site_address || '');
            setVal('f-lead-id',      data.lead_id      || '');
            setVal('f-project-id',   data.project_id   || '');
            setVal('f-title',        data.title        || '');
            setVal('f-scope',        data.scope        || '');
            setVal('f-notes',        data.notes        || '');
            setVal('f-discount',     data.discount     || 0);

            document.getElementById('sections-container').innerHTML = '';
            sectionCount = 0;
            rowCount     = 0;

            if (data.sections && data.sections.length > 0) {
                data.sections.forEach(sec => addSection(sec.name, sec.items || []));
            } else {
                addSection('Section 1');
            }
            recalcTotals();
            openModal('modal-est-form');
        })
        .catch(err => alert('Error loading estimation: ' + err.message));
}

/* ════════════════════════════════════
   Open View Modal
   ════════════════════════════════════ */
function openViewModal(id) {
    document.getElementById('view-content').innerHTML =
        '<p style="text-align:center;padding:40px;color:#94a3b8;">⏳ Loading...</p>';
    openModal('modal-est-view');

    fetch('/accounts/estimations/' + id + '/view-data')
        .then(r => r.json())
        .then(data => {
            const st      = (data.status || 'draft').toLowerCase();
            const stLabel = st.charAt(0).toUpperCase() + st.slice(1);

            /* ── Header ── */
            let html = '<div style="display:flex;justify-content:space-between;align-items:flex-start;'
                + 'flex-wrap:wrap;gap:10px;margin-bottom:16px;">'
                + '<div>'
                + '<h4 style="margin:0 0 4px;font-size:1.1rem;color:#1e293b;">'
                + escHtml(data.title || data.estimation_no) + '</h4>'
                + '<div style="font-size:.82rem;color:#64748b;">'
                + escHtml(data.estimation_no)
                + ' &nbsp;|&nbsp; <span class="badge badge-' + st + '">' + stLabel + '</span>'
                + '</div>'
                + '</div>'
                + '<a href="/accounts/estimations/' + data.id + '/pdf" target="_blank" '
                + 'class="btn-sm btn-pdf">📄 Download PDF</a>'
                + '</div>';

            /* ── Client Info Grid ── */
            html += '<div class="view-info-grid">'
                + infoCell('CLIENT',     escHtml(data.client_name  || '—'))
                + infoCell('PHONE',      escHtml(data.client_phone || '—'))
                + infoCell('EMAIL',      escHtml(data.client_email || '—'))
                + infoCell('SITE',       escHtml(data.site_address || '—'))
                + infoCell('LEAD',       data.lead_id    ? '🔗 #' + data.lead_id    : '—')
                + infoCell('PROJECT',    data.project_id ? '🏗 #' + data.project_id : '—')
                + infoCell('VALID TILL', escHtml(data.valid_till   || '—'))
                + infoCell('TITLE',      escHtml(data.title        || '—'))
                + infoCell('SCOPE',      escHtml(data.scope        || '—'))
                + infoCell('NOTES',      escHtml(data.notes        || '—'))
                + infoCell('GST %',      (data.gst_pct || 0) + '%')
                + infoCell('DISCOUNT',   '₹' + fmt(data.discount   || 0))
                + '</div>';

            /* ── Sections & Items ── */
            const sections = data.sections || [];
            if (sections.length === 0) {
                html += '<p style="text-align:center;color:#94a3b8;padding:24px;">'
                      + 'No items found for this estimation.</p>';
            }

            sections.forEach(function(sec) {
                html += '<div class="view-section-title">📦 ' + escHtml(sec.name || 'Items') + '</div>';
                html += '<table class="view-items-table"><thead><tr>'
                    + '<th style="width:30px">#</th>'
                    + '<th>Description</th>'
                    + '<th>Category</th>'
                    + '<th>Unit</th>'
                    + '<th style="text-align:right;">Qty</th>'
                    + '<th style="text-align:right;">Unit Price</th>'
                    + '<th>Measurements</th>'
                    + '<th style="text-align:right;">Amount</th>'
                    + '</tr></thead><tbody>';

                const items = sec.items || [];
                if (items.length === 0) {
                    html += '<tr><td colspan="8" style="text-align:center;color:#94a3b8;padding:12px;">'
                          + 'No items in this section</td></tr>';
                }

                items.forEach(function(item, idx) {
                    const desc      = item.description || item.item_name   || '—';
                    const cat       = item.category    || '—';
                    const unit      = item.unit        || '—';
                    const qty       = parseFloat(item.qty        || item.quantity || 0);
                    const unitPrice = parseFloat(item.unit_price || item.price    || 0);
                    const amount    = parseFloat(item.amount     || (qty * unitPrice) || 0);

                    const length  = item.length  || '';
                    const breadth = item.breadth || '';
                    const area    = item.area    || '';
                    let measHtml  = '<span style="color:#94a3b8;">—</span>';
                    if (length && breadth) {
                        const calcArea = area || (parseFloat(length) * parseFloat(breadth)).toFixed(2);
                        measHtml = '<span class="meas-cell">'
                            + length + ' × ' + breadth + ' ft'
                            + '<br>= ' + calcArea + ' sqft'
                            + '</span>';
                    }

                    html += '<tr>'
                        + '<td style="color:#94a3b8;font-size:.76rem;">' + (idx + 1) + '</td>'
                        + '<td><strong>' + escHtml(desc) + '</strong></td>'
                        + '<td><span style="background:#f1f5f9;padding:2px 8px;border-radius:4px;'
                        + 'font-size:.76rem;">' + escHtml(cat) + '</span></td>'
                        + '<td>' + escHtml(unit) + '</td>'
                        + '<td style="text-align:right;font-weight:600;">' + qty + '</td>'
                        + '<td class="price-cell" style="text-align:right;">₹' + fmt(unitPrice) + '</td>'
                        + '<td>' + measHtml + '</td>'
                        + '<td class="amt-cell">₹' + fmt(amount) + '</td>'
                        + '</tr>';
                });

                html += '</tbody></table>';
            });

            /* ════════════════════════════════════
               Site Visit Details Section
               — always shown; empty msg if no data
               ════════════════════════════════════ */
            const sv = data.site_visit || {};

            html += '<p style="font-size:.72rem;font-weight:700;color:#64748b;'
                  + 'text-transform:uppercase;letter-spacing:.06em;margin:22px 0 10px;">'
                  + '🏠 Site Visit Details</p>';

            /* Labour & Transport charges as a small info row */
            const labourCharge    = parseFloat(sv.labour_charge    || 0);
            const transportCharge = parseFloat(sv.transport_charge || 0);

            if (labourCharge > 0 || transportCharge > 0) {
                html += '<div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;'
                      + 'margin-bottom:12px;">';
                if (labourCharge > 0) {
                    html += '<div style="background:#eff6ff;border:1px solid #bfdbfe;border-radius:8px;'
                          + 'padding:10px 14px;">'
                          + '<span style="font-size:.72rem;font-weight:700;color:#2563eb;'
                          + 'text-transform:uppercase;letter-spacing:.04em;display:block;margin-bottom:3px;">'
                          + 'Labour Charge</span>'
                          + '<span style="font-size:.95rem;font-weight:700;color:#1e40af;">₹'
                          + fmt(labourCharge) + '</span></div>';
                }
                if (transportCharge > 0) {
                    html += '<div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:8px;'
                          + 'padding:10px 14px;">'
                          + '<span style="font-size:.72rem;font-weight:700;color:#16a34a;'
                          + 'text-transform:uppercase;letter-spacing:.04em;display:block;margin-bottom:3px;">'
                          + 'Transport Charge</span>'
                          + '<span style="font-size:.95rem;font-weight:700;color:#15803d;">₹'
                          + fmt(transportCharge) + '</span></div>';
                }
                html += '</div>';
            }

            /* Accordion fields */
            const svFields = [
                ['Space Details',            sv.space_details],
                ['Materials & Finishes',     sv.materials_finishes],
                ['Style Preferences',        sv.style_preferences],
                ['Appliances & Accessories', sv.appliances_accessories],
                ['Brand Preferences',        sv.brand_preferences],
                ['Finish Preferences',       sv.finish_preferences],
                ['Site Condition Notes',     sv.site_condition_notes],
            ];

            const hasAny = svFields.some(function(f) { return f[1]; });

            if (!hasAny && labourCharge === 0 && transportCharge === 0) {
                html += '<div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:8px;'
                      + 'padding:14px 18px;font-size:.84rem;color:#94a3b8;">'
                      + 'No site visit data linked to this estimation.</div>';
            } else {
                svFields.forEach(function(fieldPair) {
                    const label = fieldPair[0];
                    const value = fieldPair[1];
                    if (!value) return;
                    html += '<details class="sv-accordion">'
                          + '<summary>'
                          + escHtml(label)
                          + '<span class="sv-arrow">▾</span>'
                          + '</summary>'
                          + '<div class="sv-body">' + escHtml(value) + '</div>'
                          + '</details>';
                });
            }

            /* ── Totals ── */
            html += '<div style="display:flex;justify-content:flex-end;margin-top:20px;">'
                + '<div class="est-totals-box">'
                + '<div class="trow"><span>Subtotal</span>'
                +   '<span>₹' + fmt(data.subtotal || 0) + '</span></div>'
                + '<div class="trow"><span>Discount</span>'
                +   '<span style="color:#dc2626;">−₹' + fmt(data.discount || 0) + '</span></div>'
                + '<div class="trow"><span>Labour Charge</span>'
                +   '<span style="color:#2563eb;">+₹' + fmt(labourCharge) + '</span></div>'
                + '<div class="trow"><span>Transport Charge</span>'
                +   '<span style="color:#16a34a;">+₹' + fmt(transportCharge) + '</span></div>'
                + '<div class="trow"><span>GST (' + (data.gst_pct || 0) + '%)</span>'
                +   '<span>₹' + fmt(data.gst_amount || 0) + '</span></div>'
                + '<div class="trow total"><span>Grand Total</span>'
                +   '<span style="color:#16a34a;font-size:1.05rem;">₹'
                +   fmt(data.grand_total || 0) + '</span></div>'
                + '</div>'
                + '</div>';

            document.getElementById('view-content').innerHTML = html;
        })
        .catch(function(err) {
            document.getElementById('view-content').innerHTML =
                '<p style="text-align:center;padding:40px;color:#dc2626;">'
                + '❌ Error loading data: ' + escHtml(err.message) + '</p>';
        });
}

/* Helper: single info cell */
function infoCell(label, value) {
    return '<div class="info-item">'
        + '<span class="label">' + label + '</span>'
        + '<span class="value">' + value + '</span>'
        + '</div>';
}
</script>

@endsection
