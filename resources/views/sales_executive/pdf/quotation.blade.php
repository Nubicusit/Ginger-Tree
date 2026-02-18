<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<style>
@page { size: A4; margin: 18mm; }
body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #111; }
p { margin: 0 0 6px 0; line-height: 1.4; }
.header-table { width: 100%; }
.company-name { font-size: 22px; font-weight: bold; }
.right { text-align: right; }
.estimate-title { text-align: center; font-size: 18px; letter-spacing: 4px; font-weight: bold; margin: 20px 0; }
table { width: 100%; border-collapse: collapse; }
th, td { border: 1px solid #cbd5e1; padding: 6px; vertical-align: top; }
th { background-color: #f8fafc; font-size: 11px; text-align: center; }
.text-center { text-align: center; }
.text-right { text-align: right; }
.item-title { font-weight: bold; margin-bottom: 4px; }
.img-box { width: 70px; height: 55px; border: 1px solid #ccc; text-align: center; line-height: 55px; }
.img-box img { max-width: 70px; max-height: 55px; }
.total-table { width: 280px; float: right; margin-top: 20px; }
.grand { font-weight: bold; font-size: 14px; border: 2px solid #000; }
tr { page-break-inside: avoid; }
</style>
</head>
<body>

<!-- HEADER -->
<table class="header-table">
    <tr>
        <td>
            <img src="{{ public_path('img/gingertree-white-logo.png') }}" height="50">
        </td>
        <td class="right">
            <div class="company-name">Gingertree Interiors</div>
            <p>C-309, Third Floor, Block-5, M3M abc</p>
            <p>Sector-67, 122001</p>
            <p>8920380418</p>
            <p>contact@gingertreeinteriors.com</p>
        </td>
    </tr>
</table>

<!-- CLIENT -->
<p style="margin-top:15px;">Prepared for</p>
<p><strong>{{ $lead->client_name }}</strong></p>
<p>Project: {{ $lead->project_type ?? $lead->id }}</p>
<p>{{ now()->format('M d, Y') }}</p>

<div class="estimate-title">ESTIMATE</div>

<!-- ITEMS TABLE -->
<table>
<thead>
    <tr>
        <th>SN</th>
        <th>Item Description</th>
        <th>Image</th>
        <th>Unit Rate</th>
        <th>Qty</th>
        <th>Total</th>
    </tr>
</thead>
<tbody>
    <tr>
        <td class="text-center">1</td>
        <td>
            <p class="item-title">{{ $quotation->description }}</p>
        </td>
        <td class="text-center">
            <div class="img-box">
                @if($quotation->image)
                    <img src="{{ public_path('img/' . $quotation->image) }}">
                @else
                    <span style="font-size:10px;color:#aaa;">No image</span>
                @endif
            </div>
        </td>
        <td class="text-center">₹ {{ number_format($quotation->price, 2) }}</td>
        <td class="text-center">{{ $quotation->quantity }}</td>
        <td class="text-right">₹ {{ number_format($quotation->total, 2) }}</td>
    </tr>
</tbody>
</table>

<!-- TOTALS -->
@php
    $gst = $quotation->total * 0.18;
    $grand = $quotation->total + $gst;
@endphp

<table class="total-table">
    <tr>
        <td class="text-right">Subtotal</td>
        <td class="text-right">₹ {{ number_format($quotation->total, 2) }}</td>
    </tr>
    <tr>
        <td class="text-right">GST (18%)</td>
        <td class="text-right">₹ {{ number_format($gst, 2) }}</td>
    </tr>
    <tr class="grand">
        <td class="text-right">Grand Total</td>
        <td class="text-right">₹ {{ number_format($grand, 2) }}</td>
    </tr>
</table>

</body>
</html>
