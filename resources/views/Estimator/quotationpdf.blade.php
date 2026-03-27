<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Gingertree Interiors - Invoice</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: DejaVu Sans, sans-serif;
            background: #fff;
        }

        /* ===================== PRINT / PAGE SETUP ===================== */
        @page {
            size: A4;
            margin: 0;
        }

        /* ===================== PAGE WRAPPER ===================== */
        /*
         * Each .page is a fixed A4 box (794×1123px screen / 210×297mm print).
         * Header and footer are ALWAYS at top/bottom.
         * .page-body is the scrollable middle — it never overflows the page.
         *
         * For pages whose content CAN grow (page 3, item tables):
         *   use .page-auto  → height:auto, content wraps across print pages
         *   with @page running header/footer via CSS (WeasyPrint / Prince).
         *
         * For fixed design pages (cover, info, totals, terms):
         *   use .page-fixed → height:1123px, overflow:hidden
         */

        .page-fixed {
            position: relative;
            width: 794px;
            height: 1123 px;
            background: #ffffff;
            margin: 0 auto 0 auto;
            overflow: hidden;
            page-break-after: always;
        }

        /* Auto-height page for item tables — grows with content */
        .page-auto {
            position: relative;
            width: 794px;
            background: #ffffff;
            margin: 0 auto 0 auto;
            page-break-after: always;
        }

        /* ===================== HEADER ===================== */
        .header-wrap {
            position: relative;
            width: 100%;
            line-height: 0;
            flex-shrink: 0;
        }
        .header-wrap img.header-bg {
            width: 100%;
            display: block;
        }
        .logo-cell {
            width: 220px;
            padding-left: 22px;
            vertical-align: middle;
        }
        .logo-cell img {
            height: 70px;
            width: auto;
            display: block;
        }
        .invoice-word-cell {
            width: 160px;
            text-align: right;
            padding-right: 22px;
           vertical-align: top;   /* change this */
    padding-top: 55px;
        }
        .invoice-word {
            font-size: 38px;
            font-weight: bold;
            color: #ffffff;
            letter-spacing: 3px;
            text-transform: uppercase;
            line-height: 1;
        }

        /* ===================== FOOTER BAR ===================== */
        .footer-bar-wrap {
            position: relative;
            width: 100%;
            line-height: 0;
        }
        .footer-bar-wrap img {
            width: 100%;
            display: block;
        }

        /* For .page-fixed, footer is always pinned to bottom */
        .page-fixed .footer-bar-wrap {
            position: absolute;
            bottom: 0;
            left: 0;
        }

        /* For .page-auto, footer just sits after content */
        .page-auto .footer-bar-wrap {
            margin-top: 10px;
        }

        /* ===================== PAGE 1 BODY ===================== */
        .image-title {
            text-align: right;
            font-size: 13px;
            font-weight: bold;
            color: #333;
            padding: 18px 28px 8px 0;
        }
        .image-row-table {
            width: 100%;
            border-collapse: collapse;
        }
        .orange-bar-cell {
            width: 28px;
            background: #f5a623;
        }
        .interior-img-cell {
            padding-right: 30px;
            text-align: center;
        }
        .interior-img-cell img {
            width: 100%;
            display: block;
        }
        .captions-table {
            width: 100%;
            margin-top: 10px;
        }
        .captions-table td {
            text-align: right;
            padding-right: 36px;
            font-size: 11px;
            font-weight: bold;
            color: #111;
            letter-spacing: 1px;
            text-transform: uppercase;
            line-height: 1;
        }

        /* ===================== BADGES ===================== */
        .badges-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
        }
        .badge-cell {
            text-align: center;
            vertical-align: top;
            padding: 10px 6px 6px 6px;
            width: 14%;
        }
        .badge-cell img {
            width: 50px;
            height: 50px;
            display: block;
            margin: 0 auto 5px auto;
        }
        .badge-cell-name {
            font-size: 10px;
            font-weight: bold;
            color: #111;
            text-align: center;
            text-transform: uppercase;
            letter-spacing: 0.2px;
            line-height: 1.5;
        }

        /* ===================== BOTTOM BAR ===================== */
        .bottom-bar-table {
            width: 100%;
            margin-top: 8px;
            border-collapse: collapse;
        }
        .bottom-bar-table td {
            font-size: 20px;
            font-weight: bold;
            color: #000;
            letter-spacing: 0.5px;
            padding: 10px 28px;
        }

        /* ===================== PAGE 2 ===================== */
        .subject-bar {
            background: #f9af43;
            padding: 10px 18px;
            margin: 16px 36px 18px 36px;
        }
        .subject-bar p {
            font-size: 14px;
            font-weight: bold;
            color: #23272e;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }
        .info-table {
            width: calc(100% - 72px);
            margin: 0 36px;
            border-collapse: collapse;
            font-size: 13px;
            table-layout: fixed;
        }
        .info-table td {
            border: 1px solid #c8c8c8;
            padding: 7px 10px;
            vertical-align: top;
            line-height: 1.5;
            word-wrap: break-word;
        }
        .info-table .label   { font-weight: bold; color: #23272e; width: 90px; }
        .info-table .value   { font-weight: 600;  color: #23272e; width: 190px; }
        .info-table .label-r { font-weight: bold; color: #23272e; width: 110px; }
        .info-table .value-r { font-weight: 600;  color: #23272e; }

        /* PAGE 2 FOOTER CONTACTS */
        .footer-contacts-table {
            width: calc(100% - 72px);
            margin: 14px 36px 10px 36px;
            border-collapse: collapse;
        }
        .footer-contacts-table td {
            padding: 5px 0;
            font-size: 13px;
            font-weight: 600;
            color: #000;
            vertical-align: middle;
        }
        .ficon-cell {
            width: 26px;
            text-align: center;
            vertical-align: middle;
        }
        .ficon-cell img {
            width: 18px;
            height: 18px;
            display: block;
            margin: 0 auto;
        }
        .ftext-cell {
            padding-left: 10px;
            vertical-align: middle;
        }

        /* ===================== PAGE 3 — ITEM TABLES ===================== */
        /*
         * .body-pad wraps all item tables.
         * Tables use page-break-inside:avoid so a single service block
         * never gets split mid-row. If the block is taller than a page
         * it will still break — but only at row boundaries.
         */
        .body-pad {
            padding: 16px 36px 10px 36px;
        }

        .mk-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
            margin-bottom: 16px;
            table-layout: fixed;
            page-break-inside: auto;   /* allow break between rows */
        }
        .mk-table tr {
            page-break-inside: avoid;  /* never split a single row */
        }
        .mk-table thead {
            display: table-header-group; /* repeat header on each print page */
        }
        .mk-table th {
            background: #23272e;
            color: #ffffff;
            font-size: 13px;
            font-weight: bold;
            letter-spacing: 0.3px;
            text-transform: uppercase;
            padding: 6px 7px;
            border: 1px solid #23272e;
            text-align: center;
        }
        .mk-table th.left { text-align: left; }
        .mk-table td {
            border: 1px solid #c8c8c8;
            padding: 5px 7px;
            vertical-align: middle;
            color: #23272e;
            font-size: 13px;
            word-wrap: break-word;
        }
        .mk-table td.center { text-align: center; }
        .mk-table td.right  { text-align: right; }
        .mk-table td.bold   { font-weight: bold; }
        .mk-table .desc-row td { color: #333; line-height: 1.5; padding: 6px 7px; }
        .mk-table .total-row td {
            background: #f5f5f5;
            font-weight: bold;
            font-size: 14px;
            text-align: center;
            padding: 7px;
            border: 1px solid #c8c8c8;
        }
        .item-name { font-weight: bold; }

        .section-header-row td {
            background: #F9AF43;
            color: #23272e;
            text-align: center;
            padding: 7px 12px;
            font-size: 13px;
            font-weight: bold;
            letter-spacing: 0.6px;
            border: 1px solid #d4a035;
        }

        .acc-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
            table-layout: fixed;
            margin-bottom: 10px;
            page-break-inside: avoid;
        }
        .acc-table th {
            background: #23272e;
            color: #fff;
            font-size: 13px;
            font-weight: bold;
            padding: 6px 10px;
            border: 1px solid #23272e;
            text-align: center;
        }
        .acc-table th.left { text-align: left; }
        .acc-table td {
            border: 1px solid #c8c8c8;
            padding: 5px 10px;
            vertical-align: middle;
            font-size: 13px;
            color: #23272e;
        }
        .acc-table td.acc-img-cell {
            padding: 4px;
            text-align: center;
            width: 160px;
        }
        .acc-table td.acc-img-cell img {
            width: 150px;
            height: 68px;
            object-fit: cover;
            display: block;
            margin: 0 auto;
        }
        .acc-table td.acc-name {
            font-weight: 500;
            vertical-align: middle;
            padding: 8px 12px;
        }

        /* ===================== PAGE 6 ===================== */
        .body-p6 {
            padding: 22px 36px 10px 36px;
            /* leave space for pinned footer (≈60px) */
            padding-bottom: 80px;
        }

        .grand-total-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
            table-layout: fixed;
            margin-bottom: 6px;
        }
        .grand-total-table td {
            border: 1px solid #c8c8c8;
            padding: 8px 12px;
            vertical-align: middle;
        }
        .gt-label {
            color: #f9af43;
            font-weight: bold;
            font-size: 18px;
            text-align: center;
            width: 55%;
        }
        .gt-mrp-label {
            font-size: 11px;
            font-weight: bold;
            color: #23272e;
            text-align: center;
            width: 20%;
        }
        .gt-mrp-value {
            text-align: right;
            font-weight: bold;
            font-size: 13px;
            padding: 6px 12px;
        }
        .gt-offer {
            text-align: center;
            font-weight: bold;
            font-size: 15px;
            width: 25%;
            text-transform: uppercase;
        }
        .gt-offer-value {
            text-align: right;
            font-weight: bold;
            font-size: 19px;
            padding: 8px 12px;
            color: #23272e;
        }
        .gst-row {
            font-size: 11px;
            color: #444;
            padding: 4px 0 10px 2px;
        }
        .saved-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
        }
        .saved-table td {
            border: 1px solid #c8c8c8;
            padding: 10px 14px;
            vertical-align: middle;
        }
        .saved-label {
            color: #f9af43;
            font-weight: bold;
            font-size: 16px;
            width: 65%;
        }
        .saved-value {
            font-weight: bold;
            font-size: 18px;
            color: #23272e;
            text-align: right;
        }
        .terms-block { font-size: 12px; color: #23272e; line-height: 1.7; margin-bottom: 10px; }
        .terms-title { font-weight: bold; font-size: 12px; margin-bottom: 4px; }
        .terms-block ol { padding-left: 22px; }
        .terms-block li { margin-bottom: 2px; }

        /* ===================== PAGE 7 ===================== */
        .body-p7 {
            padding: 22px 36px 10px 36px;
            padding-bottom: 80px;
        }
        .section-title-p7 { font-size: 13px; font-weight: bold; color: #23272e; margin-bottom: 4px; }
        .section-text-p7  { font-size: 12px; color: #23272e; line-height: 1.7; margin-bottom: 4px; }
        .alpha-list { list-style-type: lower-alpha; padding-left: 28px; font-size: 12px; color: #23272e; line-height: 1.8; margin-bottom: 10px; }
        .notes-label    { font-size: 13px; font-weight: bold; color: #23272e; margin-bottom: 5px; }
        .notes-subtitle { font-size: 12px; color: #23272e; padding-left: 38px; margin-bottom: 7px; }
        .bullet-list    { list-style: none; padding-left: 0; font-size: 11px; color: #23272e; line-height: 1.8; margin-bottom: 12px; }
        .bullet-list li { padding-left: 14px; margin-bottom: 2px; }
        .closing-sincerely { font-size: 19px; font-weight: 600; font-style: italic; color: #23272e; }
        .closing-company   { font-size: 13px; font-weight: bold; color: #23272e; }
        .closing-iso       { font-size: 13px; font-weight: bold; color: #23272e; }
        .closing-tagline   { font-size: 13px; font-weight: bold; color: #23272e; }
        .plant-text {
            font-size: 14px;
            font-weight: bold;
            color: #23272e;
            letter-spacing: 0.4px;
            text-transform: uppercase;
            padding: 10px 0 4px 0;
        }
    </style>
</head>
<body>

<!-- ============================================================ -->
<!--  PAGE 1 — COVER  (fixed height, never grows)               -->
<!-- ============================================================ -->
<div class="page-fixed">

    <div class="header-wrap">
        <img class="header-bg" src="{{ public_path('img/HEADER-01.png') }}" width="794" alt="Header">
        <table style="position:absolute;top:0;left:0;width:100%;height:100%;border-collapse:collapse;">
            <tr>
                <td class="logo-cell">
                    <img src="{{ public_path('img/gingertree-white-logo.png') }}" alt="Logo" style="height:70px;width:auto;display:block;">
                </td>
                <td style="vertical-align:top;text-align:right;padding-top:8px;padding-right:10px;">
                    <span style="font-size:11px;font-weight:bold;color:#000;">www.Gingertree.in</span>
                </td>
                <td class="invoice-word-cell">
                    <span class="invoice-word">ESTIMATION</span>
                </td>
            </tr>
        </table>
    </div>

    <div class="image-title">100% CUSTOMIZED CONTEMPORARY</div>

    <table class="image-row-table">
        <tr>
            <td class="orange-bar-cell">&nbsp;</td>
            <td class="interior-img-cell">
                <img src="{{ public_path('img/modern-chandeliers-1024x666.jpg') }}" alt="Interior" style="width:100%;display:block;">
            </td>
        </tr>
    </table>

    <table class="captions-table">
        <tr><td>GET YOUR HOME INTERIOR DONE IN 30 WORKING DAYS</td></tr>
        <tr><td>DIRECT FROM OWN FACTORY</td></tr>
        <tr><td>FAST GROWING INTERIOR COMPANY</td></tr>
    </table>

    <table class="badges-table">
        <tr>
            <td class="badge-cell">
                <img src="{{ public_path('img/GINGERICONS-01 (1).png') }}" alt="">
                <div class="badge-cell-name">ISO 9001 QUALITY<br>CERTIFIED COMPANY</div>
            </td>
            <td class="badge-cell">
                <img src="{{ public_path('img/GINGERICONS-02.png') }}" alt="">
                <div class="badge-cell-name">DIRECT FROM OWN<br>FACTORY</div>
            </td>
            <td class="badge-cell">
                <img src="{{ public_path('img/GINGERICONS-03.png') }}" alt="">
                <div class="badge-cell-name">LIFE TIME SERVICE<br>WARRANTY</div>
            </td>
            <td class="badge-cell">
                <img src="{{ public_path('img/GINGERICONS-04.png') }}" alt="">
                <div class="badge-cell-name">BEST PRICE IN<br>THE REGION</div>
            </td>
            <td class="badge-cell">
                <img src="{{ public_path('img/GINGERICONS-05.png') }}" alt="">
                <div class="badge-cell-name">BORER, TERMITE<br>RESISTANT</div>
            </td>
            <td class="badge-cell">
                <img src="{{ public_path('img/GINGERICONS-06.png') }}" alt="">
                <div class="badge-cell-name">WATER<br>RESISTANT</div>
            </td>
            <td class="badge-cell">
                <img src="{{ public_path('img/GINGERICONS-07.png') }}" alt="">
                <div class="badge-cell-name">WELL TRAINED<br>EMPLOYEES TEAM</div>
            </td>
        </tr>
    </table>

    <table class="bottom-bar-table">
        <tr>
            <td>KERALA &nbsp;|&nbsp; TAMIL NADU &nbsp;|&nbsp; KARNATAKA</td>
        </tr>
    </table>

    <div class="footer-bar-wrap">
        <img src="{{ public_path('img/new.png') }}" style="width:100%;display:block;">
      <table style="position:absolute;top:0;left:0;width:100%;height:100%;border-collapse:collapse;">
          <tr>
              <td>&nbsp;</td>
                <td style="width:15%;text-align:center;font-size:14px;font-weight:bold;color:#fff;vertical-align:middle;">PAGE NO:01</td>
 </tr>
     </table>-
</div>

</div>


<!-- ============================================================ -->
<!--  PAGE 2 — CLIENT INFO  (fixed height)                       -->
<!-- ============================================================ -->
<div class="page-fixed">

    <div class="header-wrap">
        <img class="header-bg" src="{{ public_path('img/svgviewer-png-output (1).png') }}" width="794" alt="Header">
        <table style="position:absolute;top:0;left:0;width:100%;height:100%;border-collapse:collapse;">
            <tr>
                <td class="logo-cell">
                    <img src="{{ public_path('img/gingertree-logo.png') }}" alt="Logo" style="height:70px;width:auto;display:block;">
                </td>
                <td style="vertical-align:top;text-align:right;padding-top:8px;padding-right:10px;">
                    <span style="font-size:11px;font-weight:bold;color:#000;">www.Gingertree.in</span>
                </td>
                <td class="invoice-word-cell">
                    <span class="invoice-word">INVOICE</span>
                </td>
            </tr>
        </table>
    </div>

    <div class="subject-bar">
        <p>SUB: ESTIMATE FOR WOOD WORKS, ACCESSORIES AND BEAUTIFICATION</p>
    </div>

    <img src="{{ public_path('img/pic-1-interior-design-of-modern-apartment-with-colorful-dark.jpg') }}"
         alt="Interior" style="width:722px;margin:0 36px 22px 36px;display:block;height:280px;object-fit:cover;">

    <table class="info-table">
        <colgroup>
            <col style="width:90px;">
            <col style="width:190px;">
            <col style="width:110px;">
            <col>
        </colgroup>
        <tbody>
            <tr>
                <td class="label">Date:</td>
                <td class="value">{{ \Carbon\Carbon::today()->format('d.m.Y') }}</td>
                <td class="label-r">Design<br>Consultant:</td>
                <td class="value-r">Girish</td>
            </tr>
            <tr>
                <td class="label">Client:</td>
                <td class="value">{{ $lead->client_name ?? '' }}</td>
                <td class="label-r">Creative<br>Designer</td>
                <td class="value-r">{{ $estimation->designer ?? '' }}</td>
            </tr>
            <tr>
                <td class="label">Site Address:</td>
                <td class="value">{{ $lead->location ?? '' }}</td>
                <td class="label-r">Project<br>Manager :</td>
                <td class="value-r">{{ $estimation->project_manager ?? '' }}</td>
            </tr>
            <tr>
                <td class="label">Mobile<br>India:</td>
                <td class="value">{{ $lead->phone ?? '' }}</td>
                <td class="label-r">Factory<br>Manager</td>
                <td class="value-r">{{ $estimation->factory_manager ?? '' }}</td>
            </tr>
            <tr>
                <td class="label">Mobile<br>Abroad:</td>
                <td class="value">{{ $lead->alternate_phone ?? '' }}</td>
                <td class="label-r">Customer Care:</td>
                <td class="value-r">+91 7402 990000</td>
            </tr>
        </tbody>
    </table>

    <table class="footer-contacts-table">
        <tr>
            <td class="ficon-cell"><img src="{{ public_path('img/phone.png') }}" alt="phone"></td>
            <td class="ftext-cell">+91 7402 99 0000</td>
        </tr>
        <tr>
            <td class="ficon-cell"><img src="{{ public_path('img/message.png') }}" alt="email"></td>
            <td class="ftext-cell">mail@gingertree.in</td>
        </tr>
        <tr>
            <td class="ficon-cell"><img src="{{ public_path('img/map.png') }}" alt="location"></td>
            <td class="ftext-cell">Jawahar Rd, Near Gandhi Square, Poonithura, Petta, Kochi, Ernakulam, Kerala 682038</td>
        </tr>
    </table>

    <!--<div class="footer-bar-wrap">-->
    <!--    <img src="{{ public_path('img/svgviewer-png-output (8).png') }}" style="width:100%;display:block;">-->
    <!--    <table style="position:absolute;top:0;left:0;width:100%;height:100%;border-collapse:collapse;">-->
    <!--        <tr>-->
    <!--            <td>&nbsp;</td>-->
    <!--            <td style="width:15%;text-align:center;font-size:14px;font-weight:bold;color:#fff;vertical-align:middle;">PAGE NO:02</td>-->
    <!--        </tr>-->
    <!--    </table>-->
    <!--</div>-->

</div>


<!-- ============================================================ -->
<!--  PAGE 3 — ITEM TABLES                                       -->
<!--  Uses .page-auto → grows with content, no blank pages       -->
<!--  Header is at top, footer is at bottom after content        -->
<!-- ============================================================ -->
<div class="page-auto">

    <div class="header-wrap">
        <img class="header-bg" src="{{ public_path('img/svgviewer-png-output (1).png') }}" width="794" alt="Header">
        <table style="position:absolute;top:0;left:0;width:100%;height:100%;border-collapse:collapse;">
            <tr>
                <td class="logo-cell">
                    <img src="{{ public_path('img/gingertree-logo.png') }}" alt="Logo" style="height:70px;width:auto;display:block;">
                </td>
                <td style="vertical-align:top;text-align:right;padding-top:8px;padding-right:10px;">
                    <span style="font-size:11px;font-weight:bold;color:#000;">www.Gingertree.in</span>
                </td>
                <td class="invoice-word-cell">
                    <span class="invoice-word">INVOICE</span>
                </td>
            </tr>
        </table>
    </div>

    <div class="body-pad">

        @php
            $grandTotal = 0;

            $generalItems = array_values(array_filter($items, fn($i) => ($i['section'] ?? '') === 'General'));
            $serviceHeaders = array_values(array_filter($items, fn($i) => ($i['section'] ?? '') === 'Service'));
            $serviceSubItems = array_filter($items, fn($i) => ($i['section'] ?? '') === 'ServiceItem');

            $serviceGroups = [];

            foreach ($serviceHeaders as $sh) {
            $gk = $sh['service_id'] ?? 0;
            $serviceGroups[$gk] = [
            'service_name' => $sh['service_name'] ?? $sh['item_name'] ?? '-',
            'note' => $sh['service_note'] ?? '',
            'base_price' => floatval($sh['service_base_price'] ?? 0),
            'sub_items' => [],
            'accessories' => $sh['accessories'] ?? [],
            ];
            }

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
            // ✅ sub-items offer price sum = service total
            $svcTotal = array_sum(array_map(
            fn($s) => floatval($s['offer_price'] ?? $s['unit_price'] ?? 0),
            $group['sub_items']
            ));
            $grandTotal += $svcTotal;

            $mrpSum = array_sum(array_map(
            fn($s) => floatval($s['mrp'] ?? $s['unit_price'] ?? 0),
            $group['sub_items']
            ));
            @endphp
            <table class="mk-table">
                <thead>
                    <tr>
                        <th colspan="7" style="background:#F9AF43;color:#23272E;text-align:center;padding:7px 12px;font-size:14.9px;letter-spacing:0.8px;">{{ strtoupper($group['service_name']) }}</th>
                    </tr>
                    <tr>
                        <th class="left" style="width:34px;">SL<br>No.</th>
                        <th class="left" style="width:175px;">ITEMS</th>
                        <th style="width:72px;">MATERIAL</th>
                        <th style="width:24px;">UNIT</th>
                        <!-- <th style="width:24px;">QTY</th> -->
                        <th style="width:82px;">SIZE</th>
                        <th style="width:68px;">MRP</th>
                        <th style="width:72px;">OFFER PRICE</th>
                    </tr>
                </thead>
                <tbody>
                    @if(!empty($group['note']))
                    <tr class="desc-row">
                        <td colspan="6">{{ $group['note'] }}</td>
                    </tr>
                    @endif
                    @foreach($group['sub_items'] as $i => $si)
                    @php
                    $mrp = floatval($si['mrp'] ?? 0);
                    $offer = floatval($si['offer_price'] ?? 0);
                    $size = $si['size'] ?? '';
                    @endphp
                    <tr>
                        <td>{{ $i+1 }}</td>
                        <td><span class="item-name">{{ $si['item_name'] ?? '-' }}</span></td>
                        <td class="center">{{ $si['material'] ?? '-' }}</td>
                        <!-- <td class="center">{{ $si['unit'] ?? '-' }}</td> -->
                        <td class="center">{{ $si['qty'] ?? 1 }}</td>
                        <td class="center">{{ $size ?: '-' }}</td>
                        <td class="right">{{ $mrp ? number_format($mrp,0) : '-' }}</td>
                        <td class="right">{{ $offer ? number_format($offer,0) : '-' }}</td>
                    </tr>
                    @endforeach
                    <tr class="total-row">
                        <td colspan="5" class="total-label bold">TOTAL</td>
                        <td class="bold right">{{ number_format($mrpSum,0) }}</td>
                        <td class="bold right">{{ number_format($svcTotal,0) }}</td>
                    </tr>
                </tbody>
            </table>
            @endforeach
            <table class="acc-table">
                <thead>
                    <tr class="acc-header">
                        <th colspan="2" style="background:#F9AF43;color:#23272E;text-align:center;padding:7px 12px;font-size:14.9px;font-weight:800;letter-spacing:0.8px;border:1px solid #d4a035;">ACCESSORIES</th>
                    </tr>
                    <tr class="acc-col-header">
                        <th style="width:55%;text-align:left;padding:6px 12px;">Hardwares and Accessories (SPARRO)</th>
                        <th style="width:45%;text-align:center;">Models</th>
                    </tr>
                </thead>
                <tbody>

                    @php
                    $accTotal = 0;
                    $accOfferTotal = 0;
                    @endphp

                    @foreach($serviceGroups as $group)
                    @if(!empty($group['accessories']))

                    @foreach($group['accessories'] as $acc)

                    @php
                    $price = floatval($acc['price'] ?? 0);
                    $offer = floatval($acc['offer_price'] ?? $price);

                    $accTotal += $price;
                    $accOfferTotal += $offer;
                    @endphp

                    <tr>
                        <td>
                            <strong>{{ $acc['name'] }}</strong>
                            ({{ $acc['size'] }}) - {{ $acc['qty'] }}{{ $acc['unit'] }}
                        </td>

                        <td style="text-align:center;">
                            <img src="{{ public_path($acc['image']) }}" width="120">
                        </td>
                    </tr>

                    @endforeach

                    @endif
                    @endforeach

                    {{-- ✅ TOTALS --}}
                    <tr style="font-weight:bold;">
                        <td style="text-align:right;">TOTAL</td>
                        <td style="text-align:center;">{{ number_format($accTotal, 0) }}</td>
                    </tr>

                    <tr style="font-weight:bold;">
                        <td style="text-align:right;">OFFER RATE</td>
                        <td style="text-align:center;">{{ number_format($accOfferTotal, 0) }}</td>
                    </tr>

                </tbody>
            </table>
    </div>

    <!-- Footer sits naturally after content — no blank gap -->
    <!--<div class="footer-bar-wrap">-->
    <!--    <img src="{{ public_path('img/svgviewer-png-output (6).png') }}" style="width:100%;display:block;">-->
    <!--    <table style="position:absolute;top:0;left:0;width:100%;height:100%;border-collapse:collapse;">-->
    <!--        <tr>-->
    <!--            <td>&nbsp;</td>-->
    <!--            <td style="width:15%;text-align:center;font-size:14px;font-weight:bold;color:#fff;vertical-align:middle;">PAGE NO:03</td>-->
    <!--        </tr>-->
    <!--    </table>-->
    <!--</div>-->

</div>

<div class="page-fixed">

    <div class="body-p6">

        <table class="grand-total-table">
@php
$grandMrpTotal = 0;
$grandOfferTotal = 0;

// 1️⃣ Sum sub-items
foreach ($serviceGroups as $group) {
    foreach ($group['sub_items'] as $si) {
        $grandMrpTotal += floatval($si['unit_price'] ?? 0); // MRP
        $grandOfferTotal += floatval($si['offer_price'] ?? $si['unit_price'] ?? 0); // Offer
    }
}

// 2️⃣ Sum accessories
foreach ($serviceGroups as $group) {
    if (!empty($group['accessories'])) {
        foreach ($group['accessories'] as $acc) {
            $grandMrpTotal += floatval($acc['price'] ?? 0);
            $grandOfferTotal += floatval($acc['offer_price'] ?? $acc['price'] ?? 0);
        }
    }
}
$savedAmount = $grandMrpTotal - $grandOfferTotal;
@endphp
    <tbody>
        <tr>
            <td class="gt-label" rowspan="2">TOTAL</td>
            <td class="gt-col-mrp" style="border-bottom:none;padding-bottom:3px;">MRP</td>
            <td class="gt-col-offer" rowspan="2" style="vertical-align:middle;">
                <span class="gt-header-offer">OFFER<br>PRICE</span>
            </td>
        </tr>
        <tr>
            <td style="border:1px solid #c8c8c8;text-align:right;font-weight:700;font-size:13.3px;padding:6px 12px;border-top:none;">
                {{ number_format($grandMrpTotal,0) }}
            </td>
        </tr>
        <tr>
            <td style="border:1px solid #c8c8c8;"></td>
            <td style="border:1px solid #c8c8c8;"></td>
            <td style="border:1px solid #c8c8c8;text-align:right;font-weight:800;font-size:20.8px;padding:8px 12px;color:#23272E;">
                {{ number_format($grandOfferTotal,0) }}
            </td>
        </tr>
    </tbody>
</table>
        <div class="gst-row">GST 18 % As per the Invoicing Amount</div>

        <table class="saved-table">

            <tr>
                <td class="saved-label">You Saved through Gingertree</td>
                <td class="saved-value">{{ number_format($savedAmount, 0) }}</td>
            </tr>
        </table>

        <div class="terms-block">
            <div class="terms-title">Payment Terms:</div>
            <ol type="a">
                <li>Advance Payment : 50% of Total Project Cost</li>
                <li>Part Payment : Balance payment (Except Rs.10,000/-) at the time of material deliver.</li>
                <li>Final payment : Balance Rs. 10,000/- at the time of site hand over.</li>
            </ol>
        </div>
        <div class="terms-block">
            <div class="terms-title">Rate Includes:</div>
            <ol type="a">
                <li>Production</li>
                <li>Transportation</li>
                <li>Installation</li>
            </ol>
        </div>
        <div class="terms-block">
            <div class="terms-title">Gingertree Interior's Free Services:</div>
            <ol type="a">
                <li>Engineers, Designers Site Visit.</li>
                <li>Experts Consultation about your project.</li>
                <li>Customized 3D, 2D Interior Designing.</li>
                <li>Estimate.</li>
            </ol>
        </div>
    </div>

    <!--<div class="footer-bar-wrap">-->
    <!--    <img src="{{ public_path('img/svgviewer-png-output (5).png') }}" style="width:100%;display:block;">-->
    <!--    <table style="position:absolute;top:0;left:0;width:100%;height:100%;border-collapse:collapse;">-->
    <!--        <tr>-->
    <!--            <td>&nbsp;</td>-->
    <!--            <td style="width:15%;text-align:center;font-size:14px;font-weight:bold;color:#fff;vertical-align:middle;">PAGE NO:06</td>-->
    <!--        </tr>-->
    <!--    </table>-->
    <!--</div>-->

</div>


<!-- ============================================================ -->
<!--  PAGE 7 — TERMS & CLOSING  (fixed height)                   -->
<!-- ============================================================ -->
<div class="page-fixed">

    <div class="header-wrap">
        <img class="header-bg" src="{{ public_path('img/svgviewer-png-output (1).png') }}" width="794" alt="Header">
        <table style="position:absolute;top:0;left:0;width:100%;height:100%;border-collapse:collapse;">
            <tr>
                <td class="logo-cell">
                    <img src="{{ public_path('img/gingertree-logo.png') }}" alt="Logo" style="height:70px;width:auto;display:block;">
                </td>
                <td style="vertical-align:top;text-align:right;padding-top:8px;padding-right:10px;">
                    <span style="font-size:11px;font-weight:bold;color:#000;">www.Gingertree.in</span>
                </td>
                <td class="invoice-word-cell">
                    <span class="invoice-word">INVOICE</span>
                </td>
            </tr>
        </table>
    </div>

    <div class="body-p7">

        <div>
            <div class="section-title-p7">Value Added Services:</div>
            <div class="section-text-p7">We can provide below following extra services like;</div>
            <ul class="alpha-list">
                <li>Granite &amp; Wall tile Laying</li>
                <li>Painting, Wallpapers, Wooden floor.</li>
                <li>Electrical &amp; Plumbing</li>
                <li>LED Lighting</li>
                <li>Curios &amp; Bedroom Accessories</li>
                <li>Curtain &amp; Blind</li>
            </ul>
        </div>

        <div>
            <div class="notes-label">Notes :</div>
            <div class="notes-subtitle">Terms &amp; Conditions for Undertaking Interior Work</div>
            <ul class="bullet-list">
                <li>&#9658; Estimate Validity - Valid for 2 Weeks from the Date Given on Estimate</li>
                <li>&#9658; Items Specified in Estimate only will be Delivered by us, the Ones Shown in the 3D is just for the Appearance Purpose.</li>
                <li>&#9658; No Change in color, pattern, etc. will be entertained once the order is confirmed.</li>
                <li>&#9658; No refund will be made in case the order is cancelled after confirmation.</li>
                <li>&#9658; Changes in the Specification or design will change the Quotation</li>
                <li>&#9658; The Client Should be Responsible for the Rules &amp; Regulations of Apartment Association, Municipal, Electrical &amp; Other Necessary Authorities for the Execution of Work at the Site.</li>
                <li>&#9658; Final Measurement Will take only after Flooring Work.</li>
                <li>&#9658; Token Amount is not Refundable.</li>
                <li>&#9658; Electrical &amp; plumping work should be provided by the party, if assigned to us, its rate will be provided at design stage as per requirement.</li>
            </ul>
        </div>

        <div style="margin-bottom:16px;">
            <div class="closing-sincerely">Yours sincerely,</div>
            <div class="closing-company">Gingertree Interiors</div>
            <div style="height:5px;"></div>
            <div class="closing-iso">ISO 9001:2015 QUALITY CERTIFIED COMPANY</div>
            <div style="height:5px;"></div>
            <div class="closing-tagline">Happy Life Begins Here...</div>
        </div>

        <table style="width:100%;border-collapse:collapse;">
            <tr>
                <td style="width:80px;text-align:center;vertical-align:bottom;padding:4px;">
                    <img src="{{ public_path('img/9861902.png') }}" alt="" width="72" height="72">
                </td>
                <td style="width:80px;text-align:center;vertical-align:bottom;padding:4px;">
                    <img src="{{ public_path('img/GINGERICONS-09.png') }}" alt="" width="72" height="72">
                </td>
                <td style="width:80px;text-align:center;vertical-align:bottom;padding:4px;">
                    <img src="{{ public_path('img/GINGERICONS-08.png') }}" alt="" width="72" height="72">
                </td>
                <td style="vertical-align:bottom;">&nbsp;</td>
                <td style="width:94px;text-align:right;vertical-align:bottom;padding:4px;">
                    <img src="{{ public_path('img/QR-08.jpg.png') }}" alt="QR Code" width="86" height="86">
                </td>
            </tr>
        </table>
        <div class="plant-text">FOR EVERY PROJECT COMPLETE, WE PLANT A TREE</div>
    </div>

    <!--<div class="footer-bar-wrap">-->
    <!--    <img src="{{ public_path('img/svgviewer-png-output (4).png') }}" style="width:100%;display:block;">-->
    <!--    <table style="position:absolute;top:0;left:0;width:100%;height:100%;border-collapse:collapse;">-->
    <!--        <tr>-->
    <!--            <td>&nbsp;</td>-->
    <!--            <td style="width:15%;text-align:center;font-size:14px;font-weight:bold;color:#fff;vertical-align:middle;">PAGE NO:07</td>-->
    <!--        </tr>-->
    <!--    </table>-->
    <!--</div>-->

</div>

</body>
</html>
