<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Quotation {{ $quotationNo }}</title>
<style>
* { margin: 0; padding: 0; box-sizing: border-box; }

body {
    font-family: DejaVu Sans, sans-serif;
    background: #ffffff;
    color: #333;
    font-size: 12px;
    width: 794px;
}

/* =====================
   HEADER
   ===================== */
.header-outer {
    width: 100%;
    border-collapse: collapse;
}
.header-black {
    background-color: #111111;
    width: 42%;
    padding: 22px 20px 22px 26px;
    vertical-align: middle;
}
.header-orange {
    background-color: #F5A623;
    width: 58%;
    padding: 10px 22px 8px 28px;
    vertical-align: middle;
}
.company-logo-text {
    color: #F5A623;
    font-size: 21px;
    font-weight: 900;
    letter-spacing: 1px;
    line-height: 1.2;
}
.company-sub {
    color: #cccccc;
    font-size: 8px;
    letter-spacing: 2.5px;
    text-transform: uppercase;
    margin-top: 3px;
}
.website-line {
    text-align: right;
    font-size: 8px;
    color: #333333;
    font-weight: 500;
    letter-spacing: 0.4px;
    padding-bottom: 6px;
}
.invoice-big-word {
    text-align: right;
    font-size: 38px;
    font-weight: 900;
    color: #ffffff;
    letter-spacing: 5px;
    text-transform: uppercase;
    line-height: 1;
    padding-bottom: 2px;
}
.header-orange-strip {
    background: #F5A623;
    height: 18px;
}
.header-white-gap {
    background: #ffffff;
    height: 5px;
}

/* =====================
   BODY
   ===================== */
.body {
    padding: 22px 30px 130px 30px;
}

/* Info section */
.info-outer {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 22px;
}
.label-small {
    font-size: 9px;
    font-weight: 700;
    color: #555555;
    letter-spacing: 0.6px;
    text-transform: uppercase;
    margin-bottom: 3px;
}
.client-name-big {
    font-size: 18px;
    font-weight: 900;
    color: #F5A623;
    margin-bottom: 5px;
    line-height: 1.2;
}
.client-detail {
    font-size: 11px;
    color: #333333;
    line-height: 1.9;
}
.inv-no-line {
    font-size: 12px;
    font-weight: 500;
    color: #444444;
    text-align: right;
    margin-bottom: 10px;
}
.inv-no-line strong {
    font-size: 16px;
    font-weight: 900;
    color: #111111;
}
.inv-date-lines {
    font-size: 11px;
    color: #444444;
    text-align: right;
    line-height: 1.9;
}

/* =====================
   ITEMS TABLE
   ===================== */
.inv-table {
    width: 100%;
    border-collapse: collapse;
    border: 1.5px solid #bbbbbb;
}
.inv-table thead tr {
    background-color: #F5A623;
}
.inv-table thead th {
    color: #ffffff;
    font-size: 10px;
    font-weight: 800;
    letter-spacing: 0.8px;
    text-transform: uppercase;
    padding: 10px 10px;
    border-right: 1px solid rgba(255,255,255,0.35);
    text-align: left;
    line-height: 1.3;
}
.inv-table thead th:last-child { border-right: none; }
.inv-table thead th.c { text-align: center; }
.inv-table thead th.r { text-align: right; }

/* Column widths — 6 columns now */
.col-sl    { width: 40px; }
.col-desc  { width: auto; }
.col-unit  { width: 58px; }
.col-price { width: 82px; }
.col-qty   { width: 42px; }
.col-total { width: 82px; }

.inv-table tbody tr {
    border-bottom: 1.5px solid #111111;
}
.inv-table tbody tr:last-child { border-bottom: none; }
.inv-table tbody td {
    color: #111111;
    padding: 11px 10px;
    border-right: 1.5px solid #111111;
    vertical-align: top;
    font-size: 12px;
}
.inv-table tbody td:last-child { border-right: none; }
.inv-table tbody td.c { text-align: center; }
.inv-table tbody td.r { text-align: right; }
.inv-table tbody td.mid { vertical-align: middle; }

.item-name { font-weight: 700; }
.item-sub {
    font-size: 10px;
    color: #666666;
    margin-top: 2px;
    font-style: italic;
}
.item-measure {
    font-size: 9.5px;
    color: #999999;
    margin-top: 2px;
}
.unit-val {
    font-size: 11px;
    color: #444444;
    font-weight: 600;
    text-align: center;
    vertical-align: middle;
}

