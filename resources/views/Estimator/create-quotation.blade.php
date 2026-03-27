@extends('Estimator.layout.app')

@section('title', 'Create Quotation')

@section('content')

<style>
    @import url('https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:wght@300;400;500;600&display=swap');
    :root {
        --ink: #0f1623; --ink-muted: #64748b; --ink-faint: #94a3b8;
        --surface: #ffffff; --surface-2: #f8fafd; --surface-3: #f1f5f9;
        --border: #e2e8f0; --accent: #6366f1; --accent-light: #eef2ff;
        --green: #10b981; --purple: #8b5cf6; --purple-light: #f5f3ff;
        --radius: 14px; --radius-sm: 8px;
        --shadow: 0 1px 3px rgba(15,22,35,0.06), 0 4px 16px rgba(15,22,35,0.04);
    }
    * { box-sizing: border-box; }
    .q-wrap { font-family: 'Inter', sans-serif; max-width: 900px; margin: 0 auto; }
    /* page header */
    .page-header { display:flex; align-items:center; gap:14px; margin-bottom:24px; }
    .back-btn { width:36px; height:36px; border-radius:10px; display:flex; align-items:center; justify-content:center; border:1.5px solid var(--border); background:var(--surface); color:var(--ink-muted); text-decoration:none; transition:.2s; }
    .back-btn:hover { background:var(--surface-3); color:var(--ink); }
    .page-title { font-family:'Inter',sans-serif; font-size:20px; font-weight:800; color:var(--ink); }
    .page-sub { font-size:13px; color:var(--ink-faint); margin-top:1px; }
    /* grid */
    .q-grid { display:grid; grid-template-columns:1fr 2fr; gap:20px; }
    /* cards */
    .q-card { background:var(--surface); border:1.5px solid var(--border); border-radius:var(--radius); overflow:hidden; box-shadow:var(--shadow); }
    .q-card-accent { height:3px; background:linear-gradient(90deg,#6366f1,#0ea5e9); }
    .q-card-accent-green { height:3px; background:linear-gradient(90deg,#10b981,#06b6d4); }
    .q-card-body { padding:20px; }
    .client-avatar { width:44px; height:44px; border-radius:12px; background:linear-gradient(135deg,#6366f1,#0ea5e9); display:flex; align-items:center; justify-content:center; color:#fff; font-family:'Syne',sans-serif; font-weight:800; font-size:14px; flex-shrink:0; }
    .meta-row { display:flex; justify-content:space-between; font-size:12px; margin-bottom:6px; }
    .meta-label { color:var(--ink-faint); } .meta-val { font-weight:600; color:var(--ink-muted); }
    .quot-no { font-family:'Inter',sans-serif; font-size:22px; font-weight:800; color:var(--accent); letter-spacing:.04em; }
    .site-note-card { background:#fffbeb; border:1.5px solid #fde68a; border-radius:var(--radius); padding:14px 16px; }
    .section-label { font-family:'Inter',sans-serif; font-size:10px; font-weight:700; text-transform:uppercase; letter-spacing:.1em; color:var(--ink-faint); margin-bottom:12px; }
    /* items count badge */
    .count-badge { font-size:11.5px; font-weight:700; color:var(--accent); background:var(--accent-light); padding:3px 10px; border-radius:99px; }
    /* quotation item card */
    .quotation-item { background:var(--surface-2); border:1.5px solid var(--border); border-radius:12px; padding:16px; position:relative; margin-bottom:12px; }
    .quotation-item:last-child { margin-bottom:0; }
    .item-row-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:12px; }
    .item-num { font-family:'Inter',sans-serif; font-size:10px; font-weight:700; color:var(--ink-faint); text-transform:uppercase; letter-spacing:.08em; }
    .remove-btn { font-size:11.5px; font-weight:700; color:#ef4444; background:#fef2f2; border:1.5px solid #fecaca; padding:4px 12px; border-radius:6px; cursor:pointer; transition:.15s; }
    .remove-btn:hover { background:#fee2e2; }
    .form-grid { display:grid; grid-template-columns:1fr 1fr; gap:10px; }
    .form-grid .span2 { grid-column:span 2; }
    .form-label { font-family:'Inter',sans-serif; font-size:9.5px; font-weight:700; text-transform:uppercase; letter-spacing:.08em; color:var(--ink-faint); display:block; margin-bottom:5px; }
    .form-input, .form-select, .form-textarea {
        width:100%; padding:8px 12px; font-size:13px; font-family:'DM Sans',sans-serif;
        color:var(--ink); background:var(--surface); border:1.5px solid var(--border);
        border-radius:var(--radius-sm); outline:none; transition:.2s;
    }
    .form-input:focus, .form-select:focus, .form-textarea:focus {
        border-color:var(--accent); box-shadow:0 0 0 3px rgba(99,102,241,.1);
    }
    .form-input.readonly-field { background:var(--surface-3); color:var(--ink-muted); cursor:default; }
    .form-textarea { resize:none; height:60px; }
    .form-select { appearance:none; background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='none' viewBox='0 0 24 24'%3E%3Cpath stroke='%2394a3b8' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' d='M6 9l6 6 6-6'/%3E%3C/svg%3E"); background-repeat:no-repeat; background-position:right 10px center; padding-right:28px; }
    /* custom toggle */
    .toggle-row { display:flex; align-items:center; justify-content:space-between; margin-bottom:6px; }
    .custom-toggle-label { display:flex; align-items:center; gap:5px; cursor:pointer; font-size:11.5px; font-weight:700; color:var(--accent); }
    /* measurement section */
    .measure-box { background:#f0f7ff; border:1.5px solid #bfdbfe; border-radius:10px; padding:12px; margin-top:6px; }
    .measure-grid { display:grid; grid-template-columns:1fr auto 1fr; gap:8px; align-items:end; }
    .measure-x { display:flex; align-items:center; justify-content:center; padding-bottom:2px; color:var(--ink-faint); font-weight:700; font-size:16px; }
    .area-display { font-family:'Inter',sans-serif; font-size:13px; font-weight:700; color:var(--accent); }
    /* add item btn */
    .add-item-btn { width:100%; margin-top:12px; border:2px dashed var(--border); border-radius:12px; padding:12px; font-size:13px; font-weight:600; color:var(--ink-faint); background:none; cursor:pointer; transition:.2s; }
    .add-item-btn:hover { border-color:#a5b4fc; color:var(--accent); background:var(--accent-light); }
    /* services section */
    .services-header { display:flex; justify-content:space-between; align-items:center; padding:16px 20px; border-top:1.5px solid var(--border); margin-top:4px; }
    .services-toggle-label { display:flex; align-items:center; gap:6px; cursor:pointer; font-size:12px; font-weight:700; color:var(--purple); }
    /* service item card */
    .service-item { background:var(--purple-light); border:1.5px solid #ddd6fe; border-radius:12px; padding:16px; margin-bottom:10px; }
    .service-item .item-num { color:#7c3aed; }
    /* service sub-items table */
    /* Sub-item card rows (replaces table) */
    .si-card { background:#fff; border:1.5px solid #ddd6fe; border-radius:10px; padding:12px 14px; margin-bottom:8px; position:relative; }
    .si-card:last-child { margin-bottom:0; }
    .si-card-num { display:inline-flex; align-items:center; justify-content:center; width:20px; height:20px; background:#ede9fe; color:#7c3aed; border-radius:6px; font-size:10px; font-weight:800; flex-shrink:0; }
    .si-card-header { display:flex; align-items:center; gap:8px; margin-bottom:10px; }
    .si-card-title { font-family:'Inter',sans-serif; font-size:11px; font-weight:700; color:#7c3aed; flex:1; }
    .si-remove-btn { background:none; border:none; color:#ef4444; cursor:pointer; font-size:18px; line-height:1; padding:0 2px; }
    .si-grid { display:grid; grid-template-columns:1fr 1fr; gap:8px; }
    .si-grid .si-span2 { grid-column:span 2; }
    .si-label { font-family:'Inter',sans-serif; font-size:9px; font-weight:700; text-transform:uppercase; letter-spacing:.08em; color:#a78bfa; display:block; margin-bottom:4px; }
    .si-input { width:100%; padding:7px 10px; font-size:12.5px; font-family:'DM Sans',sans-serif; color:var(--ink); background:#faf8ff; border:1.5px solid #ddd6fe; border-radius:7px; outline:none; transition:.15s; }
    .si-input:focus { border-color:#8b5cf6; background:#fff; box-shadow:0 0 0 3px rgba(139,92,246,.1); }
    .si-input.si-manual { background:#fffbf0; border-color:#fcd34d; }
    .si-input.si-manual:focus { border-color:#f59e0b; box-shadow:0 0 0 3px rgba(245,158,11,.12); }
    .si-select { width:100%; padding:7px 26px 7px 10px; font-size:12.5px; font-family:'DM Sans',sans-serif; color:var(--ink); background:#faf8ff url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='11' height='11' fill='none' viewBox='0 0 24 24'%3E%3Cpath stroke='%23a78bfa' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' d='M6 9l6 6 6-6'/%3E%3C/svg%3E") no-repeat right 8px center; border:1.5px solid #ddd6fe; border-radius:7px; outline:none; appearance:none; cursor:pointer; transition:.15s; }
    .si-select:focus { border-color:#8b5cf6; box-shadow:0 0 0 3px rgba(139,92,246,.1); }
    .si-manual-badge { display:inline-flex; align-items:center; gap:3px; font-size:9px; font-weight:700; color:#d97706; background:#fef9c3; border:1px solid #fde68a; border-radius:4px; padding:1px 5px; vertical-align:middle; margin-left:4px; }
    /* action footer */
    .action-footer { display:flex; justify-content:flex-end; gap:10px; margin-top:20px; padding-top:16px; border-top:1.5px solid var(--border); flex-wrap:wrap; }
    .btn-cancel { padding:9px 20px; font-size:13px; font-weight:600; color:var(--ink-muted); background:var(--surface); border:1.5px solid var(--border); border-radius:10px; text-decoration:none; display:inline-flex; align-items:center; transition:.15s; }
    .btn-cancel:hover { background:var(--surface-3); color:var(--ink); }
    .btn-save { padding:9px 24px; font-family:'Inter',sans-serif; font-size:13px; font-weight:700; color:#fff; background:linear-gradient(135deg,#6366f1,#4f46e5); border:none; border-radius:10px; cursor:pointer; box-shadow:0 4px 12px rgba(99,102,241,.3); transition:.2s; letter-spacing:.02em; }
    .btn-save:hover { box-shadow:0 6px 18px rgba(99,102,241,.45); transform:translateY(-1px); }
    .btn-pdf { padding:9px 20px; font-family:'Inter',sans-serif; font-size:13px; font-weight:700; color:#fff; background:linear-gradient(135deg,#dc2626,#b91c1c); border:none; border-radius:10px; cursor:pointer; box-shadow:0 4px 12px rgba(220,38,38,.25); text-decoration:none; display:inline-flex; align-items:center; gap:6px; transition:.2s; }
    .btn-pdf:hover { box-shadow:0 6px 18px rgba(220,38,38,.35); transform:translateY(-1px); }
    /* category row */
    .category-row { margin-top:8px; }
    /* saves to inventory hint */
    .inv-hint { display:flex; align-items:center; gap:4px; font-size:11px; font-weight:600; color:#10b981; }
    /* custom fields grid */
    .custom-fields-grid { display:grid; grid-template-columns:1fr 1fr; gap:8px; margin-top:8px; }
    .custom-fields-grid .span2 { grid-column:span 2; }
    @keyframes spin { to { transform: rotate(360deg); } }
    .s_loading { display:flex; align-items:center; gap:5px; font-size:11px; color:#a78bfa; }
    .si_item_select { appearance:none; background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='10' fill='none' viewBox='0 0 24 24'%3E%3Cpath stroke='%23a78bfa' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' d='M6 9l6 6 6-6'/%3E%3C/svg%3E"); background-repeat:no-repeat; background-position:right 6px center; padding-right:20px !important; }
</style>

<div class="q-wrap">

    {{-- Page Header --}}
    <div class="page-header">
        <a href="{{ url()->previous() }}" class="back-btn">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div>
            <div class="page-title">Create Quotation</div>
            <div class="page-sub">for {{ $lead->client_name }}</div>
        </div>
    </div>

    <div class="q-grid">

        {{-- LEFT SIDEBAR --}}
        <div style="display:flex; flex-direction:column; gap:16px;">

            {{-- Client Card --}}
            <div class="q-card">
                <div class="q-card-accent"></div>
                <div class="q-card-body">
                    <div style="display:flex; align-items:center; gap:12px; margin-bottom:16px;">
                        <div class="client-avatar">
                            {{ strtoupper(substr($lead->client_name, 0, 1)) }}{{ strtoupper(substr(strstr($lead->client_name, ' ') ?: ' ', 1, 1)) }}
                        </div>
                        <div>
                            <div style="font-family:'Syne',sans-serif; font-weight:700; font-size:13.5px; color:var(--ink);">{{ $lead->client_name }}</div>
                            <div style="font-size:12px; color:var(--ink-faint);">{{ $lead->email }}</div>
                        </div>
                    </div>
                    <div class="meta-row"><span class="meta-label">Project Type</span><span class="meta-val">{{ $lead->project_type ?? '-' }}</span></div>
                    <div class="meta-row"><span class="meta-label">Budget</span><span class="meta-val">{{ $siteVisit->budget_sensitivity ?? '-' }}</span></div>
                    <div class="meta-row"><span class="meta-label">Visit Date</span><span class="meta-val">{{ $siteVisit && $siteVisit->visit_datetime ? \Carbon\Carbon::parse($siteVisit->visit_datetime)->format('d M Y') : '-' }}</span></div>
                </div>
            </div>

            {{-- Quotation Number --}}
            <div class="q-card">
                <div class="q-card-body">
                    <div class="section-label">Quotation No.</div>
                    <div class="quot-no">{{ $quotationNo }}</div>
                    <input type="hidden" id="quotationNumber" value="{{ $quotationNo }}">
                    <input type="hidden" id="quotationId"     value="{{ $existingQuotation->id ?? '' }}">
                </div>
            </div>

            {{-- Site Notes --}}
            @if($siteVisit && $siteVisit->site_condition_notes)
            <div class="site-note-card">
                <div style="font-family:'Syne',sans-serif; font-size:9.5px; font-weight:700; text-transform:uppercase; letter-spacing:.1em; color:#92400e; margin-bottom:6px;">Site Notes</div>
                <div style="font-size:12px; color:#78350f; line-height:1.65;">{{ $siteVisit->site_condition_notes }}</div>
            </div>
            @endif
        </div>

        {{-- RIGHT: Quotation Form --}}
        <div class="q-card">
            <div class="q-card-accent-green"></div>
            <div class="q-card-body">

                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;">
                    <div style="font-family:'Syne',sans-serif; font-weight:700; font-size:15px; color:var(--ink);">Quotation Items</div>
                    <span id="item_count" class="count-badge">1 item</span>
                </div>

                {{-- Items Wrapper --}}
                <!-- <div id="quotation_items_wrapper">
                    <div class="quotation-item">
                        <div class="item-row-header">
                            <span class="item-num">Item #1</span>
                            <button type="button" onclick="removeQuotationItem(this)" class="remove-btn hidden">Remove</button>
                        </div>
                        <div class="form-grid">
                            {{-- Item Name --}}
                            <div class="span2">
                                <div class="toggle-row">
                                    <label class="form-label" style="margin-bottom:0;">Item Name</label>
                                    <label class="custom-toggle-label">
                                        <input type="checkbox" class="custom_toggle" style="width:13px;height:13px;accent-color:#6366f1;" onchange="toggleCustomItem(this)">
                                        + Custom Item
                                    </label>
                                </div>
                                <select class="form-select q_item" onchange="updatePrice(this)">
                                    <option value="">-- Select Item --</option>
                                    @foreach($inventoryItems as $item)
                                    <option value="{{ $item->id }}" data-price="{{ $item->price }}" data-category="{{ $item->category }}" data-gst="{{ $item->gst_percentage }}">{{ $item->item_name }}</option>
                                    @endforeach
                                </select>
                                <div class="q_category_row category-row hidden">
                                    <label class="form-label">Category</label>
                                    <input type="text" class="form-input q_category_display readonly-field" readonly placeholder="Category">
                                </div>
                                <div class="custom_fields hidden custom-fields-grid">
                                    <input type="text" class="form-input q_custom_name span2" placeholder="Enter custom item name">
                                    <select class="form-select q_custom_category span2">
                                        <option value="">-- Select Category --</option>
                                        <option>Flooring</option><option>Walls</option><option>Ceiling</option>
                                        <option>Furniture</option><option>Doors & Windows</option>
                                        <option>Electrical</option><option>Sanitary</option>
                                        <option>Civil</option><option>Labour</option><option>Custom</option>
                                    </select>
                                    <input type="number" class="form-input q_custom_price" placeholder="Unit price" oninput="syncCustomPrice(this)">
                                    <input type="number" class="form-input q_custom_gst" placeholder="GST %">
                                    <div class="inv-hint span2">
                                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                        Saves to inventory
                                    </div>
                                </div>
                            </div>
                            {{-- Description --}}
                            <div class="span2">
                                <label class="form-label">Description</label>
                                <textarea class="form-textarea q_description"></textarea>
                            </div>
                            {{-- Qty + Unit --}}
                            <div>
                                <label class="form-label">Quantity</label>
                                <input type="number" class="form-input q_quantity">
                            </div>
                            <div>
                                <label class="form-label">Unit</label>
                                <select class="form-select q_unit">
                                    <option value="">-- Select Unit --</option>
                                    <optgroup label="Area"><option value="Sqft">Sqft</option><option value="Sqm">Sqm</option></optgroup>
                                    <optgroup label="Length"><option value="Rft">Rft</option><option value="Rmt">Rmt</option></optgroup>
                                    <optgroup label="Count"><option value="Nos">Nos</option><option value="Sets">Sets</option><option value="Lots">Lots</option><option value="Pairs">Pairs</option></optgroup>
                                    <optgroup label="Volume"><option value="Cft">Cft</option><option value="Cum">Cum</option></optgroup>
                                    <optgroup label="Time"><option value="Hours">Hours</option><option value="Days">Days</option></optgroup>
                                    <optgroup label="Weight"><option value="Kg">Kg</option><option value="Ltr">Ltr</option></optgroup>
                                </select>
                            </div>
                            {{-- Price + GST --}}
                            <div class="span2">
                                <label class="form-label">Unit Price</label>
                                <input type="number" class="form-input readonly-field q_price" readonly>
                            </div>
                            <div>
                                <label class="form-label">GST %</label>
                                <input type="number" class="form-input q_gst" placeholder="GST %" min="0" max="100">
                            </div>
                            {{-- Measurements --}}
                            <div class="span2">
                                <div class="toggle-row">
                                    <label class="form-label" style="margin-bottom:0;">Measurements <span style="font-weight:400;color:var(--ink-faint);text-transform:none;">(optional)</span></label>
                                    <label class="custom-toggle-label" style="color:var(--accent);">
                                        <input type="checkbox" class="measure_toggle" style="width:13px;height:13px;accent-color:#6366f1;" onchange="toggleMeasurement(this)">
                                        + Add Measurements
                                    </label>
                                </div>
                                <div class="measure-box hidden measure_fields">
                                    <div style="font-size:11px; color:var(--ink-faint); margin-bottom:8px;">Type with unit: <strong style="color:#3b82f6;">200mm · 30cm · 1.5m · 10ft · 4ft 6in</strong></div>
                                    <div class="measure-grid">
                                        <div>
                                            <label class="form-label">Length</label>
                                            <input type="text" class="form-input q_length" placeholder="e.g. 200mm" oninput="calcArea(this)">
                                        </div>
                                        <div class="measure-x">×</div>
                                        <div>
                                            <label class="form-label">Breadth</label>
                                            <input type="text" class="form-input q_breadth" placeholder="e.g. 300mm" oninput="calcArea(this)">
                                        </div>
                                    </div>
                                    <div style="margin-top:8px; display:flex; align-items:center; justify-content:space-between;">
                                        <div style="display:flex;align-items:center;gap:6px;font-size:12px;color:var(--ink-faint);">Area: <span class="q_area area-display">— Sqft</span></div>
                                        <span class="q_conv" style="font-size:11px;color:var(--ink-faint);font-style:italic;"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <button type="button" onclick="addMoreQuotationItem()" class="add-item-btn">+ Add Another Item</button> -->

                {{-- SERVICES SECTION --}}
                <div class="services-header">
                    <div>
                        <div style="font-family:'Syne',sans-serif; font-weight:700; font-size:13.5px; color:var(--ink);">Services</div>
                        <div style="font-size:12px; color:var(--ink-faint); margin-top:2px;">Add services with sub-items (Modular Kitchen, Living Room etc.)</div>
                    </div>
                </div>
                <div id="services_wrapper" style="padding:0 4px;"></div>
                <button type="button" id="add_service_btn" onclick="addServiceItem()"
                    class="add-item-btn" style="border-color:#ddd6fe; color:#8b5cf6;">
                    + Add Another Service
                </button>

                {{-- Action Footer --}}
                <div class="action-footer">
                    <a href="{{ url()->previous() }}" class="btn-cancel">Cancel</a>
                    <button onclick="submitQuotation()" class="btn-save">Save Estimation</button>
                    <a id="pdf_after_save"
                        href="{{ $estimation ? route('estimator.estimation.pdf', $estimation->id) : '#' }}"
                        target="_blank"
                        class="{{ $estimation ? '' : 'hidden' }} btn-pdf">
                        📄 Generate PDF
                    </a>
                    @if($existingQuotation && $existingQuotation->estimation)
                    <a href="{{ route('estimator.estimation.pdf', $existingQuotation->estimation->id) }}"
                        target="_blank" class="btn-pdf">
                        📄 Generate PDF
                    </a>
                    @endif
                </div>

            </div>
        </div>
    </div>
</div>

<script>
const existingItems  = @json($items);
const allInventoryItems = @json($inventoryItems);
const allServices    = @json($services);
const existingServices = @json($existingServices ?? []);

/* ── MEASUREMENT PARSER ── */
const UNIT_TO_FT = { mm:0.00328084, cm:0.0328084, m:3.28084, ft:1, inch:0.0833333, in:0.0833333 };
function parseMeasure(raw) {
    raw = (raw||'').trim().toLowerCase();
    if (!raw) return null;
    let m = raw.match(/^(\d+\.?\d*)\s*(?:ft|feet|')\s*(\d+\.?\d*)\s*(?:in|inch|inches|")?$/);
    if (m) return parseFloat(m[1]) + parseFloat(m[2])/12;
    m = raw.match(/^(\d+\.?\d*)\s*(mm|cm|m(?!in)|ft|feet|inch|inches|in|'|")?$/);
    if (!m) return null;
    const val  = parseFloat(m[1]);
    const unit = (m[2]||'ft').replace(/'/g,'ft').replace(/"/g,'in').replace('feet','ft').replace('inches','in');
    return val * (UNIT_TO_FT[unit]??1);
}
function getAreaSqft(row) {
    const L = parseMeasure(row.querySelector('.q_length')?.value);
    const B = parseMeasure(row.querySelector('.q_breadth')?.value);
    return (L!==null&&B!==null)?(L*B).toFixed(4):'0';
}
function calcArea(input) {
    const item = input.closest('.quotation-item');
    const lenRaw = item.querySelector('.q_length').value;
    const breRaw = item.querySelector('.q_breadth').value;
    const L = parseMeasure(lenRaw), B = parseMeasure(breRaw);
    item.querySelector('.q_length').style.borderColor = lenRaw?(L!==null?'#10b981':'#ef4444'):'';
    item.querySelector('.q_breadth').style.borderColor= breRaw?(B!==null?'#10b981':'#ef4444'):'';
    const area = (L!==null&&B!==null)?L*B:0;
    item.querySelector('.q_area').textContent = area>0?area.toFixed(4)+' Sqft':'— Sqft';
    const conv = item.querySelector('.q_conv');
    const hasUnit = /[a-z'"]/i.test(lenRaw)||/[a-z'"]/i.test(breRaw);
    if (conv) conv.textContent = (area>0&&hasUnit)?`→ ${L.toFixed(3)} ft × ${B.toFixed(3)} ft`:'';
}

function updatePrice(select) {
    const item  = select.closest('.quotation-item');
    const opt   = select.options[select.selectedIndex];
    item.querySelector('.q_price').value = opt.dataset.price||'';
    if (item.querySelector('.q_gst')) item.querySelector('.q_gst').value = opt.dataset.gst||0;
    const cat = opt.dataset.category||'';
    item.querySelector('.q_category_display').value = cat;
    cat ? item.querySelector('.q_category_row').classList.remove('hidden')
        : item.querySelector('.q_category_row').classList.add('hidden');
}
function syncCustomPrice(input) { input.closest('.quotation-item').querySelector('.q_price').value = input.value; }
function toggleCustomItem(cb) {
    const item = cb.closest('.quotation-item');
    if (cb.checked) {
        item.querySelector('.q_item').classList.add('hidden');
        item.querySelector('.q_item').value = '';
        item.querySelector('.custom_fields').classList.remove('hidden');
        item.querySelector('.q_price').value = '';
    } else {
        item.querySelector('.q_item').classList.remove('hidden');
        item.querySelector('.custom_fields').classList.add('hidden');
        item.querySelector('.q_custom_name').value = '';
        item.querySelector('.q_custom_price').value = '';
        item.querySelector('.q_price').value = '';
    }
}
function toggleMeasurement(cb) {
    const item = cb.closest('.quotation-item');
    const f = item.querySelector('.measure_fields');
    if (cb.checked) { f.classList.remove('hidden'); }
    else {
        f.classList.add('hidden');
        item.querySelector('.q_length').value = '';
        item.querySelector('.q_breadth').value = '';
        item.querySelector('.q_area').textContent = '— Sqft';
        const c = item.querySelector('.q_conv'); if (c) c.textContent='';
    }
}

function buildItemNameHTML() {
    return `
    <div class="span2">
        <div class="toggle-row">
            <label class="form-label" style="margin-bottom:0;">Item Name</label>
            <label class="custom-toggle-label"><input type="checkbox" class="custom_toggle" style="width:13px;height:13px;accent-color:#6366f1;" onchange="toggleCustomItem(this)"> + Custom Item</label>
        </div>
        <select class="form-select q_item" onchange="updatePrice(this)">
            <option value="">-- Select Item --</option>
            ${allInventoryItems.map(i=>`<option value="${i.id}" data-price="${i.price}" data-category="${i.category??''}" data-gst="${i.gst_percentage??0}">${i.item_name}</option>`).join('')}
        </select>
        <div class="q_category_row category-row hidden">
            <label class="form-label">Category</label>
            <input type="text" class="form-input q_category_display readonly-field" readonly placeholder="Category">
        </div>
        <div class="custom_fields hidden custom-fields-grid">
            <input type="text" class="form-input q_custom_name span2" placeholder="Enter custom item name">
            <select class="form-select q_custom_category span2">
                <option value="">-- Select Category --</option>
                <option>Flooring</option><option>Walls</option><option>Ceiling</option>
                <option>Furniture</option><option>Doors & Windows</option>
                <option>Electrical</option><option>Sanitary</option>
                <option>Civil</option><option>Labour</option><option>Custom</option>
            </select>
            <input type="number" class="form-input q_custom_price" placeholder="Unit price" oninput="syncCustomPrice(this)">
            <input type="number" class="form-input q_custom_gst" placeholder="GST %">
            <div class="inv-hint span2"><svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>Saves to inventory</div>
        </div>
    </div>`;
}
function buildMeasureHTML() {
    return `<div class="span2">
        <div class="toggle-row">
            <label class="form-label" style="margin-bottom:0;">Measurements <span style="font-weight:400;color:var(--ink-faint);text-transform:none;">(optional)</span></label>
            <label class="custom-toggle-label"><input type="checkbox" class="measure_toggle" style="width:13px;height:13px;accent-color:#6366f1;" onchange="toggleMeasurement(this)"> + Add Measurements</label>
        </div>
        <div class="measure-box hidden measure_fields">
            <div style="font-size:11px;color:var(--ink-faint);margin-bottom:8px;">Type with unit: <strong style="color:#3b82f6;">200mm · 30cm · 1.5m · 10ft</strong></div>
            <div class="measure-grid">
                <div><label class="form-label">Length</label><input type="text" class="form-input q_length" placeholder="e.g. 200mm" oninput="calcArea(this)"></div>
                <div class="measure-x">×</div>
                <div><label class="form-label">Breadth</label><input type="text" class="form-input q_breadth" placeholder="e.g. 300mm" oninput="calcArea(this)"></div>
            </div>
            <div style="margin-top:8px;display:flex;align-items:center;justify-content:space-between;">
                <div style="display:flex;align-items:center;gap:6px;font-size:12px;color:var(--ink-faint);">Area: <span class="q_area area-display">— Sqft</span></div>
                <span class="q_conv" style="font-size:11px;color:var(--ink-faint);font-style:italic;"></span>
            </div>
        </div>
    </div>`;
}

function addMoreQuotationItem() {
    const wrapper = document.getElementById('quotation_items_wrapper');
    const idx = wrapper.querySelectorAll('.quotation-item').length + 1;
    const div = document.createElement('div');
    div.className = 'quotation-item';
    div.innerHTML = `
        <div class="item-row-header">
            <span class="item-num">Item #${idx}</span>
            <button type="button" onclick="removeQuotationItem(this)" class="remove-btn">Remove</button>
        </div>
        <div class="form-grid">
            ${buildItemNameHTML()}
            <div class="span2"><label class="form-label">Description</label><textarea class="form-textarea q_description"></textarea></div>
            <div><label class="form-label">Quantity</label><input type="number" class="form-input q_quantity"></div>
            <div><label class="form-label">Unit</label>
                <select class="form-select q_unit">
                    <option value="">-- Select Unit --</option>
                    <optgroup label="Area"><option value="Sqft">Sqft</option><option value="Sqm">Sqm</option></optgroup>
                    <optgroup label="Length"><option value="Rft">Rft</option><option value="Rmt">Rmt</option></optgroup>
                    <optgroup label="Count"><option value="Nos">Nos</option><option value="Sets">Sets</option><option value="Lots">Lots</option><option value="Pairs">Pairs</option></optgroup>
                    <optgroup label="Volume"><option value="Cft">Cft</option><option value="Cum">Cum</option></optgroup>
                    <optgroup label="Time"><option value="Hours">Hours</option><option value="Days">Days</option></optgroup>
                    <optgroup label="Weight"><option value="Kg">Kg</option><option value="Ltr">Ltr</option></optgroup>
                </select>
            </div>
            <div class="span2"><label class="form-label">Unit Price</label><input type="number" class="form-input readonly-field q_price" readonly></div>
            <div><label class="form-label">GST %</label><input type="number" class="form-input q_gst" placeholder="GST %" min="0" max="100"></div>
            ${buildMeasureHTML()}
        </div>`;
    wrapper.appendChild(div);
    updateItemCount();
}
function removeQuotationItem(btn) {
    btn.closest('.quotation-item').remove();
    document.querySelectorAll('.quotation-item').forEach((item,i)=>item.querySelector('.item-num').textContent=`Item #${i+1}`);
    updateItemCount();
}
function updateItemCount() {
    const c = document.querySelectorAll('.quotation-item').length;
    document.getElementById('item_count').textContent = `${c} item${c>1?'s':''}`;
}

/* ── SERVICES ── */
function toggleServicesSection(cb) {
    // kept for backward compat - no longer used
}

/* ════════════════════════════════════════════════════════
   SERVICE ROW BUILDER
   ════════════════════════════════════════════════════════ */
function buildServiceRow(index) {
    const div = document.createElement('div');
    div.className = 'service-item';
    div.innerHTML = `
        <div class="item-row-header">
            <span class="item-num">Service #${index}</span>
            <button type="button" onclick="removeServiceItem(this)" class="remove-btn ${index===1?'hidden':''}">Remove</button>
        </div>

        {{-- Service selector + pricing summary --}}
        <div class="form-grid">
            <div class="span2">
                <label class="form-label">Service Name</label>
                <select class="form-select s_service" onchange="fillServiceDetails(this)" style="border-color:#ddd6fe;">
                    <option value="">-- Select Service --</option>
                    ${allServices.map(s=>`<option value="${s.id}"
                        data-category="${s.category_service??''}"
                        data-price="${s.price??0}"
                        data-gst="${s.gst_percentage??0}"
                        data-tax="${s.service_tax??0}"
                        data-name="${s.service_name}">${s.service_name}</option>`).join('')}
                </select>
            </div>

            <div class="span2">
                <label class="form-label">Note / Specification <span style="font-weight:400;text-transform:none;color:var(--ink-faint);">(shown in PDF under section header)</span></label>
                <textarea class="form-textarea s_note" style="border-color:#ddd6fe;height:50px;" placeholder="e.g. Using Material: WPC 17mm with Square edge bend all around..."></textarea>
            </div>


        </div>

        {{-- ── Sub-items table ── --}}
        <div class="s_sub_items_wrap" style="margin-top:16px;">

            {{-- Header row --}}
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:8px;">
                <div style="font-family:'Syne',sans-serif;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#7c3aed;">
                    Sub-Items
                    <span style="font-weight:400;font-size:9.5px;color:#a78bfa;text-transform:none;"> — select from service items or add custom</span>
                </div>
                <div class="s_loading" style="display:none;font-size:11px;color:#a78bfa;">
                    <svg width="13" height="13" style="animation:spin .8s linear infinite;display:inline;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/></svg>
                    Fetching items…
                </div>
            </div>

            <div class="s_sub_tbody" style="margin-top:4px;"></div>

            <button type="button" onclick="addBlankSubItem(this)"
                class="add-item-btn"
                style="margin-top:8px;border-color:#ddd6fe;color:#8b5cf6;font-size:12px;padding:8px;">
                + Add Custom Sub-Item
            </button>
            <div style="margin-top:12px;">
    <label style="display:flex;align-items:center;gap:6px;font-size:12px;font-weight:600;color:#7c3aed;">
        <input type="checkbox" class="acc_toggle"
            onchange="toggleAccessories(this)"
            style="accent-color:#8b5cf6;width:14px;height:14px;">
        + Add Accessories
    </label>
</div>

<div class="accessories_wrap hidden" style="margin-top:10px;"></div>

<button type="button" onclick="addAccessoryRow(this)"
    class="add-item-btn hidden acc_add_btn"
    style="margin-top:8px;border-color:#ddd6fe;color:#8b5cf6;font-size:12px;padding:8px;">
    + Add Accessory
</button>
        </div>`;

    return div;
}

/* ════════════════════════════════════════════════════════
   ADD A ROW FOR A SPECIFIC service_item (auto-filled)
   ════════════════════════════════════════════════════════ */
function addServiceItemRow(container, serviceItem) {
    const idx      = container.querySelectorAll('.si-card').length + 1;
    const itemName = escHtml(serviceItem.item_name ?? '');
    const material = escHtml(serviceItem.description ?? serviceItem.material ?? '');
    const unit = '';
    const mrp      = parseFloat(serviceItem.default_price ?? 0) || '';

    const card = document.createElement('div');
    card.className = 'si-card';
    if (serviceItem.id) card.dataset.serviceItemId = serviceItem.id;

    card.innerHTML = `
        <div class="si-card-header">
            <span class="si-card-num">${idx}</span>
            <span class="si-card-title">Sub-Item ${idx}</span>
            <button type="button" onclick="removeSubItemRow(this)" class="si-remove-btn" title="Remove">×</button>
        </div>
        <div class="si-grid">
            <div class="si-span2">
                <label class="si-label">Item Name</label>
                <select class="si-select si_item_select" onchange="onSubItemSelect(this)">
                    <option value="">-- Select Item --</option>
                </select>
                <input type="hidden" class="si_name" value="${itemName}">
            </div>
            <div>
                <label class="si-label">Material / Description</label>
                <input type="text" class="si-input si_material" value="${material}" placeholder="e.g. WPC 17mm">
            </div>
              <div>
                <label class="si-label">Qty</label>
                <input type="number" class="si-input si_qty" value="1" min="1" placeholder="1">
            </div>
            <div>
                <label class="si-label">Unit</label>
                <input type="text" class="si-input si_unit" value="${unit}">
            </div>
            <div>
                <label class="si-label">Size </label>
                <input type="text" class="si-input si-manual si_size" placeholder="e.g. 3600×800mm">
            </div>
            <div>
                <label class="si-label">MRP (₹)</label>
                <input type="number" class="si-input si_mrp" value="${mrp}" placeholder="0">
            </div>
            <div class="si-span2">
                <label class="si-label">Offer Price (₹)</label>
                <input type="number" class="si-input si-manual si_offer" placeholder="Enter offer price"
                    style="font-size:13.5px;font-weight:600;color:#92400e;">
            </div>


        </div>`;

    container.appendChild(card);
    return card;
}

/* ════════════════════════════════════════════════════════
   ADD A BLANK ROW (custom sub-item, no service_item link)
   ════════════════════════════════════════════════════════ */
function addBlankSubItem(btn) {
    const container  = btn.closest('.s_sub_items_wrap').querySelector('.s_sub_tbody');
    const serviceRow = btn.closest('.service-item');
    const serviceId  = serviceRow.querySelector('.s_service').value;
    const card = addServiceItemRow(container, {});
    populateItemDropdown(card.querySelector('.si_item_select'), serviceId, null);
    renumberSubRows(container);
}

/* ════════════════════════════════════════════════════════
   POPULATE THE ITEM DROPDOWN IN A ROW
   ════════════════════════════════════════════════════════ */
function populateItemDropdown(sel, serviceId, selectedItemId) {
    const items = serviceItemsCache[serviceId] || [];
    sel.innerHTML = '<option value="">-- Select Item --</option>';

    items.forEach(si => {
        const opt = document.createElement('option');
        opt.value             = si.id;
        opt.textContent       = si.item_name;
        opt.dataset.material  = si.description  ?? '';

        opt.dataset.mrp       = si.default_price ?? 0;
        if (selectedItemId && String(si.id) === String(selectedItemId)) {
            opt.selected = true;
        }
        sel.appendChild(opt);
    });

    const customOpt = document.createElement('option');
    customOpt.value       = '__custom__';
    customOpt.textContent = '＋ Custom name…';
    sel.appendChild(customOpt);

    // Auto-fill fields if a specific item was pre-selected
    if (selectedItemId) {
        const matched = items.find(si => String(si.id) === String(selectedItemId));
        if (matched) {
            const card = sel.closest('.si-card');
            if (card) {
                card.querySelector('.si_name').value     = matched.item_name     ?? '';
                card.querySelector('.si_material').value = matched.description   ?? '';

                card.querySelector('.si_mrp').value      = matched.default_price ?? '';
            }
        }
    }
}

/* When user picks from the item dropdown — auto-fill material/unit/mrp */
function onSubItemSelect(sel) {
    const card = sel.closest('.si-card');
    const opt  = sel.options[sel.selectedIndex];
    if (!opt) return;

    if (opt.value === '__custom__') {
        const nameInput = document.createElement('input');
        nameInput.type        = 'text';
        nameInput.className   = 'si-input si_name';
        nameInput.placeholder = 'Type item name…';
        sel.parentNode.replaceChild(nameInput, sel);
        nameInput.focus();
        return;
    }

    if (!opt.value) return;

    const nameHidden = card.querySelector('.si_name');
    if (nameHidden) nameHidden.value = opt.textContent.trim();

    // ✅ Keep only these auto-fills
    if (opt.dataset.material) card.querySelector('.si_material').value = opt.dataset.material;
    if (opt.dataset.mrp)      card.querySelector('.si_mrp').value      = opt.dataset.mrp;

     card.querySelector('.si_unit').value = '';

    const offerInput = card.querySelector('.si_offer');
    if (offerInput) {
        offerInput.style.borderColor = '#f59e0b';
        offerInput.focus();
    }
}

/* ════════════════════════════════════════════════════════
   FILL SERVICE DETAILS + FETCH service_items
   ════════════════════════════════════════════════════════ */
const serviceItemsCache = {};   // { serviceId: [service_items array] }

function fillServiceDetails(select) {
    const row   = select.closest('.service-item');
    const opt   = select.options[select.selectedIndex];
    const svcId = opt.value;

    if (!svcId) return;

    // Fetch service_items for this service
    const container = row.querySelector('.s_sub_tbody');
    const loading   = row.querySelector('.s_loading');
    container.innerHTML = '';
    loading.style.display = 'flex';

    if (serviceItemsCache[svcId]) {
        loading.style.display = 'none';
        renderServiceSubItems(container, serviceItemsCache[svcId], svcId);
        return;
    }

    fetch(`/estimator/service/${svcId}/items`)
        .then(r => r.json())
        .then(data => {

            loading.style.display = 'none';
            const items = data.items ?? data ?? [];
            serviceItemsCache[svcId] = items;
            renderServiceSubItems(container, items, svcId);
        })
        .catch(() => {
            loading.style.display = 'none';
            const blankBtn = row.querySelector('.s_sub_items_wrap button');
            addBlankSubItem(blankBtn);
        });
}

/* Render all fetched service_items as sub-item rows */
function renderServiceSubItems(container, items, svcId) {
    container.innerHTML = '';
    if (!items.length) {
        // No predefined items — one blank card
        const card = addServiceItemRow(container, {});
        populateItemDropdown(card.querySelector('.si_item_select'), svcId, null);
        renumberSubRows(container);
        return;
    }
    function renderServiceSubItems(container, items, svcId) {
    container.innerHTML = '';

    // 👉 Always create ONLY ONE row
    const firstItem = items.length ? items[0] : {};

    const card = addServiceItemRow(container, firstItem);

    // 👉 Populate dropdown (no pre-selection)
    populateItemDropdown(
        card.querySelector('.si_item_select'),
        svcId,
        null
    );

    renumberSubRows(container);
}
    renumberSubRows(container);
}

function removeSubItemRow(btn) {
    const card      = btn.closest('.si-card');
    const container = card.parentElement;
    card.remove();
    renumberSubRows(container);
}

function renumberSubRows(container) {
    container.querySelectorAll('.si-card').forEach((card, i) => {
        const numEl   = card.querySelector('.si-card-num');
        const titleEl = card.querySelector('.si-card-title');
        if (numEl)   numEl.textContent   = i + 1;
        if (titleEl) titleEl.textContent = `Sub-Item ${i + 1}`;
    });
}

function addServiceItem() {
    const wrap = document.getElementById('services_wrapper');
    const idx  = wrap.querySelectorAll('.service-item').length + 1;
    const row  = buildServiceRow(idx);
    wrap.appendChild(row);
    // tbody starts empty — user picks service first
}

function removeServiceItem(btn) {
    btn.closest('.service-item').remove();
    document.querySelectorAll('.service-item')
        .forEach((r, i) => r.querySelector('.item-num').textContent = `Service #${i+1}`);
}

/* Helper: escape HTML for use in innerHTML */
function escHtml(str) {
    return String(str).replace(/&/g,'&amp;').replace(/"/g,'&quot;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
}

/* ── SUBMIT ── */
function submitQuotation() {
    const quotationNo = document.getElementById('quotationNumber').value;
    const quotationId = document.getElementById('quotationId').value;
    const validItems  = [];


    document.querySelectorAll('.quotation-item').forEach(row => {
        const isCustom = row.querySelector('.custom_toggle')?.checked;
        const itemSelect = row.querySelector('.q_item');
        const area = getAreaSqft(row);
        if (isCustom) {
            const n = row.querySelector('.q_custom_name').value.trim();
            if (!n) return;
            validItems.push({
                item_id:'', custom_name:n,
                description:row.querySelector('.q_description')?.value||'',
                category:row.querySelector('.q_custom_category')?.value||'',
                gst_percentage:row.querySelector('.q_custom_gst')?.value||0,
                quantity:row.querySelector('.q_quantity')?.value||1,
                unit:row.querySelector('.q_unit')?.value||'',
                price:row.querySelector('.q_price')?.value||0,
                length:row.querySelector('.q_length')?.value||'',
                breadth:row.querySelector('.q_breadth')?.value||'',
                area,
            });
        } else {
            if (!itemSelect.value) return;
            validItems.push({
                item_id:itemSelect.value, custom_name:'',
                description:row.querySelector('.q_description').value,
                category:row.querySelector('.q_category_display')?.value||'',
                quantity:row.querySelector('.q_quantity').value,
                unit:row.querySelector('.q_unit').value,
                price:row.querySelector('.q_price').value,
                length:row.querySelector('.q_length')?.value||'',
                breadth:row.querySelector('.q_breadth')?.value||'',
                area,
                gst_percentage:row.querySelector('.q_gst')?.value||0,
            });
        }
    });

    const serviceItems = [];
    if (true) { // services always active
        document.querySelectorAll('.service-item').forEach(row => {
            const svcId = row.querySelector('.s_service').value;
            if (!svcId) return;
            const subItems = [];
            const accessories = [];

            row.querySelectorAll('.s_sub_tbody .si-card').forEach(tr => {

                // Read item name: prefer hidden .si_name (set by populateItemDropdown / onSubItemSelect)
                const nameEl  = tr.querySelector('.si_name');
                const selEl   = tr.querySelector('.si_item_select');
                let name = '';
                if (nameEl && nameEl.value?.trim()) {
                    name = nameEl.value.trim();
                } else if (selEl && selEl.value && selEl.value !== '__custom__') {
                    name = selEl.options[selEl.selectedIndex]?.textContent?.trim() || '';
                }
                if (!name) return;
                subItems.push({
                    name,
                    material:    tr.querySelector('.si_material')?.value  || '',
                    unit:        tr.querySelector('.si_unit')?.value      || '',
                    size:        tr.querySelector('.si_size')?.value      || '',
                    mrp:         tr.querySelector('.si_mrp')?.value       || 0,
                    offer_price: tr.querySelector('.si_offer')?.value     || 0,
                    qty:         tr.querySelector('.si_qty')?.value       || 1,
                });
            });

            // ✅ FIX: Accessories collect ചെയ്യുന്നു
            row.querySelectorAll('.accessories_wrap .si-card').forEach(acc => {
                const itemSelect = acc.querySelector('.acc_item');

                if (!itemSelect || !itemSelect.value) return;
                const fileInput = acc.querySelector('.acc_image');

                accessories.push({
                    item_id: itemSelect.value,
                    name:    itemSelect.options[itemSelect.selectedIndex]?.text || '',
                    size:    acc.querySelector('.acc_size')?.value  || '',
                    unit:    acc.querySelector('.acc_unit')?.value  || '',
                    qty:    acc.querySelector('.acc_qty')?.value  || '',
                    price:   acc.querySelector('.acc_price')?.value || 0,
                     offer_price: acc.querySelector('.acc_offer')?.value || 0,
                    image: fileInput?.files[0] || null
                });
            });

            serviceItems.push({
                service_id:  svcId,
                note:        row.querySelector('.s_note')?.value||'',
                sub_items:   subItems,
                accessories: accessories,
            });
        });
    }

    if (validItems.length===0 && serviceItems.length===0) {
        alert('Please add at least one item or service.'); return;
    }

    const formData = new FormData();
    formData.append('lead_id', '{{ $lead->id }}');
    formData.append('quotation_no', quotationNo);
    formData.append('quotation_id', quotationId);

    validItems.forEach((item,i)=>{
        Object.entries(item).forEach(([k,v])=>formData.append(`items[${i}][${k}]`,v));
    });

    serviceItems.forEach((s,i)=>{
        formData.append(`services[${i}][service_id]`, s.service_id);
        formData.append(`services[${i}][note]`,       s.note ?? '');
        // price/gst/tax/total — fetch from allServices data
        const svcData = allServices.find(sv => String(sv.id) === String(s.service_id));
        const price   = parseFloat(svcData?.price           ?? 0);
        const gst     = parseFloat(svcData?.gst_percentage  ?? 0);
        const tax     = parseFloat(svcData?.service_tax     ?? 0);
        const gstAmt  = (price * gst) / 100;
        const total   = price + gstAmt + tax;
        formData.append(`services[${i}][price]`,      price.toFixed(2));
        formData.append(`services[${i}][gst]`,        gst);
        formData.append(`services[${i}][tax]`,        tax.toFixed(2));
        formData.append(`services[${i}][gst_amount]`, gstAmt.toFixed(2));
        formData.append(`services[${i}][total]`,      total.toFixed(2));

        s.sub_items.forEach((si,j)=>{
            Object.entries(si).forEach(([k,v])=>formData.append(`services[${i}][sub_items][${j}][${k}]`,v));
        });

        // ✅ FIX: Accessories FormData-ൽ append ചെയ്യുന്നു
        s.accessories.forEach((acc, j) => {
            formData.append(`services[${i}][accessories][${j}][item_id]`, acc.item_id);
            formData.append(`services[${i}][accessories][${j}][name]`,    acc.name);
            formData.append(`services[${i}][accessories][${j}][size]`,    acc.size);
            formData.append(`services[${i}][accessories][${j}][unit]`,    acc.unit);
            formData.append(`services[${i}][accessories][${j}][qty]`,    acc.qty);
            formData.append(`services[${i}][accessories][${j}][price]`,   acc.price);
            formData.append(`services[${i}][accessories][${j}][offer_price]`,   acc.offer_price);
            if (acc.image) {
        formData.append(`services[${i}][accessories][${j}][image]`, acc.image);
    }
        });
    });

    fetch('{{ route("estimator.quotation.store") }}', {
        method:'POST',
        headers:{'X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content},
        body:formData
    })
    .then(r=>r.json())
    .then(data=>{
        if (data.success) {
            const pdfBtn = document.getElementById('pdf_after_save');
            if (pdfBtn) { pdfBtn.href='/estimator/estimation/'+data.estimation_id+'/pdf'; pdfBtn.classList.remove('hidden'); }
            alert('Estimation saved! Click "Generate PDF" to download.');
        } else { alert(data.message); }
    })
    .catch(err=>alert('Fetch error: '+err.message));
}

/* ── LOAD EXISTING ITEMS ── */
// ── REPLACE the entire window.addEventListener('DOMContentLoaded', ...) block with this ──

window.addEventListener('DOMContentLoaded', function () {

    if (document.getElementById('services_wrapper').querySelectorAll('.service-item').length === 0) {
        addServiceItem();
    }

    // ── Load existing services ──
    if (Array.isArray(existingServices) && existingServices.length > 0) {
        const wrap = document.getElementById('services_wrapper');
        wrap.innerHTML = ''; // clear default empty row

        existingServices.forEach((svc, idx) => {
            const row = buildServiceRow(idx + 1);
            wrap.appendChild(row);

            // Set service dropdown value
            const sel = row.querySelector('.s_service');
            if (sel) sel.value = svc.service_id;

            // Set note
            const noteEl = row.querySelector('.s_note');
            if (noteEl) noteEl.value = svc.note ?? '';

            // Cache service items & render sub-items
            const container = row.querySelector('.s_sub_tbody');
            serviceItemsCache[svc.service_id] = serviceItemsCache[svc.service_id] || [];

            container.innerHTML = '';

            svc.sub_items.forEach((si, j) => {
                const card = addServiceItemRow(container, {
                    item_name:     si.name     ?? '',
                    material:      si.material ?? '',
                    default_price: si.mrp      ?? 0,
                });

                populateItemDropdown(card.querySelector('.si_item_select'), svc.service_id, null);

                const nameEl = card.querySelector('.si_name');
                if (nameEl) nameEl.value = si.name ?? '';

                // Replace select with text input showing saved name
                const selEl = card.querySelector('.si_item_select');
                if (selEl && si.name) {
                    const nameDisplay = document.createElement('input');
                    nameDisplay.type        = 'text';
                    nameDisplay.className   = 'si-input si_name';
                    nameDisplay.value       = si.name;
                    nameDisplay.placeholder = 'Item name';
                    selEl.parentNode.replaceChild(nameDisplay, selEl);
                }

                const matEl = card.querySelector('.si_material');
                if (matEl) matEl.value = si.material ?? '';

                const qtyEl = card.querySelector('.si_qty');
if (qtyEl) qtyEl.value = si.qty ?? 1;

                const unitEl = card.querySelector('.si_unit');
                if (unitEl) unitEl.value = si.unit ?? '';

                const sizeEl = card.querySelector('.si_size');
                if (sizeEl) sizeEl.value = si.size ?? '';

                const mrpEl = card.querySelector('.si_mrp');
                if (mrpEl) mrpEl.value = si.mrp ?? '';

                const offerEl = card.querySelector('.si_offer');
                if (offerEl) offerEl.value = si.offer_price ?? '';
            });

            renumberSubRows(container);

            // ✅ FIX: Load accessories — INSIDE the forEach, after renumberSubRows
            if (Array.isArray(svc.accessories) && svc.accessories.length > 0) {

                const accToggle = row.querySelector('.acc_toggle');
                const accWrap   = row.querySelector('.accessories_wrap');
                const accBtn    = row.querySelector('.acc_add_btn');

                if (accToggle) accToggle.checked = true;
                if (accWrap)   accWrap.classList.remove('hidden');
                if (accBtn)    accBtn.classList.remove('hidden');

                accWrap.innerHTML = '';

                svc.accessories.forEach((acc) => {
                    const div = document.createElement('div');
                    div.className = 'si-card';

                    div.innerHTML = `
                        <div class="si-card-header">
                            <span class="si-card-title">Accessory</span>
                            <button type="button" onclick="this.closest('.si-card').remove()" class="si-remove-btn">×</button>
                        </div>
                        <div class="si-grid">
                            <div class="si-span2">
                                <label class="si-label">Item Name</label>
                                <select class="si-select acc_item" onchange="setAccessoryPrice(this)">
                                    <option value="">-- Select Item --</option>
                                    ${allInventoryItems.map(i => `
                                        <option value="${i.id}" data-price="${i.price}"
                                            ${String(i.id) === String(acc.item_id) ? 'selected' : ''}>
                                            ${i.item_name}
                                        </option>
                                    `).join('')}
                                </select>
                            </div>

                            <div>
                                <label class="si-label">Size</label>
                                <input type="text" class="si-input acc_size"
                                    value="${escHtml(acc.size ?? '')}">
                            </div>

                            <div>
                                <label class="si-label">Qty</label>
                                <input type="number" class="si-input acc_qty"
                                    value="${escHtml(acc.qty ?? '')}">
                            </div>

                            <div>
                                <label class="si-label">Unit</label>
                                <input type="text" class="si-input acc_unit"
                                    value="${escHtml(acc.unit ?? '')}">
                            </div>

                            <div>
                                <label class="si-label">Unit Price</label>
                                <input type="number" class="si-input acc_price"
                                    value="${acc.price ?? ''}" readonly>
                            </div>
                            <div>
                                <label class="si-label">Offer Price</label>
                            <input type="number" class="si-input acc_offer"
    value="${acc.offer_price ?? ''}">
     </div>

                            <div class="si-span2">
                                <label class="si-label">Upload Image</label>
                                <input type="file" class="si-input acc_image" accept="image/*">
                                ${acc.image
                                    ? `<div style="margin-top:6px;">
                                        <img src="/${acc.image}"
                                            style="height:60px;border-radius:6px;border:1px solid #ddd6fe;"
                                            alt="Accessory image">
                                       </div>`
                                    : ''}
                            </div>
                        </div>
                    `;

                    accWrap.appendChild(div);
                });
            }
            // ✅ End of accessories block — still inside existingServices.forEach
        }); // ← closes existingServices.forEach
    }

    // ── Load existing general items ──
    let existing = existingItems;
    if (typeof existing === 'string') { try { existing = JSON.parse(existing); } catch(e) { existing = []; } }
    if (!Array.isArray(existing) || existing.length === 0) return;

    const wrapper = document.getElementById('quotation_items_wrapper');
    if (!wrapper) return;
    wrapper.innerHTML = '';
    existing.forEach((item, index) => {
        addMoreQuotationItem();
        const row = wrapper.querySelectorAll('.quotation-item')[index];
        if (item.custom_name && item.custom_name.trim() !== '') {
            const t = row.querySelector('.custom_toggle'); t.checked = true; toggleCustomItem(t);
            row.querySelector('.q_custom_name').value = item.custom_name || '';
            row.querySelector('.q_custom_price').value = item.price || '';
            row.querySelector('.q_price').value = item.price || '';
            const cc = row.querySelector('.q_custom_category'); if (cc) cc.value = item.category || '';
            const cg = row.querySelector('.q_custom_gst'); if (cg) cg.value = item.gst_percentage || 0;
        } else if (item.item_id) {
            const sel = row.querySelector('.q_item'); sel.value = item.item_id;
            const cd = row.querySelector('.q_category_display');
            if (cd && item.category) { cd.value = item.category; row.querySelector('.q_category_row').classList.remove('hidden'); }
            row.querySelector('.q_price').value = item.price || '';
            const gi = row.querySelector('.q_gst'); if (gi) gi.value = item.gst_percentage || 0;
        }
        row.querySelector('.q_description').value = item.description || '';
        row.querySelector('.q_quantity').value = item.quantity || 1;
        const us = row.querySelector('.q_unit'); if (us) us.value = item.unit || '';
    });
    updateItemCount();
});

    // ── Load existing services ──


document.addEventListener('input', function(e) {
    if (e.target.classList.contains('q_custom_name')) {
        const name=e.target.value; if(name.length<2) return;
        const row=e.target.closest('.quotation-item');
        fetch(`/estimator/item-details?item_name=${name}`)
            .then(r=>r.json())
            .then(d=>{
                if(d.success){
                    row.querySelector('.q_custom_category').value=d.category||'';
                    row.querySelector('.q_unit').value=d.unit||'';
                    row.querySelector('.q_price').value=d.price||'';
                    row.querySelector('.q_gst').value=d.gst_percentage||'';
                }
            });
    }
});

function toggleAccessories(cb) {
    const service = cb.closest('.service-item');
    const wrap = service.querySelector('.accessories_wrap');
    const btn  = service.querySelector('.acc_add_btn');

    if (cb.checked) {
        wrap.classList.remove('hidden');
        btn.classList.remove('hidden');

        if (!wrap.children.length) {
            addAccessoryRow(btn); // add first row
        }
    } else {
        wrap.innerHTML = '';
        wrap.classList.add('hidden');
        btn.classList.add('hidden');
    }
}

function addAccessoryRow(btn) {
    const container = btn.closest('.service-item').querySelector('.accessories_wrap');

    const div = document.createElement('div');
    div.className = 'si-card';

    div.innerHTML = `
        <div class="si-card-header">
            <span class="si-card-title">Accessory</span>
            <button type="button" onclick="this.closest('.si-card').remove()"
                class="si-remove-btn">×</button>
        </div>

        <div class="si-grid">

            <!-- Item Name -->
            <div class="si-span2">
                <label class="si-label">Item Name</label>
                <select class="si-select acc_item" onchange="setAccessoryPrice(this)">
                    <option value="">-- Select Item --</option>
                    ${allInventoryItems.map(i => `
                        <option value="${i.id}" data-price="${i.price}">
                            ${i.item_name}
                        </option>
                    `).join('')}
                </select>
            </div>

            <!-- Size -->
            <div>
                <label class="si-label">Size</label>
                <input type="text" class="si-input acc_size" placeholder="e.g. 12x12">
            </div>

            <!-- qty -->
            <div>
                <label class="si-label">Qty</label>
                <input type="number" class="si-input acc_qty">
            </div>

            <!-- Unit -->
            <div>
                <label class="si-label">Unit</label>
                <input type="text" class="si-input acc_unit" placeholder="Nos / Set">
            </div>

            <!-- Price -->
            <div>
                <label class="si-label">Unit Price</label>
                <input type="number" class="si-input acc_price" readonly>
            </div>
            <!-- offer -->
            <div>
    <label class="si-label">Offer Price</label>
    <input type="number" class="si-input acc_offer" placeholder="Enter offer price">
</div>

            <!-- Image -->
            <div class="si-span2">
                <label class="si-label">Upload Image</label>
                <input type="file" class="si-input acc_image" accept="image/*">
            </div>

        </div>
    `;

    container.appendChild(div);
}

function setAccessoryPrice(select) {
    const opt = select.options[select.selectedIndex];
    const card = select.closest('.si-card');

    if (opt && opt.dataset.price) {
        card.querySelector('.acc_price').value = opt.dataset.price;
    }
}
</script>

@endsection
