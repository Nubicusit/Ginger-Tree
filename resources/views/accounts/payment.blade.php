{{-- resources/views/accounts/payment.blade.php --}}
@extends('accounts.layout.app')

@section('title','Accounts – Payment Management')

@section('content')

@php
$isList = isset($projects) && !isset($project);

if ($isList) {
    $totalProjects = $projects->count();
    $totalValue    = $projects->sum('total_value');
    $totalReceived = $projects->sum('payments_sum_amount');
    $totalBalance  = $projects->sum('balance');
} else {
    $payments      = $payments      ?? collect([]);
    $totalPaid     = $totalPaid     ?? $payments->sum('amount');
    $totalGST      = $totalGST      ?? $payments->sum('gst_amount');
    $totalDiscount = $totalDiscount ?? $payments->sum('discount_amount');
    $balance       = $balance       ?? (($project->total_value ?? 0) - $totalPaid);
    $grandTotal    = $grandTotal    ?? ($project->total_value ?? 0);
    $pct           = $pct           ?? 0;
}
@endphp

<style>
.pp-page  { padding: 24px; }
.pp-title { font-size: 1.35rem; font-weight: 700; margin-bottom: 20px; color: #1e293b; }

.pp-stats {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px;
    margin-bottom: 24px;
}
.pp-stat-card {
    background: #fff; border: 1px solid #e2e8f0; border-radius: 10px;
    padding: 16px 20px; box-shadow: 0 1px 4px rgba(0,0,0,.06);
}
.pp-stat-card small {
    display: flex; align-items: center; gap: 6px;
    font-size: .75rem; color: #64748b;
    text-transform: uppercase; letter-spacing: .05em; margin-bottom: 6px;
}
.pp-stat-card h4       { margin: 0; font-size: 1.4rem; font-weight: 700; color: #1e293b; }
.pp-stat-card h4.green { color: #16a34a; }
.pp-stat-card h4.red   { color: #dc2626; }
.pp-stat-card h4.blue  { color: #2563eb; }

.pay-bar { height: 6px; background: #e2e8f0; border-radius: 20px; margin-top: 8px; overflow: hidden; }
.pay-bar-fill { height: 100%; background: linear-gradient(90deg,#16a34a,#4ade80); border-radius: 20px; transition: width .5s; }

.pp-card {
    background: #fff; border: 1px solid #e2e8f0; border-radius: 10px;
    box-shadow: 0 1px 4px rgba(0,0,0,.06); margin-bottom: 24px; overflow: hidden;
}
.pp-card-body { padding: 20px 24px; }
.pp-card-head {
    padding: 14px 20px; background: #1e293b; color: #fff;
    display: flex; align-items: center; justify-content: space-between;
}
.pp-card-head h5 { margin: 0; font-size: .95rem; font-weight: 600; }
.pp-card h5 { font-size: 1rem; font-weight: 600; color: #1e293b; margin-bottom: 14px; }

.pp-table { width: 100%; border-collapse: collapse; font-size: .88rem; }
.pp-table thead tr { background: #f8fafc; }
.pp-table th {
    padding: 10px 12px; text-align: left; font-weight: 600;
    color: #475569; border-bottom: 2px solid #e2e8f0; white-space: nowrap;
}
.pp-table td {
    padding: 10px 12px; border-bottom: 1px solid #f1f5f9;
    color: #334155; vertical-align: middle;
}
.pp-table tbody tr:last-child td { border-bottom: none; }
.pp-table tbody tr:hover { background: #f8fafc; }
.pp-table .green  { color: #16a34a; font-weight: 600; }
.pp-table .red    { color: #dc2626; font-weight: 600; }
.pp-table .blue   { color: #2563eb; }
.pp-table .muted  { color: #94a3b8; font-size: .8rem; }
.pp-table .center { text-align: center; }

.badge {
    display: inline-block; padding: 2px 10px; border-radius: 20px;
    font-size: .72rem; font-weight: 700; letter-spacing: .04em;
}
.badge-pending  { background: #fef9c3; color: #854d0e; }
.badge-cleared  { background: #dcfce7; color: #15803d; }
.badge-rejected { background: #fee2e2; color: #dc2626; }

.btn-manage {
    display: inline-block; padding: 5px 14px; background: #3b82f6;
    color: #fff; border-radius: 6px; font-size: .82rem; font-weight: 600;
    text-decoration: none; transition: background .15s;
}
.btn-manage:hover { background: #2563eb; color: #fff; }

.btn-edit {
    display: inline-flex; align-items: center; gap: 3px; padding: 4px 10px;
    background: #f59e0b; color: #fff; border: none; border-radius: 5px;
    font-size: .76rem; font-weight: 600; cursor: pointer; transition: background .15s;
}
.btn-edit:hover { background: #d97706; }

.btn-del {
    display: inline-flex; align-items: center; gap: 3px; padding: 4px 10px;
    background: #ef4444; color: #fff; border: none; border-radius: 5px;
    font-size: .76rem; font-weight: 600; cursor: pointer; transition: background .15s;
}
.btn-del:hover { background: #dc2626; }

.btn-approve {
    display: inline-flex; align-items: center; gap: 3px; padding: 4px 10px;
    background: #16a34a; color: #fff; border: none; border-radius: 5px;
    font-size: .76rem; font-weight: 600; cursor: pointer; transition: background .15s;
}
.btn-approve:hover { background: #15803d; }

.btn-save {
    padding: 9px 24px; background: #16a34a; color: #fff; border: none;
    border-radius: 7px; font-size: .9rem; font-weight: 600;
    cursor: pointer; transition: background .15s; white-space: nowrap;
}
.btn-save:hover { background: #15803d; }

.pencil-btn {
    background: none; border: 1px solid #cbd5e1; border-radius: 5px;
    padding: 1px 6px; font-size: .7rem; color: #3b82f6;
    cursor: pointer; font-weight: 600; line-height: 1.4; margin-left: 4px;
}
.pencil-btn:hover { background: #eff6ff; }

.pp-form-section {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 14px; margin-bottom: 14px;
}
.pp-form-section.cols2 { grid-template-columns: repeat(2,1fr); }
.pp-form-section.cols4 { grid-template-columns: repeat(4,1fr); }

.pp-form-group label {
    display: block; font-size: .8rem; font-weight: 600;
    color: #475569; margin-bottom: 5px;
}
.pp-form-group input,
.pp-form-group select,
.pp-form-group textarea {
    width: 100%; padding: 8px 12px; border: 1px solid #cbd5e1;
    border-radius: 7px; font-size: .88rem; color: #1e293b;
    background: #fff; box-sizing: border-box; transition: border-color .15s;
    font-family: inherit;
}
.pp-form-group input:focus,
.pp-form-group select:focus { outline: none; border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59,130,246,.13); }
.pp-form-group input.is-invalid,
.pp-form-group select.is-invalid { border-color: #ef4444; }

.pp-toggle-row {
    display: flex; align-items: center; gap: 10px;
    margin-bottom: 14px; flex-wrap: wrap;
}
.pp-toggle-row label {
    display: flex; align-items: center; gap: 6px;
    font-size: .85rem; font-weight: 600; color: #475569; cursor: pointer;
}
.pp-toggle-row input[type=checkbox] { width: 16px; height: 16px; cursor: pointer; }
.pp-inline-num {
    width: 100px !important; display: inline-block !important;
    padding: 5px 8px !important; font-size: .85rem !important;
}
.pp-inline-sel {
    width: 120px; padding: 5px 8px; border: 1px solid #cbd5e1;
    border-radius: 6px; font-size: .85rem; font-family: inherit;
}
.pp-inline-sel:focus { outline: none; border-color: #3b82f6; }

.pp-conditional { display: none; grid-template-columns: repeat(3,1fr); gap: 14px; }
.pp-conditional.active { display: grid; }

.file-link {
    display: inline-flex; align-items: center; gap: 4px;
    font-size: .78rem; color: #3b82f6; text-decoration: none;
}
.file-link:hover { text-decoration: underline; }

/* File thumbnails */
.file-thumbs { display: flex; flex-wrap: wrap; gap: 6px; margin-top: 6px; }
.file-thumb {
    display: inline-flex; align-items: center; gap: 4px;
    background: #f1f5f9; border: 1px solid #e2e8f0; border-radius: 6px;
    padding: 3px 8px; font-size: .76rem; color: #3b82f6; text-decoration: none;
}
.file-thumb:hover { background: #e0f2fe; }

/* Multi-file preview */
.multi-file-preview { display: flex; flex-wrap: wrap; gap: 6px; margin-top: 6px; }
.multi-file-chip {
    background: #f0fdf4; border: 1px solid #86efac; border-radius: 5px;
    padding: 2px 8px; font-size: .74rem; color: #15803d;
}

/* Alert boxes */
.pp-alert {
    border-radius: 10px; padding: 14px 18px; margin-bottom: 20px;
    font-size: .88rem; display: flex; align-items: flex-start; gap: 10px;
}
.pp-alert-error   { background: #fee2e2; border: 1px solid #fca5a5; color: #b91c1c; }
.pp-alert-success { background: #dcfce7; border: 1px solid #86efac; color: #15803d; }
.pp-alert ul { margin: 6px 0 0 0; padding-left: 18px; }
.pp-alert ul li { margin-bottom: 2px; }

/* Modal */
.pp-modal-overlay {
    display: none; position: fixed; inset: 0;
    background: rgba(15,23,42,.5); z-index: 9999;
    align-items: center; justify-content: center; padding: 16px;
}
.pp-modal-overlay.show { display: flex; }
.pp-modal {
    background: #fff; border-radius: 14px; padding: 26px 26px 22px;
    width: 100%; max-width: 600px; max-height: 90vh; overflow-y: auto;
    box-shadow: 0 24px 60px rgba(0,0,0,.22);
    position: relative; animation: ppIn .18s ease;
}
@keyframes ppIn {
    from { transform: translateY(14px) scale(.98); opacity: 0; }
    to   { transform: none; opacity: 1; }
}
.pp-modal-close {
    position: absolute; top: 14px; right: 16px;
    background: #f1f5f9; border: none; border-radius: 50%;
    width: 28px; height: 28px; font-size: 1rem;
    color: #64748b; cursor: pointer; line-height: 28px; text-align: center;
}
.pp-modal-close:hover { background: #fee2e2; color: #ef4444; }
.pp-modal h4 { margin: 0 0 18px; font-size: 1.05rem; font-weight: 700; color: #1e293b; }
.pp-modal .pp-form-section { grid-template-columns: repeat(2,1fr); }
.pp-modal-footer { display: flex; gap: 10px; justify-content: flex-end; margin-top: 18px; }
.btn-modal-update {
    padding: 9px 22px; background: #3b82f6; color: #fff; border: none;
    border-radius: 7px; font-size: .9rem; font-weight: 700; cursor: pointer; transition: background .15s;
}
.btn-modal-update:hover { background: #2563eb; }
.btn-modal-cancel {
    padding: 9px 18px; background: #f1f5f9; color: #475569; border: none;
    border-radius: 7px; font-size: .9rem; font-weight: 600; cursor: pointer;
}
.btn-modal-cancel:hover { background: #e2e8f0; }

@media (max-width: 768px) {
    .pp-stats { grid-template-columns: repeat(2,1fr); }
    .pp-form-section { grid-template-columns: 1fr 1fr; }
    .pp-form-section.cols4 { grid-template-columns: 1fr 1fr; }
    .pp-modal .pp-form-section { grid-template-columns: 1fr; }
}
@media (max-width: 480px) {
    .pp-stats { grid-template-columns: 1fr; }
    .pp-form-section { grid-template-columns: 1fr; }
}
</style>

<div class="pp-page">

{{-- ═══ FLASH MESSAGES & VALIDATION ERRORS ═══ --}}
@if(session('success'))
    <div class="pp-alert pp-alert-success">
        <span style="font-size:1.1rem;">✅</span>
        <div><strong>Success:</strong> {{ session('success') }}</div>
    </div>
@endif

@if(session('error'))
    <div class="pp-alert pp-alert-error">
        <span style="font-size:1.1rem;">❌</span>
        <div><strong>Error:</strong> {{ session('error') }}</div>
    </div>
@endif

@if($errors->any())
    <div class="pp-alert pp-alert-error">
        <span style="font-size:1.1rem;">⚠️</span>
        <div>
            <strong>Please fix the following errors:</strong>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif

{{-- ─────────────────────────────────────────
     PROJECT LIST
───────────────────────────────────────────── --}}
@if($isList)

    <h3 class="pp-title">📁 Projects Payment Overview</h3>

    <div class="pp-stats">
        <div class="pp-stat-card"><small>Total Projects</small><h4>{{ $totalProjects }}</h4></div>
        <div class="pp-stat-card"><small>Total Value</small><h4>₹{{ number_format($totalValue) }}</h4></div>
        <div class="pp-stat-card"><small>Received</small><h4 class="green">₹{{ number_format($totalReceived) }}</h4></div>
        <div class="pp-stat-card"><small>Balance</small><h4 class="red">₹{{ number_format($totalBalance) }}</h4></div>
    </div>

    <div class="pp-card">
        <div class="pp-card-body">
            <table class="pp-table">
                <thead>
                    <tr>
                        <th>#</th><th>Project</th><th>Client</th>
                        <th>Total Value</th><th>Received</th><th>Balance</th>
                        <th>Progress</th><th>Action</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($projects as $i => $proj)
                    @php
                        $recv = $proj->payments_sum_amount ?? 0;
                        $barPct = $proj->total_value > 0 ? min(100, round($recv / $proj->total_value * 100)) : 0;
                    @endphp
                    <tr>
                        <td class="muted">{{ $i+1 }}</td>
                        <td><strong>{{ $proj->name }}</strong></td>
                        <td>{{ $proj->client }}</td>
                        <td>₹{{ number_format($proj->total_value) }}</td>
                        <td class="green">₹{{ number_format($recv) }}</td>
                        <td class="red">₹{{ number_format($proj->balance) }}</td>
                        <td style="min-width:90px;">
                            <div style="font-size:.75rem;color:#64748b;margin-bottom:3px;">{{ $barPct }}%</div>
                            <div class="pay-bar"><div class="pay-bar-fill" style="width:{{ $barPct }}%"></div></div>
                        </td>
                        <td>
                            <a href="{{ route('accounts.projects.show', $proj->id) }}" class="btn-manage">Manage</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="center" style="padding:28px;color:#94a3b8;">No projects found</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

{{-- ─────────────────────────────────────────
     SINGLE PROJECT
───────────────────────────────────────────── --}}
@else

    <div style="display:flex;align-items:center;gap:12px;margin-bottom:20px;flex-wrap:wrap;">
        <a href="{{ route('accounts.projects.index') }}" style="color:#64748b;font-size:.85rem;text-decoration:none;">← Projects</a>
        <h3 class="pp-title" style="margin:0;">💳 Payment Management</h3>
    </div>

    {{-- Summary --}}
    <div class="pp-stats">
        <div class="pp-stat-card">
            <small>Project</small>
            <h4 style="font-size:.95rem;line-height:1.3;">{{ $project->name }}</h4>
            <div style="font-size:.78rem;color:#94a3b8;margin-top:3px;">{{ $project->client }}</div>
        </div>
        <div class="pp-stat-card">
            <small>
                Contract Value
                <button class="pencil-btn" onclick="openTotalModal()">✏️ Edit</button>
            </small>
            <h4>₹{{ number_format($project->total_value) }}</h4>
            @if($project->gst_rate)
            <div style="font-size:.75rem;color:#64748b;margin-top:3px;">
                GST {{ $project->gst_rate }}% = ₹{{ number_format($project->total_value * $project->gst_rate / 100) }}
            </div>
            @endif
        </div>
        <div class="pp-stat-card">
            <small>Total Received</small>
            <h4 class="green">₹{{ number_format($totalPaid) }}</h4>
            <div class="pay-bar"><div class="pay-bar-fill" style="width:{{ $pct }}%"></div></div>
            <div style="font-size:.72rem;color:#64748b;margin-top:3px;">{{ $pct }}% of contract</div>
        </div>
        <div class="pp-stat-card">
            <small>Balance Due</small>
            <h4 class="{{ $balance > 0 ? 'red' : 'green' }}">₹{{ number_format($balance) }}</h4>
            @if($totalGST > 0)
            <div style="font-size:.75rem;color:#64748b;margin-top:3px;">
                GST collected: ₹{{ number_format($totalGST) }}
            </div>
            @endif
        </div>
    </div>

    {{-- ── Add Payment ── --}}
    <div class="pp-card">
        <div class="pp-card-head"><h5>➕ Record New Payment</h5></div>
        <div class="pp-card-body">
            <form method="POST"
                  action="{{ route('accounts.projects.payments.store', $project->id) }}"
                  enctype="multipart/form-data">
                @csrf

                <div class="pp-form-section cols4">
                    <div class="pp-form-group">
                        <label>Payment Type <span style="color:red">*</span></label>
                        <select name="payment_type" required
                            class="{{ $errors->has('payment_type') ? 'is-invalid' : '' }}">
                            <option value="">Select…</option>
                            <option {{ old('payment_type') == 'Advance' ? 'selected' : '' }}>Advance</option>
                            <option {{ old('payment_type') == 'Partial' ? 'selected' : '' }}>Partial</option>
                            <option {{ old('payment_type') == 'Full'    ? 'selected' : '' }}>Full</option>
                        </select>
                        @error('payment_type')
                            <div style="color:#dc2626;font-size:.75rem;margin-top:3px;">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="pp-form-group">
                        <label>Method <span style="color:red">*</span></label>
                        <select name="payment_method" id="add-method" required
                            onchange="toggleAddFields()"
                            class="{{ $errors->has('payment_method') ? 'is-invalid' : '' }}">
                            <option value="">Select…</option>
                            <option value="Online"        {{ old('payment_method') == 'Online'        ? 'selected' : '' }}>Online</option>
                            <option value="Bank Transfer" {{ old('payment_method') == 'Bank Transfer' ? 'selected' : '' }}>Bank Transfer</option>
                            <option value="Cash"          {{ old('payment_method') == 'Cash'          ? 'selected' : '' }}>Cash</option>
                        </select>
                        @error('payment_method')
                            <div style="color:#dc2626;font-size:.75rem;margin-top:3px;">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="pp-form-group">
                        <label>Amount (₹) <span style="color:red">*</span></label>
                        <input type="number" name="amount" id="add-amount"
                               value="{{ old('amount') }}"
                               placeholder="0.00" step="0.01" min="1" required
                               oninput="calcPayable()"
                               class="{{ $errors->has('amount') ? 'is-invalid' : '' }}">
                        @error('amount')
                            <div style="color:#dc2626;font-size:.75rem;margin-top:3px;">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="pp-form-group">
                        <label>Remarks</label>
                        <input type="text" name="remarks"
                               value="{{ old('remarks') }}"
                               placeholder="e.g. Advance for materials…">
                    </div>
                </div>

                {{-- Discount & GST toggles --}}
                <div class="pp-toggle-row">
                    <label>
                        <input type="checkbox" id="add-disc-chk" onchange="toggleDiscount()"
                            {{ old('discount_type') && old('discount_type') != 'none' ? 'checked' : '' }}>
                        Apply Discount
                    </label>
                    <span id="disc-fields" style="display:{{ old('discount_type') && old('discount_type') != 'none' ? 'flex' : 'none' }};align-items:center;gap:8px;">
                        <select class="pp-inline-sel" name="discount_type" id="add-disc-type" onchange="calcPayable()">
                            <option value="none">Type</option>
                            <option value="percent" {{ old('discount_type') == 'percent' ? 'selected' : '' }}>%</option>
                            <option value="flat"    {{ old('discount_type') == 'flat'    ? 'selected' : '' }}>Flat ₹</option>
                        </select>
                        <input type="number" name="discount_value" id="add-disc-val" class="pp-inline-num"
                               value="{{ old('discount_value') }}"
                               placeholder="0" step="0.01" min="0" oninput="calcPayable()">
                    </span>

                    <label style="margin-left:16px;">
                        <input type="checkbox" name="apply_gst" id="add-gst-chk" value="1"
                            onchange="calcPayable()"
                            {{ old('apply_gst') ? 'checked' : '' }}>
                        GST ({{ $project->gst_rate ?? 18 }}%)
                    </label>
                    <select class="pp-inline-sel" name="gst_type">
                        <option value="IGST"      {{ old('gst_type') == 'IGST'      ? 'selected' : '' }}>IGST</option>
                        <option value="CGST+SGST" {{ old('gst_type') == 'CGST+SGST' ? 'selected' : '' }}>CGST+SGST</option>
                    </select>

                    <span style="margin-left:16px;font-size:.85rem;color:#475569;">
                        Payable: <strong id="calc-payable" style="color:#16a34a;">₹0</strong>
                    </span>
                </div>

                {{-- Online --}}
                <div class="pp-conditional {{ old('payment_method') == 'Online' ? 'active' : '' }}" id="add-online-fields">
                    <div class="pp-form-group">
                        <label>Transaction ID <span style="color:red">*</span></label>
                        <input type="text" name="transaction_id"
                               value="{{ old('transaction_id') }}"
                               placeholder="UTR / TXN"
                               class="{{ $errors->has('transaction_id') ? 'is-invalid' : '' }}">
                        @error('transaction_id')
                            <div style="color:#dc2626;font-size:.75rem;margin-top:3px;">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="pp-form-group">
                        <label>Screenshots <span style="color:red">*</span> <span style="font-weight:400;color:#94a3b8;">(multiple allowed)</span></label>
                        <input type="file" name="screenshot[]" multiple accept="image/*,.pdf"
                               class="{{ $errors->has('screenshot') || $errors->has('screenshot.*') ? 'is-invalid' : '' }}"
                               onchange="previewFileNames(this, 'preview-screenshot')">
                        <div class="multi-file-preview" id="preview-screenshot"></div>
                        @error('screenshot')
                            <div style="color:#dc2626;font-size:.75rem;margin-top:3px;">{{ $message }}</div>
                        @enderror
                        @error('screenshot.*')
                            <div style="color:#dc2626;font-size:.75rem;margin-top:3px;">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Bank Transfer --}}
                <div class="pp-conditional {{ old('payment_method') == 'Bank Transfer' ? 'active' : '' }}" id="add-bank-fields">
                    <div class="pp-form-group">
                        <label>Bank Name <span style="color:red">*</span></label>
                        <input type="text" name="bank_name"
                               value="{{ old('bank_name') }}"
                               placeholder="e.g. HDFC Bank"
                               class="{{ $errors->has('bank_name') ? 'is-invalid' : '' }}">
                        @error('bank_name')
                            <div style="color:#dc2626;font-size:.75rem;margin-top:3px;">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="pp-form-group">
                        <label>Account Number <span style="color:red">*</span></label>
                        <input type="text" name="account_number"
                               value="{{ old('account_number') }}"
                               class="{{ $errors->has('account_number') ? 'is-invalid' : '' }}">
                        @error('account_number')
                            <div style="color:#dc2626;font-size:.75rem;margin-top:3px;">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="pp-form-group">
                        <label>IFSC <span style="color:red">*</span></label>
                        <input type="text" name="ifsc_code"
                               value="{{ old('ifsc_code') }}"
                               class="{{ $errors->has('ifsc_code') ? 'is-invalid' : '' }}">
                        @error('ifsc_code')
                            <div style="color:#dc2626;font-size:.75rem;margin-top:3px;">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="pp-form-group">
                        <label>Transfer Ref <span style="color:red">*</span></label>
                        <input type="text" name="transfer_ref"
                               value="{{ old('transfer_ref') }}"
                               class="{{ $errors->has('transfer_ref') ? 'is-invalid' : '' }}">
                        @error('transfer_ref')
                            <div style="color:#dc2626;font-size:.75rem;margin-top:3px;">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="pp-form-group">
                        <label>Bank Slips <span style="font-weight:400;color:#94a3b8;">(multiple allowed)</span></label>
                        <input type="file" name="bank_slip[]" multiple accept="image/*,.pdf"
                               onchange="previewFileNames(this, 'preview-bank-slip')">
                        <div class="multi-file-preview" id="preview-bank-slip"></div>
                    </div>
                </div>

                {{-- Cash --}}
                <div class="pp-conditional {{ old('payment_method') == 'Cash' ? 'active' : '' }}" id="add-cash-fields">
                    <div class="pp-form-group">
                        <label>Receipts <span style="color:red">*</span> <span style="font-weight:400;color:#94a3b8;">(multiple allowed)</span></label>
                        <input type="file" name="receipt[]" multiple accept="image/*,.pdf"
                               class="{{ $errors->has('receipt') || $errors->has('receipt.*') ? 'is-invalid' : '' }}"
                               onchange="previewFileNames(this, 'preview-receipt')">
                        <div class="multi-file-preview" id="preview-receipt"></div>
                        @error('receipt')
                            <div style="color:#dc2626;font-size:.75rem;margin-top:3px;">{{ $message }}</div>
                        @enderror
                        @error('receipt.*')
                            <div style="color:#dc2626;font-size:.75rem;margin-top:3px;">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div style="margin-top:14px;">
                    <button type="submit" class="btn-save">💾 Save Payment</button>
                </div>
            </form>
        </div>
    </div>

    {{-- ── Payment History ── --}}
    <div class="pp-card">
        <div class="pp-card-head">
            <h5>📋 Payment History</h5>
            <span style="font-size:.82rem;opacity:.8;">
                {{ $payments->count() }} record(s) &nbsp;|&nbsp;
                Discount: ₹{{ number_format($totalDiscount) }} &nbsp;|&nbsp;
                GST: ₹{{ number_format($totalGST) }}
            </span>
        </div>
        <div class="pp-card-body" style="padding:0;">
            <table class="pp-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Type</th>
                        <th>Method</th>
                        <th>Amount</th>
                        <th>Discount</th>
                        <th>GST</th>
                        <th>Total Payable</th>
                        <th>Ref / Files</th>
                        <th>Remarks</th>
                        <th>Status</th>
                        <th style="text-align:center;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($payments as $i => $pay)
                @php
                    $screenshots = is_array($pay->screenshot_path) ? $pay->screenshot_path : json_decode($pay->screenshot_path, true) ?? [];
                    $bankSlips   = is_array($pay->bank_slip_path)   ? $pay->bank_slip_path   : json_decode($pay->bank_slip_path, true)   ?? [];
                    $receipts    = is_array($pay->receipt_path)     ? $pay->receipt_path     : json_decode($pay->receipt_path, true)     ?? [];
                @endphp
                <tr>
                    <td class="muted">{{ $i+1 }}</td>
                    <td>{{ $pay->payment_type }}</td>
                    <td>
                        @php $mIcons=['Online'=>'💻','Bank Transfer'=>'🏦','Cash'=>'💵']; @endphp
                        {{ $mIcons[$pay->payment_method] ?? '💳' }} {{ $pay->payment_method }}
                    </td>
                    <td class="green">₹{{ number_format($pay->amount) }}</td>
                    <td class="red">
                        @if($pay->discount_amount > 0)
                            −₹{{ number_format($pay->discount_amount) }}
                            <div class="muted">
                                ({{ $pay->discount_type==='percent' ? $pay->discount_value.'%' : '₹'.$pay->discount_value }})
                            </div>
                        @else —
                        @endif
                    </td>
                    <td class="blue">
                        @if($pay->apply_gst && $pay->gst_amount > 0)
                            ₹{{ number_format($pay->gst_amount) }}
                            <div class="muted">{{ $pay->gst_type }}</div>
                        @else —
                        @endif
                    </td>
                    <td><strong>₹{{ number_format($pay->total_payable) }}</strong></td>
                    <td style="font-size:.8rem;min-width:130px;">
                        @if($pay->transaction_id) <div>TXN: {{ $pay->transaction_id }}</div> @endif
                        @if($pay->transfer_ref)   <div>Ref: {{ $pay->transfer_ref }}</div>   @endif
                        @if($pay->bank_name)       <div class="muted">{{ $pay->bank_name }}</div> @endif

                        {{-- Screenshots --}}
                        @if(count($screenshots))
                            <div class="file-thumbs">
                                @foreach($screenshots as $idx => $path)
                                    <a href="{{ asset($path) }}" target="_blank" class="file-thumb">
                                        📎 Screenshot {{ count($screenshots) > 1 ? $idx+1 : '' }}
                                    </a>
                                @endforeach
                            </div>
                        @endif

                        {{-- Bank Slips --}}
                        @if(count($bankSlips))
                            <div class="file-thumbs">
                                @foreach($bankSlips as $idx => $path)
                                    <a href="{{ asset($path) }}" target="_blank" class="file-thumb">
                                        📎 Slip {{ count($bankSlips) > 1 ? $idx+1 : '' }}
                                    </a>
                                @endforeach
                            </div>
                        @endif

                        {{-- Receipts --}}
                        @if(count($receipts))
                            <div class="file-thumbs">
                                @foreach($receipts as $idx => $path)
                                    <a href="{{ asset($path) }}" target="_blank" class="file-thumb">
                                        📎 Receipt {{ count($receipts) > 1 ? $idx+1 : '' }}
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </td>
                    <td style="color:#64748b;font-size:.82rem;max-width:120px;">{{ $pay->remarks ?? '—' }}</td>
                    <td>
                        <span class="badge badge-{{ strtolower($pay->status) }}">{{ $pay->status }}</span>
                    </td>
                    <td style="text-align:center;white-space:nowrap;">
                        @if($pay->status === 'Pending')
                        <form method="POST"
                              action="{{ route('accounts.projects.payments.approve',[$project->id,$pay->id]) }}"
                              style="display:inline;">
                            @csrf @method('PATCH')
                            <button type="submit" class="btn-approve" title="Approve">✔</button>
                        </form>
                        @endif

                        <button class="btn-edit" onclick="openEditPayment(
                            {{ $pay->id }},
                            '{{ $pay->payment_type }}',
                            '{{ $pay->payment_method }}',
                            {{ $pay->amount }},
                            '{{ addslashes($pay->remarks ?? '') }}',
                            '{{ addslashes($pay->transaction_id ?? '') }}',
                            '{{ addslashes($pay->bank_name ?? '') }}',
                            '{{ addslashes($pay->account_number ?? '') }}',
                            '{{ addslashes($pay->ifsc_code ?? '') }}',
                            '{{ addslashes($pay->transfer_ref ?? '') }}'
                        )">✏️ Edit</button>

                        <form method="POST"
                              action="{{ route('accounts.projects.payments.destroy',[$project->id,$pay->id]) }}"
                              style="display:inline;"
                              onsubmit="return confirm('Delete ₹{{ number_format($pay->amount) }} payment?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-del">🗑</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="11" class="center" style="padding:32px;color:#94a3b8;">No payments recorded yet</td>
                </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endif
</div>


{{-- ═══ MODAL: Edit Payment ═══ --}}
<div class="pp-modal-overlay" id="modal-edit-pay">
    <div class="pp-modal">
        <button class="pp-modal-close" onclick="closeModal('modal-edit-pay')">✕</button>
        <h4>✏️ Edit Payment</h4>
        <form method="POST" id="form-edit-pay" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="pp-form-section">
                <div class="pp-form-group">
                    <label>Payment Type</label>
                    <select name="payment_type" id="ep-type">
                        <option>Advance</option><option>Partial</option><option>Full</option>
                    </select>
                </div>
                <div class="pp-form-group">
                    <label>Method</label>
                    <select name="payment_method" id="ep-method" onchange="toggleEditFields()">
                        <option value="Online">Online</option>
                        <option value="Bank Transfer">Bank Transfer</option>
                        <option value="Cash">Cash</option>
                    </select>
                </div>
                <div class="pp-form-group">
                    <label>Amount (₹)</label>
                    <input type="number" name="amount" id="ep-amount" step="0.01" min="1" required>
                </div>
                <div class="pp-form-group">
                    <label>Remarks</label>
                    <input type="text" name="remarks" id="ep-remarks">
                </div>
            </div>

            <div class="pp-form-section" id="ep-online-fields" style="display:none;">
                <div class="pp-form-group">
                    <label>Transaction ID</label>
                    <input type="text" name="transaction_id" id="ep-txn">
                </div>
                <div class="pp-form-group">
                    <label>Replace Screenshots <span style="font-weight:400;color:#94a3b8;">(multiple)</span></label>
                    <input type="file" name="screenshot[]" multiple accept="image/*,.pdf"
                           onchange="previewFileNames(this, 'ep-preview-screenshot')">
                    <div class="multi-file-preview" id="ep-preview-screenshot"></div>
                </div>
            </div>

            <div class="pp-form-section" id="ep-bank-fields" style="display:none;">
                <div class="pp-form-group">
                    <label>Bank Name</label>
                    <input type="text" name="bank_name" id="ep-bank">
                </div>
                <div class="pp-form-group">
                    <label>Account Number</label>
                    <input type="text" name="account_number" id="ep-acc">
                </div>
                <div class="pp-form-group">
                    <label>IFSC</label>
                    <input type="text" name="ifsc_code" id="ep-ifsc">
                </div>
                <div class="pp-form-group">
                    <label>Transfer Ref</label>
                    <input type="text" name="transfer_ref" id="ep-ref">
                </div>
                <div class="pp-form-group">
                    <label>Replace Bank Slips <span style="font-weight:400;color:#94a3b8;">(multiple)</span></label>
                    <input type="file" name="bank_slip[]" multiple accept="image/*,.pdf"
                           onchange="previewFileNames(this, 'ep-preview-bank-slip')">
                    <div class="multi-file-preview" id="ep-preview-bank-slip"></div>
                </div>
            </div>

            <div class="pp-form-section" id="ep-cash-fields" style="display:none;">
                <div class="pp-form-group">
                    <label>Replace Receipts <span style="font-weight:400;color:#94a3b8;">(multiple)</span></label>
                    <input type="file" name="receipt[]" multiple accept="image/*,.pdf"
                           onchange="previewFileNames(this, 'ep-preview-receipt')">
                    <div class="multi-file-preview" id="ep-preview-receipt"></div>
                </div>
            </div>

            <div class="pp-modal-footer">
                <button type="button" class="btn-modal-cancel" onclick="closeModal('modal-edit-pay')">Cancel</button>
                <button type="submit" class="btn-modal-update">💾 Update Payment</button>
            </div>
        </form>
    </div>
</div>


{{-- ═══ MODAL: Edit Total Value ═══ --}}
@isset($project)
<div class="pp-modal-overlay" id="modal-edit-total">
    <div class="pp-modal" style="max-width:380px;">
        <button class="pp-modal-close" onclick="closeModal('modal-edit-total')">✕</button>
        <h4>✏️ Edit Contract Value</h4>
        <form method="POST" action="{{ route('accounts.projects.update-total', $project->id) }}">
            @csrf @method('PATCH')
            <div class="pp-form-section cols2">
                <div class="pp-form-group">
                    <label>Total Value (₹)</label>
                    <input type="number" name="total_value"
                           value="{{ $project->total_value }}" step="0.01" min="0" required>
                </div>
                <div class="pp-form-group">
                    <label>GST Rate (%)</label>
                    <input type="number" name="gst_rate"
                           value="{{ $project->gst_rate ?? 18 }}" step="0.01" min="0" max="100">
                </div>
            </div>
            <div class="pp-modal-footer">
                <button type="button" class="btn-modal-cancel" onclick="closeModal('modal-edit-total')">Cancel</button>
                <button type="submit" class="btn-modal-update">💾 Update</button>
            </div>
        </form>
    </div>
</div>
@endisset


<script>
const PROJECT_ID      = {{ $project->id ?? 'null' }};
const PROJECT_GST_PCT = {{ $project->gst_rate ?? 18 }};

// Modal
function openModal(id)  { document.getElementById(id).classList.add('show'); }
function closeModal(id) { document.getElementById(id).classList.remove('show'); }
document.querySelectorAll('.pp-modal-overlay').forEach(el => {
    el.addEventListener('click', e => { if (e.target === el) el.classList.remove('show'); });
});

// Show selected file names as chips
function previewFileNames(input, previewId) {
    const container = document.getElementById(previewId);
    if (!container) return;
    container.innerHTML = '';
    Array.from(input.files).forEach(file => {
        const chip = document.createElement('span');
        chip.className = 'multi-file-chip';
        chip.textContent = file.name;
        container.appendChild(chip);
    });
}

// Add form: method toggle
function toggleAddFields() {
    const m = document.getElementById('add-method').value;
    document.getElementById('add-online-fields').classList.toggle('active', m === 'Online');
    document.getElementById('add-bank-fields').classList.toggle('active',   m === 'Bank Transfer');
    document.getElementById('add-cash-fields').classList.toggle('active',   m === 'Cash');
}

// Add form: discount toggle
function toggleDiscount() {
    const on = document.getElementById('add-disc-chk').checked;
    document.getElementById('disc-fields').style.display = on ? 'flex' : 'none';
    calcPayable();
}

// Live payable
function calcPayable() {
    const amount  = parseFloat(document.getElementById('add-amount')?.value) || 0;
    const discOn  = document.getElementById('add-disc-chk')?.checked;
    const discT   = document.getElementById('add-disc-type')?.value ?? 'none';
    const discV   = parseFloat(document.getElementById('add-disc-val')?.value) || 0;
    const gstOn   = document.getElementById('add-gst-chk')?.checked;
    const disc    = discOn ? (discT === 'percent' ? amount * discV / 100 : discV) : 0;
    const taxable = amount - disc;
    const gst     = gstOn ? taxable * PROJECT_GST_PCT / 100 : 0;
    const el      = document.getElementById('calc-payable');
    if (el) el.textContent = '₹' + (taxable + gst).toLocaleString('en-IN', {maximumFractionDigits:0});
}

// Edit modal open
function openEditPayment(payId, payType, method, amount, remarks, txn, bank, acc, ifsc, ref) {
    document.getElementById('form-edit-pay').action =
        `/accounts/projects/${PROJECT_ID}/payments/${payId}`;
    setVal('ep-type',    payType);
    setVal('ep-method',  method);
    setVal('ep-amount',  amount);
    setVal('ep-remarks', remarks);
    setVal('ep-txn',     txn);
    setVal('ep-bank',    bank);
    setVal('ep-acc',     acc);
    setVal('ep-ifsc',    ifsc);
    setVal('ep-ref',     ref);
    // Clear any file previews
    ['ep-preview-screenshot','ep-preview-bank-slip','ep-preview-receipt'].forEach(id => {
        const el = document.getElementById(id);
        if (el) el.innerHTML = '';
    });
    toggleEditFields();
    openModal('modal-edit-pay');
}

function setVal(id, val) {
    const el = document.getElementById(id);
    if (!el) return;
    if (el.tagName === 'SELECT') {
        [...el.options].forEach(o => o.selected = (o.value === String(val) || o.text === String(val)));
    } else { el.value = val ?? ''; }
}

function toggleEditFields() {
    const m = document.getElementById('ep-method')?.value;
    const show = (id, c) => { const el=document.getElementById(id); if(el) el.style.display = c?'grid':'none'; };
    show('ep-online-fields', m === 'Online');
    show('ep-bank-fields',   m === 'Bank Transfer');
    show('ep-cash-fields',   m === 'Cash');
}

function openTotalModal() { openModal('modal-edit-total'); }

// Restore conditional fields on page load if old() values exist (after validation failure)
document.addEventListener('DOMContentLoaded', function() {
    const method = document.getElementById('add-method')?.value;
    if (method) toggleAddFields();
    calcPayable();
});
</script>
@endsection