/* Signature */
.signature-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 24px;
}
.sig-note {
    font-size: 9.5px;
    color: #aaaaaa;
    font-style: italic;
    vertical-align: bottom;
}
.sig-block { text-align: right; vertical-align: bottom; }
.sig-line {
    border-top: 1.5px solid #333333;
    display: inline-block;
    width: 155px;
    padding-top: 5px;
    font-size: 11px;
    font-weight: 700;
    color: #333333;
    text-align: center;
}

/* =====================
   FOOTER — fixed at A4 page bottom
   ===================== */
.footer-wrap {
    position: fixed;
    bottom: 20px;
    left: 0;
    right: 0;
    padding: 0 30px 0 30px;
}
.footer-row {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 7px;
}
.footer-row td {
    font-size: 11px;
    font-weight: 600;
    color: #111111;
    vertical-align: middle;
    padding: 0;
}
.f-label-cell {
    width: 38px;
    font-size: 11px;
    padding-right: 8px;
    vertical-align: middle;
}
.f-label {
    display: inline-block;
    background: #F5A623;
    color: #ffffff;
    font-size: 8px;
    font-weight: 800;
    letter-spacing: 0.5px;
    text-transform: uppercase;
    padding: 2px 5px;
    border-radius: 2px;
}
.footer-bar {
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    height: 20px;
    background: #F5A623;
    width: 100%;
    margin-top: 14px;
}
</style>
</head>
<body>

{{-- ===================== HEADER ===================== --}}
<table class="header-outer" cellpadding="0" cellspacing="0" width="100%">
    <tr>
        <td class="header-black">
    @php
        $logoPath = public_path('img/gingertree-white-logo.png');
        $logoBase64 = '';
        if (file_exists($logoPath)) {
            $logoData = base64_encode(file_get_contents($logoPath));
            $mimeType = mime_content_type($logoPath);
            $logoBase64 = 'data:' . $mimeType . ';base64,' . $logoData;
        }
    @endphp

    @if($logoBase64)
        <img src="{{ $logoBase64 }}" style="max-height: 55px; max-width: 160px; object-fit: contain;">
    @else
        <div class="company-logo-text">Ginger Tree</div>
        <div class="company-sub">Interiors &amp; Design</div>
    @endif
</td>
        <td class="header-orange">
            <div class="website-line">www.GingerTree.com</div>
            <div class="invoice-big-word">QUOTATION</div>
        </td>
    </tr>
    <tr>
        <td colspan="2" class="header-orange-strip"></td>
    </tr>
    <tr>
        <td colspan="2" class="header-white-gap"></td>
    </tr>
</table>

{{-- ===================== BODY ===================== --}}
<div class="body">

    {{-- Client + Meta --}}
    <table class="info-outer" cellpadding="0" cellspacing="0">
        <tr>
            <td style="width:54%; vertical-align:top;">
                <div class="label-small">Quotation To:</div>
                <div class="client-name-big">{{ $lead->client_name }}</div>
                <div class="client-detail">
                    Phone: {{ $lead->phone }}<br>
                    @if(!empty($lead->project_address))
                        Address: {{ $lead->project_address }}<br>
                    @elseif(!empty($lead->location))
                        Location: {{ $lead->location }}<br>
                    @endif
                    @if(!empty($lead->email))
                        Email: {{ $lead->email }}
                    @endif
                </div>
            </td>
            <td style="width:46%; vertical-align:top;">
                <div class="inv-no-line">
                    QUOTATION NO: <strong>#&nbsp;{{ $quotationNo }}</strong>
                </div>
                <div class="inv-date-lines">
                    Quotation Date: {{ \Carbon\Carbon::now()->format('d M Y') }}<br>
                    @if(!empty($lead->project_type))
                        Project Type: {{ $lead->project_type }}<br>
                    @endif
                    @if(!empty($lead->expected_start_date))
                        Start Date: {{ \Carbon\Carbon::parse($lead->expected_start_date)->format('d M Y') }}
                    @endif
                </div>
            </td>
        </tr>
    </table>

    {{-- Items Table --}}
    @php
$grandTotal = 0;
$totalGST = 0;
@endphp

    <table class="inv-table" cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th class="col-sl c">SL<br>NO</th>
                <th class="col-desc">Description</th>
                <th class="col-unit c">Unit</th>
                <th class="col-price c">Price</th>
                <th class="col-qty c">Qty.</th>
                <th class="col-gst c">GST</th>
                <th class="col-total r">Total</th>
            </tr>
        </thead>
        <tbody>

            @foreach($items as $index => $item)
                @php
