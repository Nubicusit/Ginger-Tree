<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Gingertree Interiors</title>
    <link rel="icon" type="image/png" href="{{ asset('img/Boltoni-Favicon.png') }}">
    <link rel="icon" type="image/png" href="{{ asset('img/Boltoni-Favicon.png') }}">
    <link rel="icon" type="image/png" href="{{ asset('img/Boltoni-Favicon.png') }}">
    <style>
        body {
            font-family: DejaVu Sans, Arial, Helvetica, sans-serif;
            font-size: 13px;
            color: #000;
            margin: 30px;
        }

        .border-box {
            border: 2px solid #004e7c;
            padding: 25px;
        }

        .header-table {
            width: 100%;
        }

        .header-table td {
            vertical-align: top;
        }

        .quote-title {
            color: #004e7c;
            font-weight: bold;
            font-size: 22px;
        }

        .info {
            font-size: 12px;
            margin-top: 5px;
            line-height: 1.3;
        }

        .info strong {

            width: auto;
            min-width: 40px;
        }

        .right-info {
            text-align: right;
        }

        .quote-details {
            width: 220px;
            border-collapse: collapse;
            font-size: 12px;
        }

        .quote-details td {
            border: 1px solid #004e7c;
            padding: 4px 8px;
        }

        .customer-header {
            background-color: #004e7c;
            color: #fff;
            font-weight: bold;
            padding: 5px 8px;
            font-size: 13px;
        }

        .customer-box {
            border: 1px solid #004e7c;
            border-top: none;
            padding: 8px;
            font-size: 12px;
            width: 40%;
            margin-top: 20px;
        }

        .invoice-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 25px;
            font-size: 12.5px;
        }

        .invoice-table th {
            background-color: #004e7c;
            color: #fff;
            padding: 6px;
            text-align: left;
        }

        .invoice-table td {
            border: 1px solid #004e7c;
            padding: 6px;
        }

        .invoice-table tr:nth-child(even) {
            background-color: #f2f9ff;
        }

        .terms {
            border: 1px solid #004e7c;
            margin-top: 20px;
            font-size: 12px;
        }

        .terms-title {
            background-color: #004e7c;
            color: white;
            font-weight: bold;
            padding: 5px 8px;
        }

        .terms-content {
            padding: 8px 10px;
        }

        .summary {
            float: right;
            margin-top: 10px;
        }

        .footer {
            text-align: center;
            font-size: 12px;
            margin-top: 50px;
        }

        .footer strong {
            font-size: 14px;
            color: #004e7c;
        }

        .bottom-section {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
            gap: 20px;
        }

        .terms {
            flex: 1;
            border: 1px solid #004e7c;
            font-size: 12px;
        }

        .terms-title {
            background-color: #004e7c;
            color: white;
            font-weight: bold;
            padding: 5px 8px;
        }

        .terms-content {
            padding: 8px 10px;
        }

        .summary {
            width: 35%;
            border-collapse: collapse;
            font-size: 12.5px;
        }

        .summary td {
            padding: 4px 8px;
            border: 1px solid #004e7c;
        }

        .summary .total-row {
            background-color: #ffecb3;
            font-weight: bold;
        }

        .watermark-img {
            position: fixed;
            top: 40%;
            left: 50%;
            transform: translateX(-50%);
            width: 400px;
            opacity: 0.2;
            z-index: 2;
            pointer-events: none;
        }
    </style>
</head>
<body>
    <img src="{{ public_path('img/gingertree-white-logo.png') }}" class="watermark-img" alt="Watermark" />
    <div class="border-box">
        <table class="header-table">
            <tr>
                <td>
                    <img src="{{ public_path('img/gingertree-white-logo.png') }}" height="75" alt="Logo"><br>
                    <div class="info">
                        <div>[Street Address]</div>
                        <div>[City, ST ZIP]</div>
                        <div><strong>Website:</strong>somedomain.com</div>
                        <div><strong>Phone:</strong>+91 1234567899</div>
                        <div><strong>Prepared by:</strong></div>
                    </div>
                </td>
                <td class="right-info">
                    <div class="quote-title">QUOTE</div>
                    <table class="quote-details" align="right">
                        <tr>
                            <td>DATE</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>QUOTE </td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>CUSTOMER ID</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>VALID UNTIL</td>
                            <td></td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <div class="customer-header"> CLIENT DETAILS</div>
        <div class="">
            <div><strong>Name:</strong>{{ $lead->client_name }}</div>
            <p><strong>Project Type:</strong> {{ $lead->project_type }}</p>
            <p><strong>Approved:</strong> {{ $lead->siteVisit->approval_status }}</p>
        </div>
        <table class="invoice-table">
            <thead>
                <tr>
                    <th style="width: 50%;">PAPER</th>
                    <th style="width: 60%;">SIZE</th>
                    <th style="width: 30%;">QUANTITY</th>
                    <th style="width: 30%;">PRINTING QUANTITY</th>
                    <th style="width: 20%;">UNIT PRICE</th>
                    <th style="width: 10%;">TAXED</th>
                    <th style="width: 20%;">AMOUNT</th>
                </tr>
            </thead>
            <tbody>


            </tbody>
        </table>
        <table width="100%" cellspacing="0" cellpadding="5" style="margin-top:20px; border-spacing:0;">
            <tr valign="top">
                <td style="width:60%; padding-right:10px;">
                    <table width="100%" style="border:1px solid #004e7c; border-collapse:collapse;">
                        <tr>
                            <td style="background-color:#004e7c; color:#fff; font-weight:bold; padding:5px 8px;">
                                TERMS AND CONDITIONS
                            </td>
                        </tr>
                        <tr>
                            <td style="padding:8px 10px; font-size:12px;">
                                <div>1. Customer will be billed after indicating acceptance of this quote.</div>
                                <div>2. Payment will be due prior to delivery of service and goods.</div>
                                <div>3. Please fax or mail the signed price quote to the address above.</div>
                                <br>
                                <em>Customer Acceptance (sign below):</em><br><br>
                                X ___________________________________________<br>
                                <strong>Print Name:</strong>
                            </td>
                        </tr>
                    </table>
                </td>
                <td style="width:40%; vertical-align:top;">
                    <table width="100%" style="border-collapse:collapse; font-size:12.5px;">
                        <tr>
                            <td>Subtotal</td>
                            <td align="right"></td>
                        </tr>
                        <tr>
                            <td>Taxable</td>
                            <td align="right">00.00</td>
                        </tr>
                        <tr>
                            <td>Tax rate</td>
                            <td align="right">0%</td>
                        </tr>
                        <tr>
                            <td>Tax due</td>
                            <td align="right">00</td>
                        </tr>
                        <tr>
                            <td>Other</td>
                            <td align="right">00.00</td>
                        </tr>
                        <tr style="background-color:#ffecb3; font-weight:bold;">
                            <td>TOTAL</td>
                            <td align="right">₹ </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <div style="clear: both;"></div>
        <div class="footer">
            <p>If you have any questions about this price quote, please contact</p>
            <p>GingerTree Interiors, email, phone</p>
            <strong>Thank You For Your Business!</strong>
        </div>
    </div>
</body>
</html>
