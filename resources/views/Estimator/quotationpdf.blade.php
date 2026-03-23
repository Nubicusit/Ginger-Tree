<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Quotation {{ $quotationNo }}</title>
<style>
* { margin: 0; padding: 0; box-sizing: border-box; }
body { font-family: DejaVu Sans, sans-serif; background: #ffffff; color: #222; font-size: 11px; width: 794px; }
.header-outer { width: 100%; border-collapse: collapse; }
.header-black { background-color: #111111; width: 42%; padding: 22px 20px 22px 26px; vertical-align: middle; }
.header-orange { background-color: #F5A623; width: 58%; padding: 10px 22px 8px 28px; vertical-align: middle; }
.company-logo-text { color: #F5A623; font-size: 21px; font-weight: 900; letter-spacing: 1px; }
.company-sub { color: #cccccc; font-size: 8px; letter-spacing: 2.5px; text-transform: uppercase; margin-top: 3px; }
.website-line { text-align: right; font-size: 8px; color: #333; font-weight: 500; padding-bottom: 6px; }
.invoice-big-word { text-align: right; font-size: 38px; font-weight: 900; color: #ffffff; letter-spacing: 5px; text-transform: uppercase; line-height: 1; }
.header-orange-strip { background: #F5A623; height: 18px; }
.header-white-gap { background: #ffffff; height: 5px; }
.body { padding: 22px 30px 140px 30px; }
.info-outer { width: 100%; border-collapse: collapse; margin-bottom: 22px; }
.label-small { font-size: 9px; font-weight: 700; color: #555; letter-spacing: 0.6px; text-transform: uppercase; margin-bottom: 3px; }
.client-name-big { font-size: 18px; font-weight: 900; color: #F5A623; margin-bottom: 5px; }
.client-detail { font-size: 11px; color: #333; line-height: 1.9; }
.inv-no-line { font-size: 12px; font-weight: 500; color: #444; text-align: right; margin-bottom: 10px; }
.inv-no-line strong { font-size: 16px; font-weight: 900; color: #111; }
.inv-date-lines { font-size: 11px; color: #444; text-align: right; line-height: 1.9; }
/* Section header orange bar */
.section-hdr-tbl { width: 100%; border-collapse: collapse; margin-top: 14px; }
.section-hdr-tbl td { background-color: #F5A623; color: #ffffff; font-size: 11px; font-weight: 900; letter-spacing: 1.2px; text-transform: uppercase; padding: 8px 10px; }
/* Items table */
.inv-table { width: 100%; border-collapse: collapse; border: 1.5px solid #bbbbbb; }
.inv-table thead tr { background-color: #eeeeee; }
.inv-table thead th { color: #333; font-size: 9.5px; font-weight: 800; letter-spacing: 0.7px; text-transform: uppercase; padding: 8px; border-right: 1px solid #cccccc; border-bottom: 1.5px solid #aaaaaa; text-align: left; }
.inv-table thead th:last-child { border-right: none; }
.inv-table thead th.c { text-align: center; }
.inv-table thead th.r { text-align: right; }
.col-sl { width: 32px; } .col-item { width: auto; } .col-mat { width: 70px; } .col-unit { width: 44px; } .col-size { width: 72px; } .col-mrp { width: 66px; } .col-offer { width: 74px; }
.inv-table tbody tr { border-bottom: 1px solid #dddddd; }
.inv-table tbody tr.note-row td { background: #fffdf5; font-size: 10px; color: #555; font-style: italic; padding: 8px 8px 4px 8px; border-right: none; }
.inv-table tbody td { color: #222; padding: 9px 8px; border-right: 1px solid #dddddd; vertical-align: middle; font-size: 11px; }
.inv-table tbody td:last-child { border-right: none; }
.inv-table tbody td.c { text-align: center; } .inv-table tbody td.r { text-align: right; }
.item-name { font-weight: 700; } .item-sub { font-size: 9.5px; color: #777; margin-top: 1px; } .item-measure { font-size: 9px; color: #999; margin-top: 1px; }
.subtotal-row td { background: #f4f4f4; font-weight: 700; font-size: 11px; color: #111; padding: 9px 8px; border-top: 1.5px solid #aaa; border-right: 1px solid #ddd; }
.subtotal-row td:last-child { border-right: none; }
/* Totals */
.totals-table { width: 100%; border-collapse: collapse; margin-top: 16px; }
.totals-table td { padding: 6px 10px; font-size: 11px; color: #333; }
.t-label { text-align: right; font-weight: 600; width: 75%; }
.t-val { text-align: right; font-weight: 700; width: 25%; }
.totals-table tr.gst-row td { background: #fff8e1; color: #b45309; }
.totals-table tr.grand td { background: #111111; color: #F5A623; font-size: 13px; font-weight: 900; padding: 10px; }
/* Footer */
.footer-wrap { position: fixed; bottom: 22px; left: 0; right: 0; padding: 0 30px; }
.footer-row { width: 100%; border-collapse: collapse; margin-bottom: 6px; }
.footer-row td { font-size: 10.5px; font-weight: 600; color: #111; padding: 0; }
.f-label-cell { width: 36px; padding-right: 8px; }
.f-label { display: inline-block; background: #F5A623; color: #fff; font-size: 7.5px; font-weight: 800; letter-spacing: 0.5px; text-transform: uppercase; padding: 2px 5px; border-radius: 2px; }
.footer-bar { position: fixed; bottom: 0; left: 0; right: 0; height: 20px; background: #F5A623; }
</style>
</head>
<body>

{{-- HEADER --}}
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
                <img src="{{ $logoBase64 }}" style="max-height:55px; max-width:160px; object-fit:contain;">
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
    <tr><td colspan="2" class="header-orange-strip"></td></tr>
    <tr><td colspan="2" class="header-white-gap"></td></tr>
</table>

{{-- BODY --}}
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
                    @if(!empty($lead->email))Email: {{ $lead->email }}@endif
                </div>
            </td>
            <td style="width:46%; vertical-align:top;">
                <div class="inv-no-line">QUOTATION NO: <strong>#&nbsp;{{ $quotationNo }}</strong></div>
                <div class="inv-date-lines">
                    Quotation Date: {{ \Carbon\Carbon::now()->format('d M Y') }}<br>
                    @if(!empty($lead->project_type))Project Type: {{ $lead->project_type }}<br>@endif
                    @if(!empty($lead->expected_start_date))Start Date: {{ \Carbon\Carbon::parse($lead->expected_start_date)->format('d M Y') }}@endif
                </div>
            </td>
        </tr>
    </table>

 @php
    $grandTotal      = 0;
    $totalGST        = 0;
    $totalServiceTax = 0;

    $generalItems = array_values(array_filter($items, fn($i) => ($i['section'] ?? '') === 'General'));

    // ✅ Service header rows
    $serviceHeaders = array_values(array_filter($items, fn($i) => ($i['section'] ?? '') === 'Service'));

    // ✅ ServiceItem rows — grouped by service_id
    $serviceSubItems = array_filter($items, fn($i) => ($i['section'] ?? '') === 'ServiceItem');

    // Build groups: service_id → { header + sub_items[] }
    $serviceGroups = [];
    foreach ($serviceHeaders as $sh) {
        $gk = $sh['service_id'] ?? 0;
        $serviceGroups[$gk] = [
            'service_name' => $sh['service_name'] ?? $sh['item_name'] ?? '-',
            'note'         => $sh['service_note']       ?? '',
            'base_price'   => floatval($sh['service_base_price'] ?? 0),
            'gst_pct'      => floatval($sh['gst_percentage']     ?? 0),
            'service_tax'  => floatval($sh['service_tax']        ?? 0),
            'sub_items'    => [],
        ];
    }
    // Attach sub-items to their parent service
    foreach ($serviceSubItems as $si) {
        $gk = $si['service_id'] ?? 0;
        if (isset($serviceGroups[$gk])) {
            $serviceGroups[$gk]['sub_items'][] = $si;
        }
    }
@endphp

    {{-- SERVICE SECTIONS (Modular Kitchen, Living Room etc.) --}}
    @foreach($serviceGroups as $grpKey => $group)
        @php
            $svcBase   = $group['base_price'];
            $svcGstPct = $group['gst_pct'];
            $svcTax    = $group['service_tax'];
            $svcGstAmt = ($svcBase * $svcGstPct) / 100;
            $svcTotal  = $svcBase + $svcGstAmt + $svcTax;
            $grandTotal      += $svcTotal;
            $totalGST        += $svcGstAmt;
            $totalServiceTax += $svcTax;
            $mrpSum = array_sum(array_map(fn($s) => floatval($s['mrp'] ?? $s['unit_price'] ?? 0), $group['sub_items']));
        @endphp

        <table class="section-hdr-tbl" cellpadding="0" cellspacing="0">
            <tr><td>{{ strtoupper($group['service_name']) }}</td></tr>
        </table>

        <table class="inv-table" cellpadding="0" cellspacing="0">
            <thead>
                <tr>
                    <th class="col-sl c">SL<br>No.</th>
                    <th class="col-item">Items</th>
                    <th class="col-mat c">Material</th>
                    <th class="col-unit c">Unit</th>
                    <th class="col-size c">Size</th>
                    <th class="col-mrp r">MRP</th>
                    <th class="col-offer r">Offer Price</th>
                </tr>
            </thead>
            <tbody>
                @if(!empty($group['note']))
                <tr class="note-row"><td colspan="7">{{ $group['note'] }}</td></tr>
                @endif

                @foreach($group['sub_items'] as $si_idx => $si)
                @php
                    $siMrp   = floatval($si['mrp']        ?? ($si['unit_price'] ?? 0));
                    $siOffer = floatval($si['offer_price'] ?? ($si['unit_price'] ?? 0));
                    $siSize  = $si['size'] ?? ((!empty($si['length']) && !empty($si['breadth'])) ? $si['length'].'x'.$si['breadth'] : '');
                @endphp
                <tr>
                    <td class="c">{{ $si_idx + 1 }}</td>
                    <td>
                        <span class="item-name">{{ $si['item_name'] ?? '-' }}</span>
                        @if(!empty($si['description']))<div class="item-sub">{{ $si['description'] }}</div>@endif
                    </td>
                    <td class="c" style="font-size:10px; color:#555;">{{ $si['material'] ?? '-' }}</td>
                    <td class="c">{{ $si['unit'] ?? '-' }}</td>
                    <td class="c" style="font-size:10px;">{{ $siSize ?: '-' }}</td>
                    <td class="r">{{ $siMrp   > 0 ? number_format($siMrp,   0) : '-' }}</td>
                    <td class="r" style="font-weight:700; color:#111;">
                        {{ $siOffer > 0 ? 'Rs. '.number_format($siOffer, 0) : '-' }}
                    </td>
                </tr>
                @endforeach

                <tr class="subtotal-row">
                    <td colspan="5" style="text-align:right; letter-spacing:0.5px; text-transform:uppercase; font-size:10px;">
                        TOTAL @if($svcGstPct > 0)<span style="font-weight:400;color:#888;">(incl. {{ $svcGstPct }}% GST)</span>@endif
                    </td>
                    <td class="r" style="color:#888;">{{ $mrpSum > 0 ? number_format($mrpSum,0) : '' }}</td>
                    <td class="r" style="font-size:12px;">Rs. {{ number_format($svcTotal,2) }}</td>
                </tr>
            </tbody>
        </table>
    @endforeach

    {{-- GENERAL INVENTORY ITEMS --}}
    @if(count($generalItems) > 0)
    <table class="section-hdr-tbl" cellpadding="0" cellspacing="0" style="margin-top:18px;">
        <tr><td style="background:#444444;">OTHER ITEMS</td></tr>
    </table>
    <table class="inv-table" cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th class="col-sl c">SL<br>No.</th>
                <th class="col-item">Description</th>
                <th class="col-mat c">Category</th>
                <th class="col-unit c">Unit</th>
                <th class="col-size c">Qty</th>
                <th class="col-mrp r">Unit Price</th>
                <th class="col-offer r">Total</th>
            </tr>
        </thead>
        <tbody>
            @php $gIdx = 1; $genSub = 0; @endphp
            @foreach($generalItems as $gi)
            @php
                $qty     = floatval($gi['quantity']       ?? 1);
                $price   = floatval($gi['price']          ?? 0);
                $gstRate = floatval($gi['gst_percentage'] ?? 0);
                $amount  = $qty * $price;
                $gstAmt  = ($amount * $gstRate) / 100;
                $rowTotal = $amount + $gstAmt;
                $grandTotal += $rowTotal;
                $totalGST   += $gstAmt;
                $genSub     += $rowTotal;
            @endphp
            <tr>
                <td class="c">{{ $gIdx++ }}</td>
                <td>
                    <span class="item-name">{{ $gi['item_name'] ?? 'N/A' }}</span>
                    @if(!empty($gi['description']))<div class="item-sub">{{ $gi['description'] }}</div>@endif
                    @if(!empty($gi['length']) && !empty($gi['breadth']))
                        <div class="item-measure">{{ $gi['length'] }} ft x {{ $gi['breadth'] }} ft@if(!empty($gi['area']) && floatval($gi['area']) > 0) = {{ $gi['area'] }} Sqft@endif</div>
                    @endif
                </td>
                <td class="c" style="font-size:10px;color:#555;">{{ $gi['category'] ?? '-' }}</td>
                <td class="c">{{ $gi['unit'] ?? '-' }}</td>
                <td class="c">{{ $qty % 1 == 0 ? intval($qty) : $qty }}</td>
                <td class="r">Rs. {{ number_format($price,2) }}</td>
                <td class="r">Rs. {{ number_format($rowTotal,2) }}</td>
            </tr>
            @endforeach
            <tr class="subtotal-row">
                <td colspan="6" style="text-align:right;text-transform:uppercase;font-size:10px;letter-spacing:0.5px;">SUBTOTAL</td>
                <td class="r">Rs. {{ number_format($genSub,2) }}</td>
            </tr>
        </tbody>
    </table>
    @endif

    {{-- TOTALS --}}
    <table class="totals-table" cellpadding="0" cellspacing="0">
        @if($totalGST > 0)
        <tr class="gst-row">
            <td class="t-label">Total GST Amount</td>
            <td class="t-val">Rs. {{ number_format($totalGST,2) }}</td>
        </tr>
        @endif
        @if($totalServiceTax > 0)
        <tr>
            <td class="t-label" style="color:#555;">Service Tax</td>
            <td class="t-val"   style="color:#555;">Rs. {{ number_format($totalServiceTax,2) }}</td>
        </tr>
        @endif
        <tr class="grand">
            <td class="t-label" style="letter-spacing:1px;text-transform:uppercase;">Grand Total</td>
            <td class="t-val"   style="font-size:14px;">Rs. {{ number_format($grandTotal,2) }}</td>
        </tr>
    </table>

    <div style="margin-top:14px;font-size:9.5px;color:#aaa;font-style:italic;text-align:center;">
        Thank you for choosing Ginger Tree Interiors &amp; Design. This quotation is valid for 30 days.
    </div>
</div>

{{-- FOOTER --}}
<div class="footer-wrap">
    <table class="footer-row" cellpadding="0" cellspacing="0"><tr><td class="f-label-cell"><span class="f-label">Tel</span></td><td>+91 7402 99 0000</td></tr></table>
    <table class="footer-row" cellpadding="0" cellspacing="0"><tr><td class="f-label-cell"><span class="f-label">Mail</span></td><td>mail@gingertree.in</td></tr></table>
    <table class="footer-row" cellpadding="0" cellspacing="0"><tr><td class="f-label-cell"><span class="f-label">Addr</span></td><td>Jawahar Rd, Near Gandhi Square, Poonithura, Petta</td></tr></table>
</div>
<div class="footer-bar"></div>
</body>
</html>