$qty   = floatval($item['quantity'] ?? 1);
$price = floatval($item['price'] ?? 0);

$gstRate = floatval($item['gst_percentage'] ?? 0);

$amount = $qty * $price;
$gstAmount = ($amount * $gstRate) / 100;

$total = $amount + $gstAmount;

$grandTotal += $total;
$totalGST += $gstAmount;
@endphp
                <tr>
                    {{-- SL No --}}
                    <td class="c mid">{{ $index + 1 }}</td>

                    {{-- Description --}}
                    <td>
                        <span class="item-name">{{ $item['item_name'] ?? 'N/A' }}</span>
                        @if(!empty($item['category']))
                            <div class="item-sub">{{ $item['category'] }}</div>
                        @endif
                        @if(!empty($item['description']))
                            <div class="item-sub">{{ $item['description'] }}</div>
                        @endif
                        @if(!empty($item['length']) && !empty($item['breadth']))
                            <div class="item-measure">
                                {{ $item['length'] }} ft x {{ $item['breadth'] }} ft
                                @if(!empty($item['area']) && floatval($item['area']) > 0)
                                    = {{ $item['area'] }} Sqft
                                @endif
                            </div>
                        @endif
                    </td>

                    {{-- Unit — dedicated column --}}
                    <td class="unit-val c mid">{{ !empty($item['unit']) ? $item['unit'] : '-' }}</td>

                    {{-- Price --}}
                    <td class="c mid">Rs. {{ number_format($price, 2) }}</td>

                    {{-- Qty --}}
                    <td class="c mid">{{ $qty % 1 == 0 ? intval($qty) : $qty }}</td>

                    <td class="c mid">
{{ $gstRate }}% <br>
</td>

                    {{-- Total --}}
                    <td class="r mid">Rs. {{ number_format($total, 2) }}</td>
                </tr>
            @endforeach

            {{-- TAX row — colspan adjusted to 6 columns --}}
            <tr>
                <td colspan="3" style="background:#ffffff; padding:10px 10px; border-right:1.5px solid #cccccc;"></td>
                <td colspan="3" style="background:#ffffff; padding:10px 10px; border-right:1.5px solid #111111; font-size:11px; font-weight:700; color:#333333; text-align:center; letter-spacing:0.4px;">TOTAL GST</td>
                <td style="background:#ffffff; padding:10px 10px; font-size:12px; font-weight:700; color:#222222; text-align:right;">{{ number_format($totalGST, 2) }}</td>
            </tr>

            {{-- GRAND TOTAL row — colspan adjusted to 6 columns --}}
            <tr>
                <td colspan="3" style="background:#ffffff; padding:13px 10px; border-right:1.5px solid #444444; font-size:9.5px; font-weight:800; color:#111111; letter-spacing:0.3px; text-transform:uppercase; vertical-align:middle;">
                    Thank you for your business with us!
                </td>
                <td colspan="3" style="background:#ffffff; padding:13px 10px; border-right:1.5px solid #444444; font-size:10.5px; font-weight:800; color:#111111; letter-spacing:0.4px; text-transform:uppercase; text-align:center; vertical-align:middle;">
                    Grand Total
                </td>
                <td style="background:#ffffff; padding:13px 10px; font-size:15px; font-weight:900; color:#111111; text-align:right; vertical-align:middle;">
                    Rs. {{ number_format($grandTotal, 2) }}
                </td>
            </tr>

        </tbody>
    </table>



</div>

{{-- ===================== FOOTER ===================== --}}
<div class="footer-wrap">

    <table class="footer-row" cellpadding="0" cellspacing="0">
        <tr>
            <td class="f-label-cell"><span class="f-label">Tel</span></td>
            <td>+91 7402 99 0000</td>
        </tr>
    </table>

    <table class="footer-row" cellpadding="0" cellspacing="0">
        <tr>
            <td class="f-label-cell"><span class="f-label">Mail</span></td>
            <td><a href="/cdn-cgi/l/email-protection" class="__cf_email__" data-cfemail="036e626a6f43646a6d646671777166662d6a6d">mail@gingertree.in
</a></td>
        </tr>
    </table>

    <table class="footer-row" cellpadding="0" cellspacing="0">
        <tr>
            <td class="f-label-cell"><span class="f-label">Addr</span></td>
            <td>Jawahar Rd, Near Gandhi Square, Poonithura, Petta,
            </td></tr></table></div>
            <div class="footer-bar"></div></body></html>
