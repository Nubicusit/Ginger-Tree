<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{ $quotation->quotation_no ?? 'Quotation' }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&family=Cormorant+Garamond:wght@300;400;500;600&family=DM+Sans:wght@300;400;500;600&display=swap');

        /* ── COLOUR TOKENS ── */
        :root {
            --gold:        #8b6f2e;   /* primary accent — dark warm gold */
            --gold-light:  #c4a256;   /* mid tone for sub-accents        */
            --gold-pale:   #f5efe0;   /* tinted background panels        */
            --gold-rule:   #d4b87a;   /* divider / border lines          */
            --ink:         #1c1810;   /* near-black body text            */
            --ink-mid:     #4a4030;   /* secondary text                  */
            --ink-soft:    #7a6e5a;   /* muted labels                    */
            --paper:       #ffffff;
            --paper-tint:  #fdf9f2;   /* warm off-white rows             */
        }

        @page { size: A4; margin: 18mm 15mm 20mm 15mm; }
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'DM Sans', Arial, sans-serif;
            font-size: 11px;
            line-height: 1.55;
            color: var(--ink);
            background: var(--paper);
            max-width: 190mm;
            margin: 0 auto;
        }

        /* ── HEADER ── */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding-bottom: 20px;
            margin-bottom: 26px;
            border-bottom: 1px solid var(--gold-rule);
            position: relative;
        }

        /* thin decorative top-rule */
        .header::before {
            content: '';
            position: absolute;
            top: -8px; left: 0; right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--gold) 0%, var(--gold-light) 50%, var(--gold) 100%);
        }

        .logo-wrap {
            width: 66px; height: 54px;
            border: 1px solid var(--gold-rule);
            border-radius: 4px;
            display: flex; align-items: center; justify-content: center;
            overflow: hidden;
            background: var(--paper-tint);
            margin-bottom: 10px;
        }

        .logo-wrap img { max-width: 62px; max-height: 50px; object-fit: contain; }

        .company-name {
            font-family: 'Playfair Display', Georgia, serif;
            font-size: 22px; font-weight: 700;
            color: var(--ink);
            letter-spacing: 0.4px;
        }

        .company-tagline {
            font-family: 'Cormorant Garamond', Georgia, serif;
            font-size: 11px; font-weight: 400;
            font-style: italic;
            color: var(--gold);
            letter-spacing: 1.5px;
            margin-top: 3px;
        }

        .header-right { text-align: right; }

        .doc-label {
            font-family: 'Playfair Display', Georgia, serif;
            font-size: 32px; font-weight: 700;
            color: var(--gold);
            letter-spacing: 2px;
            text-transform: uppercase;
            line-height: 1;
        }

        .doc-ornament {
            font-family: 'Cormorant Garamond', Georgia, serif;
            font-size: 11px;
            color: var(--gold-rule);
            letter-spacing: 3px;
            margin-top: 4px;
        }

        .company-contact {
            font-size: 10px;
            color: var(--ink-soft);
            margin-top: 10px;
            line-height: 1.9;
        }

        /* ── META ROW ── */
        .meta-row {
            display: flex;
            margin-bottom: 24px;
            border: 1px solid var(--gold-rule);
            border-radius: 3px;
            overflow: hidden;
        }

        .meta-block {
            flex: 1;
            padding: 13px 18px;
            border-right: 1px solid var(--gold-rule);
            background: var(--paper);
        }

        .meta-block:last-child { border-right: none; }

        .meta-block.accent { background: var(--gold); }

        .meta-key {
            font-size: 8.5px; font-weight: 600;
            text-transform: uppercase; letter-spacing: 1.2px;
            color: var(--ink-soft);
            margin-bottom: 5px;
        }

        .meta-block.accent .meta-key { color: #e8d8b0; }

        .meta-val {
            font-family: 'DM Sans', Arial, sans-serif;
            font-size: 14px; font-weight: 600;
            color: var(--ink);
        }

        .meta-block.accent .meta-val { color: #fff8ec; }

        /* ── BILL TO ── */
        .bill-section { margin-bottom: 24px; }

        .bill-to {
            display: inline-block;
            min-width: 54%;
            background: var(--gold-pale);
            border-left: 3px solid var(--gold);
            padding: 14px 20px;
        }

        .bill-label {
            font-size: 8.5px; font-weight: 600;
            text-transform: uppercase; letter-spacing: 1.5px;
            color: var(--gold);
            margin-bottom: 7px;
        }

        .client-name {
            font-family: 'Playfair Display', Georgia, serif;
            font-size: 17px; font-weight: 600;
            color: var(--ink);
            margin-bottom: 5px;
        }

        .client-details { font-size: 11px; color: var(--ink-mid); line-height: 1.7; }

        /* ── ITEMS TABLE ── */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid var(--gold-rule);
        }

        .items-table thead tr { background: var(--gold); }

        .items-table th {
            padding: 12px 13px;
            font-size: 9px; font-weight: 600;
            text-transform: uppercase; letter-spacing: 1px;
            color: #fff5dc;
            text-align: left;
        }

        .items-table th.r, .items-table td.r { text-align: right; }

        .items-table tbody tr { border-bottom: 1px solid #ede5d0; }
        .items-table tbody tr:nth-child(even) { background: var(--paper-tint); }
        .items-table tbody tr:last-child { border-bottom: none; }

        .items-table td { padding: 12px 13px; vertical-align: middle; }

        .row-num { font-size: 10px; color: var(--gold-light); font-weight: 600; }

        .item-name {
            font-family: 'Playfair Display', Georgia, serif;
            font-weight: 600; font-size: 11.5px;
            color: var(--ink);
        }

        .item-desc { font-size: 10px; color: var(--ink-soft); margin-top: 2px; }

        .img-cell { text-align: center; }

        .img-wrap {
            width: 44px; height: 36px;
            border: 1px solid var(--gold-rule);
            border-radius: 3px;
            overflow: hidden;
            display: flex; align-items: center; justify-content: center;
            background: var(--gold-pale);
            margin: 0 auto;
        }

        .img-wrap img { max-width: 42px; max-height: 34px; object-fit: cover; }
        .no-img { font-size: 8px; color: var(--gold-rule); }

        /* ── TOTALS ── */
        .bottom-section { display: flex; justify-content: flex-end; margin-top: 20px; }

        .totals-wrap { width: 260px; }

        .totals-inner {
            border: 1px solid var(--gold-rule);
            overflow: hidden;
        }

        .totals-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 16px;
            border-bottom: 1px solid #ede5d0;
            background: var(--paper);
        }

        .totals-row:last-child { border-bottom: none; background: var(--gold); }

        .t-label { font-size: 11px; color: var(--ink-soft); font-weight: 500; }
        .t-val   { font-size: 11px; color: var(--ink); font-weight: 600; }

        .totals-row.grand .t-label,
        .totals-row.grand .t-val {
            font-family: 'DM Sans', Arial, sans-serif;
            color: #fff8ec;
            font-size: 14px; font-weight: 700;
        }

        /* ── FOOTER ── */
        .footer-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-top: 44px;
            padding-top: 18px;
            border-top: 1px solid var(--gold-rule);
            position: relative;
        }

        /* thin double rule effect */
        .footer-row::before {
            content: '';
            position: absolute;
            top: 3px; left: 0; right: 0;
            height: 1px;
            background: var(--gold-pale);
        }

        .terms { font-size: 10px; color: var(--ink-soft); max-width: 240px; line-height: 1.65; }

        .terms strong {
            display: block;
            font-size: 8.5px;
            text-transform: uppercase; letter-spacing: 1px;
            color: var(--gold);
            margin-bottom: 4px;
        }

        .sig-block { text-align: center; width: 180px; }

        .sig-line { border-top: 1px solid var(--ink); margin-bottom: 8px; }

        .sig-name { font-size: 11px; font-weight: 600; color: var(--ink); }

        .sig-company {
            font-family: 'Cormorant Garamond', Georgia, serif;
            font-style: italic;
            font-size: 10px; color: var(--gold);
            margin-top: 2px; letter-spacing: 0.3px;
        }

        @media print {
            body { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        }
    </style>
</head>
<body>

@php
    $items = is_array($quotation->items) ? $quotation->items : json_decode($quotation->items, true) ?? [];
    $subtotal = collect($items)->sum(fn($i) => floatval($i['total'] ?? 0));
    $gst = $subtotal * 0.18;
    $grandTotal = $subtotal + $gst;
@endphp

<!-- HEADER -->
<div class="header">
    <div class="header-left">
        <div class="logo-wrap">
            @if(file_exists(public_path('img/gingertree-white-logo.png')))
                <img src="{{ asset('img/gingertree-white-logo.png') }}" alt="Logo">
            @else
                <span style="color:#c4a256;font-size:9px;">LOGO</span>
            @endif
        </div>
        <div class="company-name">Gingertree Interiors</div>
        <div class="company-tagline">Premium Interior Solutions</div>
    </div>
    <div class="header-right">
        <div class="doc-label">Quotation</div>
        <div class="doc-ornament">― ✦ ―</div>
        <div class="company-contact">
            C-309, Third Floor, Block-5, M3M ABC<br>
            Sector-67, Gurugram — 122001<br>
            +91 89203 80418 &nbsp;·&nbsp; contact@gingertreeinteriors.com
        </div>
    </div>
</div>

<!-- META ROW -->
<div class="meta-row">
    <div class="meta-block accent">
        <div class="meta-key">Quotation No.</div>
        <div class="meta-val">{{ $quotation->quotation_no }}</div>
    </div>
    <div class="meta-block">
        <div class="meta-key">Date Issued</div>
        <div class="meta-val">{{ \Carbon\Carbon::parse($quotation->created_at)->format('d M Y') }}</div>
    </div>
    <div class="meta-block">
        <div class="meta-key">Valid Until</div>
        <div class="meta-val">{{ \Carbon\Carbon::parse($quotation->created_at)->addDays(30)->format('d M Y') }}</div>
    </div>
    <div class="meta-block accent">
        <div class="meta-key">Status</div>
        <div class="meta-val">Draft</div>
    </div>
</div>

<!-- BILL TO -->
<div class="bill-section">
    <div class="bill-to">
        <div class="bill-label">Bill To</div>
        <div class="client-name">{{ $quotation->lead->client_name ?? 'Customer Name' }}</div>
        <div class="client-details">
            @if($quotation->lead->phone ?? null){{ $quotation->lead->phone }}<br>@endif
            @if($quotation->lead->location ?? null){{ $quotation->lead->location }}@endif
        </div>
    </div>
</div>

<!-- ITEMS TABLE -->
<table class="items-table">
    <thead>
        <tr>
            <th style="width:4%">#</th>
            <th style="width:15%">Item</th>
            <th>Description</th>
            <th style="width:8%;text-align:center">Image</th>
            <th class="r" style="width:7%">Qty</th>
            <th class="r" style="width:12%">Rate (₹)</th>
            <th class="r" style="width:13%">Total (₹)</th>
        </tr>
    </thead>
    <tbody>
        @forelse($items as $index => $item)
        <tr>
            <td><span class="row-num">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span></td>
            <td>
                <div class="item-name">{{ \App\Models\InventoryStock::find($item['item_id'] ?? 0)?->item_name ?? 'N/A' }}</div>
            </td>
            <td><span class="item-desc">{{ $item['description'] ?? '—' }}</span></td>
            <td class="img-cell">
                <div class="img-wrap">
                    @if(!empty($item['image']))
                        @if(file_exists(public_path("quotations/{$item['image']}")))
                            <img src="{{ asset("quotations/{$item['image']}") }}" alt="Item">
                        @elseif(file_exists(public_path($item['image'])))
                            <img src="{{ asset($item['image']) }}" alt="Item">
                        @else
                            <span class="no-img">—</span>
                        @endif
                    @else
                        <span class="no-img">—</span>
                    @endif
                </div>
            </td>
            <td class="r" style="font-weight:600">{{ $item['quantity'] ?? '—' }}</td>
            <td class="r">₹{{ number_format($item['price'] ?? 0, 2) }}</td>
            <td class="r" style="font-weight:700">₹{{ number_format($item['total'] ?? 0, 2) }}</td>
        </tr>
        @empty
        <tr>
            <td colspan="7" style="text-align:center;padding:36px;color:#a89870;">No items in this quotation.</td>
        </tr>
        @endforelse
    </tbody>
</table>

<!-- TOTALS -->
<div class="bottom-section">
    <div class="totals-wrap">
        <div class="totals-inner">
            <div class="totals-row">
                <span class="t-label">Subtotal</span>
                <span class="t-val">₹{{ number_format($subtotal, 2) }}</span>
            </div>
            <div class="totals-row">
                <span class="t-label">GST (18%)</span>
                <span class="t-val">₹{{ number_format($gst, 2) }}</span>
            </div>
            <div class="totals-row grand">
                <span class="t-label">Grand Total</span>
                <span class="t-val">₹{{ number_format($grandTotal, 2) }}</span>
            </div>
        </div>
    </div>
</div>

<!-- FOOTER -->
<div class="footer-row">
    <div class="terms">
        <strong>Terms &amp; Conditions</strong>
        Prices are valid for 30 days from the date of issue. Delivery timeline will be confirmed upon order placement. All disputes subject to Gurugram jurisdiction.
    </div>
    <div class="sig-block">
        <div class="sig-line"></div>
        <div class="sig-name">Authorized Signatory</div>
        <div class="sig-company">Gingertree Interiors</div>
    </div>
</div>

<script>
window.onload = function () {
    setTimeout(() => {
        window.print();
        window.onafterprint = () => window.close();
    }, 1000);
};
</script>

</body>
</html>