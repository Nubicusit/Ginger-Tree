

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{ $quotation->quotation_no ?? 'Quotation' }}</title>
    <style>
        @page { 
            size: A4; 
            margin: 20mm 15mm 15mm 15mm; 
        }
        * { 
            margin: 0; 
            padding: 0; 
            box-sizing: border-box; 
        }
        body { 
            font-family: 'Helvetica Neue', 'Segoe UI', Arial, sans-serif; 
            font-size: 11px; 
            line-height: 1.4;
            color: #2c3e50;
            max-width: 190mm;
            margin: 0 auto;
            background: white;
        }
        
        /* Header Styles */
        .header { 
            text-align: center; 
            margin-bottom: 30px; 
            padding-bottom: 25px;
            border-bottom: 4px double #d4af37;
        }
        .logo-container {
            width: 100px;
            height: 80px;
            margin: 0 auto 15px;
            border: 2px solid #f0f0f0;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #fafafa;
            overflow: hidden;
        }
        .logo-container img {
            max-width: 96px;
            max-height: 76px;
            object-fit: contain;
        }
        .company-name { 
            font-size: 28px; 
            font-weight: 800; 
            color: #d4af37; 
            margin-bottom: 5px; 
            letter-spacing: 1px;
            text-transform: uppercase;
        }
        .company-tagline {
            font-size: 12px;
            color: #7f8c8d;
            font-style: italic;
            margin-bottom: 15px;
        }
        .company-details {
            font-size: 11px;
            color: #5a6c7d;
            line-height: 1.5;
        }
        
        /* Quotation Info */
        .quotation-info {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 25px;
            border: 1px solid #dee2e6;
        }
        .qt-main-title { 
            font-size: 24px; 
            font-weight: 700; 
            color: #d4af37; 
            margin-bottom: 15px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        .qt-meta {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            font-size: 13px;
        }
        .meta-item {
            background: white;
            padding: 12px;
            border-radius: 8px;
            border-left: 4px solid #d4af37;
        }
        .meta-label {
            font-weight: 600;
            color: #6c757d;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .meta-value {
            font-size: 16px;
            font-weight: 700;
            color: #2c3e50;
            margin-top: 4px;
        }
        
        /* Customer Section */
        .customer-section { 
            background: #fff8e1; 
            padding: 20px; 
            margin-bottom: 25px; 
            border-radius: 12px; 
            border-left: 6px solid #d4af37; 
        }
        .section-title { 
            font-weight: 700; 
            font-size: 12px; 
            color: #d4af37; 
            margin-bottom: 15px; 
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .customer-name { 
            font-size: 18px; 
            font-weight: 700; 
            color: #2c3e50; 
            margin-bottom: 10px; 
        }
        .customer-details {
            color: #6c757d;
            font-size: 12px;
            line-height: 1.6;
        }
        
        /* Items Table */
        .items-table { 
            width: 100%; 
            border-collapse: collapse; 
            margin: 25px 0; 
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            border-radius: 12px;
            overflow: hidden;
        }
        th { 
            background: linear-gradient(135deg, #d4af37 0%, #b8942f 100%) !important; 
            color: white; 
            padding: 15px 12px; 
            text-align: left; 
            font-size: 11px; 
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        td { 
            padding: 14px 12px; 
            border-bottom: 1px solid #f1f3f4; 
        }
        tr:hover td {
            background: #f8f9fa;
        }
        .text-right { text-align: right; }
        .qty-col, .rate-col, .total-col, .img-col { width: 10%; }
        .desc-col { width: 48%; }
        .item-name { 
            font-weight: 600; 
            color: #d4af37; 
            font-size: 12px; 
        }
        .item-desc {
            color: #6c757d;
            font-size: 11px;
        }
        .img-container { 
            width: 50px; 
            height: 40px; 
            border: 1px solid #e9ecef; 
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            margin: 0 auto;
            background: #fafafa;
        }
        .img-container img { 
            max-width: 48px; 
            max-height: 38px; 
            object-fit: cover;
        }
        
        /* Totals */
        .totals-section { 
            width: 50%; 
            margin-left: auto; 
            margin-top: 30px; 
        }
        .totals-table {
            width: 100%;
            background: #f8f9fa;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }
        .totals-table td {
            padding: 12px 20px;
            border-bottom: 1px solid #e9ecef;
        }
        .total-label {
            font-weight: 600;
            color: #6c757d;
        }
        .total-value {
            font-weight: 700;
            color: #2c3e50;
            font-size: 13px;
        }
        .grand-total-row {
            background: linear-gradient(135deg, #d4af37 0%, #b8942f 100%) !important;
            color: white !important;
            border-bottom: none !important;
        }
        .grand-total-row .total-label,
        .grand-total-row .total-value {
            color: white !important;
            font-weight: 800 !important;
            font-size: 16px !important;
        }
        
        /* Signature */
        .signature-section { 
            margin-top: 60px; 
            display: flex; 
            justify-content: flex-end; 
            padding-top: 40px;
            border-top: 2px dashed #dee2e6;
        }
        .signature-box { 
            width: 250px; 
            text-align: center; 
            padding-top: 70px; 
            font-weight: 600;
            position: relative;
        }
        .signature-box::before {
            content: "________________________";
            position: absolute;
            top: 25px;
            left: 50%;
            transform: translateX(-50%);
            font-size: 14px;
            color: #dee2e6;
            letter-spacing: 3px;
        }
        .signature-company {
            font-size: 11px;
            color: #6c757d;
            margin-top: 8px;
        }
        
        /* Print Optimizations */
        @media print { 
            body { 
                -webkit-print-color-adjust: exact; 
                print-color-adjust: exact; 
                margin: 0;
            }
            .totals-section {
                position: absolute;
                bottom: 40px;
                right: 15mm;
            }
        }
        
        /* Responsive for preview */
        @media screen and (max-width: 768px) {
            body { font-size: 10px; }
            .qt-meta { grid-template-columns: 1fr; }
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

<!-- Header -->
<div class="header">
    <div class="logo-container">
        @if(file_exists(public_path('img/gingertree-white-logo.png')))
            <img src="{{ asset('img/gingertree-white-logo.png') }}" alt="Logo">
        @else
            <span style="color:#999; font-size: 10px;">LOGO</span>
        @endif
    </div>
    
    <div class="company-name">Gingertree Interiors</div>
    <div class="company-tagline">Premium Interior Solutions</div>
    <div class="company-details">
        C-309, Third Floor, Block-5, M3M ABC<br>
        Sector-67, 122001<br>
        M: +91 89203 80418 | E: contact@gingertreeinteriors.com
    </div>
</div>

<!-- Quotation Info -->
<div class="quotation-info">
    <div class="qt-main-title">Quotation</div>
    <div class="qt-meta">
        <div class="meta-item">
            <div class="meta-label">Quotation No</div>
            <div class="meta-value">{{ $quotation->quotation_no }}</div>
        </div>
        <div class="meta-item">
            <div class="meta-label">Date</div>
            <div class="meta-value">{{ \Carbon\Carbon::parse($quotation->created_at)->format('d M Y') }}</div>
        </div>
    </div>
</div>

<!-- Customer Section -->
<div class="customer-section">
    <div class="section-title">Bill To:</div>
    <div class="customer-name">{{ $quotation->lead->client_name ?? 'Customer Name' }}</div>
    <div class="customer-details">
        @if($quotation->lead->phone) Phone: {{ $quotation->lead->phone }} @endif
        @if($quotation->lead->location) Location: {{ $quotation->lead->location }} @endif
    </div>
</div>

<!-- Items Table -->
<table class="items-table">
    <thead>
        <tr>
            <th style="width: 5%;">#</th>
            <th style="width: 15%;">Item</th>
            <th class="desc-col">Description</th>
            <th class="img-col">Image</th>
            <th class="qty-col">Qty</th>
            <th class="rate-col">Rate (₹)</th>
            <th class="total-col">Total (₹)</th>
        </tr>
    </thead>
    <tbody>
        @forelse($items as $index => $item)
            <tr>
                <td style="font-weight: 600;">{{ $index + 1 }}</td>
                <td class="item-name">
                    {{ \App\Models\InventoryStock::find($item['item_id'] ?? 0)?->item_name ?? 'N/A' }}
                </td>
                <td class="item-desc">{{ $item['description'] ?? '-' }}</td>
                <td>
                    <div class="img-container">
                        @if(!empty($item['image']))
                            @if(file_exists(public_path("quotations/{$item['image']}")))
                                <img src="{{ asset("quotations/{$item['image']}") }}" alt="Item">
                            @elseif(file_exists(public_path($item['image'])))
                                <img src="{{ asset($item['image']) }}" alt="Item">
                            @else
                                <span style="color:#999; font-size: 9px;">No img</span>
                            @endif
                        @else
                            <span style="color:#999; font-size: 9px;">No img</span>
                        @endif
                    </div>
                </td>
                <td class="text-right" style="font-weight: 600;">{{ $item['quantity'] ?? '-' }}</td>
                <td class="text-right">₹{{ number_format($item['price'] ?? 0, 2) }}</td>
                <td class="text-right" style="font-weight: 700; font-size: 12px;">₹{{ number_format($item['total'] ?? 0, 2) }}</td>
            </tr>
        @empty
            <tr><td colspan="7" style="text-align: center; padding: 40px; color: #6c757d;">No items in quotation</td></tr>
        @endforelse
    </tbody>
</table>

<!-- Totals -->
<table class="totals-section">
    <table class="totals-table">
        <tr>
            <td class="total-label" style="width: 60%;">Sub Total</td>
            <td class="total-value text-right">₹{{ number_format($subtotal, 2) }}</td>
        </tr>
        <tr>
            <td class="total-label">GST (18%)</td>
            <td class="total-value text-right">₹{{ number_format($gst, 2) }}</td>
        </tr>
        <tr class="grand-total-row">
            <td class="total-label">Grand Total</td>
            <td class="total-value text-right">₹{{ number_format($grandTotal, 2) }}</td>
        </tr>
    </table>
</table>

<!-- Signature -->
<div class="signature-section">
    <div class="signature-box">
        Authorized Signature
        <div class="signature-company">Gingertree Interiors</div>
    </div>
</div>

<script>
window.onload = function() {
    setTimeout(() => {
        window.print();
        window.onafterprint = () => window.close();
    }, 1000);
}
</script>

</body>
</html>



