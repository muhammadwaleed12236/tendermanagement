<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quotation # {{ $vendorNum }} - {{ $vendorName }} - Bill # {{ $bill->bill_number }}</title>
    <style>
        * {
            box-sizing: border-box;
        }
        body {
            font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
            color: #0f172a;
            background: #f8fafc;
            margin: 0;
            padding: 24px;
            font-size: 13px;
        }
        .no-print {
            background: #ffffff;
            padding: 12px 18px;
            border-radius: 10px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border: 1px solid #cbd5e1;
            box-shadow: 0 2px 6px rgba(0,0,0,0.04);
            flex-wrap: wrap;
            gap: 12px;
        }
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 7px 14px;
            font-size: 12px;
            font-weight: bold;
            border-radius: 6px;
            text-decoration: none;
            cursor: pointer;
            border: none;
            transition: all 0.15s;
        }
        .btn-primary {
            background: #0f172a;
            color: #fff;
        }
        .btn-primary:hover {
            background: #1e293b;
        }
        .btn-outline {
            background: #fff;
            color: #334155;
            border: 1px solid #cbd5e1;
        }
        .btn-outline:hover {
            background: #e2e8f0;
        }
        .btn-active {
            background: #2563eb;
            color: #fff;
            border: 1px solid #1d4ed8;
        }
        .btn-group {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        /* Quotation Paper Container (A4 Proportioned) */
        .quotation-paper {
            max-width: 860px;
            min-height: 1130px;
            margin: 0 auto;
            border: 1px solid #cbd5e1;
            padding: 40px 48px;
            background: #fff;
            box-shadow: 0 8px 20px rgba(0,0,0,0.06);
            position: relative;
            box-sizing: border-box;
        }

        /* Custom Header & Footer Banners */
        .custom-header-banner {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 5;
        }
        .custom-header-banner img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }
        .custom-footer-banner {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 5;
        }
        .custom-footer-banner img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        /* Standard Typographic Letterhead */
        .letterhead {
            border-bottom: 3px double #0f172a;
            padding-bottom: 16px;
            margin-bottom: 24px;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }
        .company-name {
            font-size: 22px;
            font-weight: 900;
            color: #0f172a;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .company-meta {
            font-size: 11px;
            color: #475569;
            line-height: 1.5;
            margin-top: 4px;
        }
        .qtn-badge {
            text-align: right;
        }
        .qtn-title {
            font-size: 20px;
            font-weight: 900;
            color: #1e293b;
            letter-spacing: 1px;
        }
        .qtn-meta {
            font-size: 12px;
            font-family: monospace;
            margin-top: 4px;
            color: #334155;
        }

        .meta-grid {
            display: flex;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 24px;
        }
        .to-box {
            font-size: 13px;
            line-height: 1.5;
        }
        .to-title {
            font-weight: bold;
            font-size: 14px;
            color: #0f172a;
        }

        .items-table {
            width: 100%;
            margin-bottom: 24px;
            font-size: 12.5px;
        }
        .total-box {
            margin-left: auto;
            width: 320px;
            font-size: 12.5px;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 6px 12px;
        }
        .grand-total {
            font-weight: bold;
            font-size: 14px;
        }

        /* ==============================================================
           7 DISTINCT INNER BODY DESIGN PATTERNS
           ("sb ki body ki designing change hogi taky alag alag pattren lagy")
           ============================================================== */

        /* 1. CORPORATE NAVY ELEGANCE */
        .body-corporate_navy .items-table {
            border-collapse: separate;
            border-spacing: 0;
            border: 1.5px solid #1e3a8a;
            border-radius: 8px;
            overflow: hidden;
        }
        .body-corporate_navy .items-table th {
            background: #1e3a8a;
            color: #ffffff;
            font-weight: bold;
            padding: 9px 12px;
            border-right: 1px solid #2563eb;
            border-bottom: 2px solid #172554;
            font-size: 12px;
            letter-spacing: 0.5px;
        }
        .body-corporate_navy .items-table th:last-child {
            border-right: none;
        }
        .body-corporate_navy .items-table td {
            padding: 8px 12px;
            border-bottom: 1px solid #e2e8f0;
            border-right: 1px solid #f1f5f9;
            color: #1e293b;
        }
        .body-corporate_navy .items-table td:last-child {
            border-right: none;
        }
        .body-corporate_navy .items-table tr:nth-child(even) td {
            background: #f8fafc;
        }
        .body-corporate_navy .items-table tr:last-child td {
            border-bottom: none;
        }
        .body-corporate_navy .total-box {
            border: 1.5px solid #1e3a8a;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 6px rgba(30, 58, 138, 0.08);
        }
        .body-corporate_navy .total-row {
            border-bottom: 1px solid #e2e8f0;
            background: #ffffff;
        }
        .body-corporate_navy .grand-total {
            background: #1e3a8a;
            color: #ffffff;
            border-bottom: none;
            padding: 8px 12px;
        }
        .body-corporate_navy .grand-total span:last-child {
            color: #38bdf8 !important;
            font-weight: 900;
        }

        /* 2. CLASSIC MONOCHROME (GOVT / LEGAL MINIMAL) */
        .body-classic_mono .items-table {
            border-collapse: collapse;
            border-top: 2.5px solid #000000;
            border-bottom: 2.5px solid #000000;
            border-left: none;
            border-right: none;
        }
        .body-classic_mono .items-table th {
            background: #ffffff;
            color: #000000;
            font-weight: 900;
            padding: 9px 10px;
            border-bottom: 1.5px solid #000000;
            border-top: none;
            border-left: none;
            border-right: none;
            text-transform: uppercase;
            font-size: 11.5px;
            letter-spacing: 0.5px;
        }
        .body-classic_mono .items-table td {
            padding: 8px 10px;
            border-bottom: 1px solid #cbd5e1;
            border-left: none;
            border-right: none;
            color: #000000;
            background: #ffffff;
        }
        .body-classic_mono .items-table tr:last-child td {
            border-bottom: none;
        }
        .body-classic_mono .total-box {
            border-top: 1.5px solid #000000;
            border-bottom: none;
            border-left: none;
            border-right: none;
            border-radius: 0;
            width: 300px;
        }
        .body-classic_mono .total-row {
            padding: 5px 0;
            border-bottom: none;
        }
        .body-classic_mono .grand-total {
            border-top: 1px solid #000000;
            border-bottom: 3.5px double #000000;
            padding: 7px 0;
            margin-top: 4px;
            color: #000000;
            font-weight: 900;
            font-size: 14px;
        }
        .body-classic_mono .grand-total span:last-child {
            color: #000000 !important;
            font-weight: 900;
        }

        /* 3. MODERN ROUNDED SLATE TECH */
        .body-modern_slate .items-table {
            border-collapse: separate;
            border-spacing: 0;
            border: 1px solid #cbd5e1;
            border-radius: 12px;
            overflow: hidden;
        }
        .body-modern_slate .items-table th {
            background: #f1f5f9;
            color: #334155;
            font-weight: 800;
            padding: 10px 12px;
            border-bottom: 1px solid #cbd5e1;
            border-right: 1px solid #e2e8f0;
            font-size: 11.5px;
        }
        .body-modern_slate .items-table th:last-child {
            border-right: none;
        }
        .body-modern_slate .items-table td {
            padding: 9px 12px;
            border-bottom: 1px solid #f1f5f9;
            border-right: 1px solid #f8fafc;
            color: #334155;
        }
        .body-modern_slate .items-table td:last-child {
            border-right: none;
        }
        .body-modern_slate .items-table tr:hover td {
            background: #f8fafc;
        }
        .body-modern_slate .items-table tr:last-child td {
            border-bottom: none;
        }
        .body-modern_slate .total-box {
            background: #f8fafc;
            border: 1px solid #cbd5e1;
            border-radius: 12px;
            padding: 8px 12px;
            width: 310px;
        }
        .body-modern_slate .total-row {
            padding: 5px 0;
            border-bottom: 1px dashed #cbd5e1;
        }
        .body-modern_slate .grand-total {
            border-bottom: none;
            border-top: 1.5px solid #334155;
            font-weight: 900;
            font-size: 14px;
            padding-top: 8px;
            margin-top: 4px;
            color: #0f172a;
        }
        .body-modern_slate .grand-total span:last-child {
            color: #0284c7 !important;
            font-weight: 900;
        }

        /* 4. WARM CRIMSON & TERRACOTTA */
        .body-warm_crimson .items-table {
            border-collapse: collapse;
            border: 1.5px solid #991b1b;
        }
        .body-warm_crimson .items-table th {
            background: #991b1b;
            color: #ffffff;
            font-weight: bold;
            padding: 9px 10px;
            border: 1px solid #7f1d1d;
            font-size: 11.5px;
            letter-spacing: 0.5px;
        }
        .body-warm_crimson .items-table td {
            padding: 8px 10px;
            border: 1px solid #fecdd3;
            color: #4c0519;
        }
        .body-warm_crimson .items-table tr:nth-child(even) td {
            background: #fff1f2;
        }
        .body-warm_crimson .total-box {
            border: 1.5px solid #991b1b;
            border-radius: 6px;
            overflow: hidden;
        }
        .body-warm_crimson .total-row {
            border-bottom: 1px solid #fecdd3;
            background: #ffffff;
        }
        .body-warm_crimson .grand-total {
            background: #991b1b;
            color: #ffffff;
            border-bottom: none;
            padding: 8px 12px;
        }
        .body-warm_crimson .grand-total span:last-child {
            color: #fef08a !important;
            font-weight: 900;
        }

        /* 5. FOREST GREEN CONTRACTOR */
        .body-forest_green .items-table {
            border-collapse: separate;
            border-spacing: 0;
            border: 1.5px solid #065f46;
            border-radius: 8px;
            overflow: hidden;
        }
        .body-forest_green .items-table th {
            background: #065f46;
            color: #ffffff;
            font-weight: 700;
            padding: 9px 10px;
            border-right: 1px solid #047857;
            border-bottom: 1.5px solid #064e3b;
            font-size: 11.5px;
        }
        .body-forest_green .items-table th:last-child {
            border-right: none;
        }
        .body-forest_green .items-table td {
            padding: 8px 10px;
            border-bottom: 1px solid #d1fae5;
            border-right: 1px solid #e6f4ea;
            color: #064e3b;
        }
        .body-forest_green .items-table td:last-child {
            border-right: none;
        }
        .body-forest_green .items-table tr:nth-child(even) td {
            background: #f0fdf4;
        }
        .body-forest_green .items-table tr:last-child td {
            border-bottom: none;
        }
        .body-forest_green .total-box {
            border: 1.5px solid #065f46;
            border-radius: 8px;
            overflow: hidden;
        }
        .body-forest_green .total-row {
            border-bottom: 1px solid #d1fae5;
            background: #ffffff;
        }
        .body-forest_green .grand-total {
            background: #ecfdf5;
            color: #065f46;
            border-top: 1.5px solid #065f46;
            border-bottom: none;
            padding: 8px 12px;
        }
        .body-forest_green .grand-total span:last-child {
            color: #047857 !important;
            font-weight: 900;
        }

        /* 6. TRADITIONAL ACCOUNTING LEDGER */
        .body-ledger_grid .items-table {
            border-collapse: collapse;
            border: 2px solid #334155;
        }
        .body-ledger_grid .items-table th {
            background: #334155;
            color: #f8fafc;
            font-weight: 800;
            padding: 8px 10px;
            border: 1px solid #475569;
            font-size: 11.5px;
            letter-spacing: 0.5px;
        }
        .body-ledger_grid .items-table td {
            padding: 7px 10px;
            border: 1px solid #64748b;
            color: #0f172a;
            background: #ffffff;
        }
        .body-ledger_grid .total-box {
            border: 2px solid #334155;
            border-radius: 0;
            width: 310px;
        }
        .body-ledger_grid .total-row {
            padding: 6px 10px;
            border-bottom: 1px solid #64748b;
            background: #ffffff;
        }
        .body-ledger_grid .grand-total {
            background: #f1f5f9;
            color: #0f172a;
            border-top: 2px solid #334155;
            border-bottom: none;
            font-weight: 900;
            padding: 7px 10px;
        }
        .body-ledger_grid .grand-total span:last-child {
            color: #0f172a !important;
            font-weight: 900;
        }

        /* 7. MINIMALIST DOTTED LINES */
        .body-minimal_dotted .items-table {
            border-collapse: collapse;
            border-top: none;
            border-bottom: none;
            border-left: none;
            border-right: none;
        }
        .body-minimal_dotted .items-table th {
            background: transparent;
            color: #475569;
            font-weight: 700;
            padding: 8px 6px;
            border-bottom: 2px dotted #64748b;
            border-top: none;
            border-left: none;
            border-right: none;
            font-size: 11.5px;
            letter-spacing: 0.5px;
        }
        .body-minimal_dotted .items-table td {
            padding: 9px 6px;
            border-bottom: 1px dotted #94a3b8;
            border-top: none;
            border-left: none;
            border-right: none;
            color: #1e293b;
            background: transparent;
        }
        .body-minimal_dotted .items-table tr:last-child td {
            border-bottom: none;
        }
        .body-minimal_dotted .total-box {
            border-top: 1.5px dotted #64748b;
            border-bottom: none;
            border-left: none;
            border-right: none;
            border-radius: 0;
            width: 290px;
        }
        .body-minimal_dotted .total-row {
            padding: 5px 0;
            border-bottom: 1px dotted #e2e8f0;
        }
        .body-minimal_dotted .grand-total {
            border-top: 2px solid #0f172a;
            border-bottom: 1px solid #0f172a;
            font-weight: 800;
            font-size: 13.5px;
            padding: 8px 0;
            margin-top: 6px;
            color: #0f172a;
        }
        .body-minimal_dotted .grand-total span:last-child {
            color: #0f172a !important;
            font-weight: 900;
        }

        /* Integrated Table Footer (tfoot) Styling for all themes */
        .items-table tfoot td { padding: 7px 10px; }
        .body-corporate_navy .items-table tfoot { border-top: 2px solid #1e3a8a; background: #f8fafc; }
        .body-corporate_navy .items-table tfoot .grand-total-row td { background: #1e3a8a; color: #fff; font-weight: 900; }
        .body-corporate_navy .items-table tfoot .grand-total-row td:last-child { color: #38bdf8 !important; }

        .body-classic_mono .items-table tfoot { border-top: 2px solid #000; }
        .body-classic_mono .items-table tfoot td { border: none; color: #000; }
        .body-classic_mono .items-table tfoot .grand-total-row td { border-top: 1px solid #000; border-bottom: 3.5px double #000; font-weight: 900; }

        .body-modern_slate .items-table tfoot { border-top: 1.5px solid #cbd5e1; background: #f8fafc; }
        .body-modern_slate .items-table tfoot .grand-total-row td { border-top: 1.5px solid #334155; color: #0284c7; font-weight: 900; }

        .body-warm_crimson .items-table tfoot { border-top: 2px solid #991b1b; background: #fff1f2; }
        .body-warm_crimson .items-table tfoot .grand-total-row td { background: #991b1b; color: #fff; font-weight: 900; }
        .body-warm_crimson .items-table tfoot .grand-total-row td:last-child { color: #fef08a !important; }

        .body-forest_green .items-table tfoot { border-top: 2px solid #065f46; background: #f0fdf4; }
        .body-forest_green .items-table tfoot .grand-total-row td { background: #ecfdf5; color: #065f46; border-top: 1.5px solid #065f46; font-weight: 900; }

        .body-ledger_grid .items-table tfoot { border-top: 2px solid #334155; }
        .body-ledger_grid .items-table tfoot td { border: 1px solid #64748b; }
        .body-ledger_grid .items-table tfoot .grand-total-row td { background: #f1f5f9; font-weight: 900; border-top: 2px solid #334155; }

        .body-minimal_dotted .items-table tfoot { border-top: 2px dotted #64748b; }
        .body-minimal_dotted .items-table tfoot td { border: none; border-bottom: 1px dotted #e2e8f0; }
        .body-minimal_dotted .items-table tfoot .grand-total-row td { border-top: 2px solid #0f172a; border-bottom: 1px solid #0f172a; font-weight: 900; }

        .terms {
            margin-top: 30px;
            font-size: 11.5px;
            color: #475569;
            line-height: 1.6;
            border-top: 1px dashed #cbd5e1;
            padding-top: 12px;
        }

        .sig-row {
            margin-top: 60px;
            display: flex;
            justify-content: space-between;
        }
        .sig-block {
            text-align: center;
            width: 220px;
            border-top: 1px solid #0f172a;
            padding-top: 6px;
            font-weight: bold;
            font-size: 12px;
        }

        /* Stationary Mode (Hides Header/Footer images for pre-printed stationary paper) */
        body.stationary-mode .custom-header-banner,
        body.stationary-mode .custom-footer-banner,
        body.stationary-mode .letterhead {
            visibility: hidden !important;
            opacity: 0 !important;
        }
        body.stationary-mode .quotation-paper {
            background-image: none !important;
        }

        @media print {
            .no-print {
                display: none !important;
            }
            body {
                padding: 0;
                background: #fff;
            }
            .quotation-paper {
                box-shadow: none;
                border: none;
                padding: 0;
                width: 100%;
                max-width: 100%;
                min-height: auto;
            }
        }
    </style>
</head>
@php
    $bodyStyleOptions = \App\Models\Company::bodyStyleOptions();
    $requestedBodyStyle = request()->query('body_style');
    if ($requestedBodyStyle && array_key_exists($requestedBodyStyle, $bodyStyleOptions)) {
        $bodyStyle = $requestedBodyStyle;
    } else {
        $bodyStyle = $chosenCompany?->body_style;
        if (!$bodyStyle || !array_key_exists($bodyStyle, $bodyStyleOptions)) {
            if ($vendorNum == 1) {
                $bodyStyle = 'corporate_navy';
            } elseif ($vendorNum == 2) {
                $bodyStyle = 'classic_mono';
            } elseif ($vendorNum == 3) {
                $bodyStyle = 'forest_green';
            } else {
                $bodyStyle = 'modern_slate';
            }
        }
    }

    $effectiveTotalLayout = $totalLayout ?? 'auto';
    if ($effectiveTotalLayout === 'auto') {
        if ($bodyStyle === 'classic_mono' || $bodyStyle === 'ledger_grid') {
            $effectiveTotalLayout = 'in_table_footer';
        } elseif ($bodyStyle === 'modern_slate') {
            $effectiveTotalLayout = 'split_banner';
        } elseif ($bodyStyle === 'warm_crimson') {
            $effectiveTotalLayout = 'wide_bar';
        } else {
            $effectiveTotalLayout = 'card_box';
        }
    }

    $colSpan = 2; // Description + Qty
    if ($showSrColumn) $colSpan++;
    if ($showUnitColumn) $colSpan++;
    $colSpan++; // Rate
@endphp
<body class="body-{{ $bodyStyle }} {{ ($chosenCompany && !$chosenCompany->show_on_print) ? 'stationary-mode' : '' }}">

    @php
        $hasCustomHeader = $chosenCompany && $chosenCompany->letterhead_type === 'header_footer' && $chosenCompany->header_image;
        $hasCustomFooter = $chosenCompany && $chosenCompany->letterhead_type === 'header_footer' && $chosenCompany->footer_image;
        $isFullPage = $chosenCompany && $chosenCompany->letterhead_type === 'full_page' && $chosenCompany->full_letterhead_image;

        $topPadding = $hasCustomHeader ? ($chosenCompany->body_top_margin ?: 150) : ($isFullPage ? ($chosenCompany->body_top_margin ?: 150) : 40);
        $bottomPadding = $hasCustomFooter ? ($chosenCompany->body_bottom_margin ?: 90) : ($isFullPage ? ($chosenCompany->body_bottom_margin ?: 90) : 40);
        $sidePadding = ($hasCustomHeader || $isFullPage) ? ($chosenCompany->body_side_margin ?: 48) : 48;
    @endphp

    <!-- Top Action Bar -->
    <div class="no-print">
        <div class="btn-group">
            <a href="{{ route('bills.show', $bill) }}" class="btn btn-outline">&larr; Back to Bill</a>
            <button onclick="window.print()" class="btn btn-primary">
                🖨️ Print Quotation # {{ $vendorNum }}
            </button>
        </div>

        <!-- Live Company / Letterhead Selector -->
        <div class="btn-group" style="align-items: center; background: #fff; padding: 4px 10px; border-radius: 6px; border: 1px solid #cbd5e1;">
            <label for="company_picker" style="font-size: 11px; font-weight: bold; color: #475569; display: flex; align-items: center; gap: 4px;">
                <svg style="width: 14px; height: 14px; color: #2563eb;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                <span>COMPANY:</span>
            </label>
            <select id="company_picker" onchange="const u = new URL(window.location.href); u.searchParams.set('company_id', this.value); window.location.href = u.toString();" style="padding: 5px 10px; font-size: 12px; font-weight: bold; border-radius: 6px; border: 1px solid #94a3b8; background: #f8fafc; color: #0f172a; cursor: pointer; max-width: 260px;">
                @if(isset($allCompanies))
                    @foreach($allCompanies as $c)
                        <option value="{{ $c->id }}" {{ ($chosenCompany && $chosenCompany->id == $c->id) ? 'selected' : '' }}>
                            {{ $c->company_name }}
                        </option>
                    @endforeach
                @endif
            </select>

            @if($chosenCompany)
                <a href="{{ route('companies.letterhead', $chosenCompany) }}" target="_blank" title="Customize Letterhead & Body Margins for {{ $chosenCompany->company_name }}" class="btn btn-outline" style="padding: 4px 8px; font-size: 11px; color: #0056d2; border-color: #bfdbfe; background: #eff6ff;">
                    ⚙️ Setup Letterhead
                </a>
            @endif
        </div>

        <!-- Live Body Design Pattern Selector ("sb k andar ka boddy change ho") -->
        <div class="btn-group" style="align-items: center; background: #fff; padding: 4px 10px; border-radius: 6px; border: 1px solid #cbd5e1;">
            <label for="body_picker" style="font-size: 11px; font-weight: bold; color: #475569; display: flex; align-items: center; gap: 5px;">
                <span style="display: inline-block; width: 10px; height: 10px; border-radius: 50%; background-color: {{ $bodyStyleOptions[$bodyStyle]['color'] ?? '#1e3a8a' }};"></span>
                <span>BODY DESIGN:</span>
            </label>
            <select id="body_picker" onchange="const u = new URL(window.location.href); u.searchParams.set('body_style', this.value); window.location.href = u.toString();" style="padding: 5px 10px; font-size: 12px; font-weight: bold; border-radius: 6px; border: 1px solid #94a3b8; background: #f8fafc; color: #0f172a; cursor: pointer;">
                @foreach($bodyStyleOptions as $bKey => $bOpt)
                    <option value="{{ $bKey }}" {{ $bodyStyle === $bKey ? 'selected' : '' }}>
                        {{ $bOpt['name'] }} ({{ $bOpt['badge'] }})
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Column Visibility Toggles ("unit assing karna hai nh wo sb hat mai ho") -->
        <div class="btn-group" style="align-items: center; background: #fff; padding: 4px 8px; border-radius: 6px; border: 1px solid #cbd5e1;">
            <button type="button" 
                    onclick="const u = new URL(window.location.href); u.searchParams.set('unit', '{{ $showUnitColumn ? 0 : 1 }}'); window.location.href = u.toString();" 
                    class="btn {{ $showUnitColumn ? 'btn-active' : 'btn-outline' }}" 
                    style="padding: 4px 8px; font-size: 11px;"
                    title="Toggle Unit Column (Show / Hide Unit)">
                <span>Unit: {{ $showUnitColumn ? 'ON' : 'OFF' }}</span>
            </button>
            <button type="button" 
                    onclick="const u = new URL(window.location.href); u.searchParams.set('sr', '{{ $showSrColumn ? 0 : 1 }}'); window.location.href = u.toString();" 
                    class="btn {{ $showSrColumn ? 'btn-active' : 'btn-outline' }}" 
                    style="padding: 4px 8px; font-size: 11px;"
                    title="Toggle S# Column">
                <span>S#: {{ $showSrColumn ? 'ON' : 'OFF' }}</span>
            </button>
        </div>

        <!-- Totals Layout Selector ("layout change ho sakein nichy sb ka total same hai") -->
        <div class="btn-group" style="align-items: center; background: #fff; padding: 4px 10px; border-radius: 6px; border: 1px solid #cbd5e1;">
            <label for="layout_picker" style="font-size: 11px; font-weight: bold; color: #475569;">TOTALS LAYOUT:</label>
            <select id="layout_picker" onchange="const u = new URL(window.location.href); u.searchParams.set('total_layout', this.value); window.location.href = u.toString();" style="padding: 5px 8px; font-size: 12px; font-weight: bold; border-radius: 6px; border: 1px solid #94a3b8; background: #f8fafc; color: #0f172a; cursor: pointer;">
                @if(isset($totalLayoutOptions))
                    @foreach($totalLayoutOptions as $tKey => $tOpt)
                        <option value="{{ $tKey }}" {{ ($totalLayout ?? 'auto') === $tKey ? 'selected' : '' }}>
                            {{ $tOpt['name'] }}
                        </option>
                    @endforeach
                @endif
            </select>
        </div>

        <!-- Print Mode Toggle: Full Letterhead vs Blank Stationary -->
        <div class="btn-group" style="align-items: center; background: #f1f5f9; padding: 3px 6px; border-radius: 6px; border: 1px solid #cbd5e1;">
            <button type="button" 
                    id="btn_full_letterhead"
                    onclick="setPrintMode('full')" 
                    class="btn btn-active" 
                    style="padding: 5px 10px; font-size: 11px;">
                📄 With Letterhead
            </button>
            <button type="button" 
                    id="btn_stationary"
                    onclick="setPrintMode('stationary')" 
                    class="btn btn-outline" 
                    style="padding: 5px 10px; font-size: 11px;"
                    title="Hide Header & Footer images when feeding physical pre-printed stationary into printer">
                🖨️ Stationary (Body Only)
            </button>
        </div>

        <div class="btn-group">
            <span style="font-size: 11px; font-weight: bold; color: #64748b;">QUOTATIONS:</span>
            <a href="{{ route('bills.quotation', [$bill, 1]) }}" class="btn {{ $vendorNum == 1 ? 'btn-active' : 'btn-outline' }}">
                Quotation 1
            </a>
            <a href="{{ route('bills.quotation', [$bill, 2]) }}" class="btn {{ $vendorNum == 2 ? 'btn-active' : 'btn-outline' }}">
                Quotation 2
            </a>
            <a href="{{ route('bills.quotation', [$bill, 3]) }}" class="btn {{ $vendorNum == 3 ? 'btn-active' : 'btn-outline' }}">
                Quotation 3
            </a>
            <a href="{{ route('bills.sales-tax-invoice', $bill) }}" class="btn btn-outline">Sales Tax Invoice</a>
            <a href="{{ route('bills.comparative-statement', $bill) }}" class="btn btn-outline" style="color: #047857; font-weight: bold;">Comparative Statement</a>
            <a href="{{ route('bills.invoice-design', $bill) }}" class="btn btn-outline" style="color: #0056d2; font-weight: bold; border-color: #bfdbfe; background: #eff6ff;">🎨 Invoice Designs</a>
        </div>
    </div>

    <!-- Official Quotation Document -->
    <div class="quotation-paper" style="{{ $isFullPage ? 'background-image: url(' . $chosenCompany->full_letterhead_image_url . '); background-size: 100% 100%;' : '' }}">

        <!-- 1. UPLOADED CUSTOM HEADER BANNER -->
        @if($hasCustomHeader)
            <div class="custom-header-banner" style="height: {{ $chosenCompany->header_height ?: 140 }}px;">
                <img src="{{ $chosenCompany->header_image_url }}" alt="Header Letterhead">
            </div>
        @endif

        <!-- INNER DOCUMENT BODY CONTAINER (Adjusted with margins) -->
        <div style="padding-top: {{ $topPadding }}px; padding-bottom: {{ $bottomPadding }}px; padding-left: {{ $sidePadding }}px; padding-right: {{ $sidePadding }}px; position: relative; z-index: 10;">

            <!-- 2. BUILT-IN SYSTEM LETTERHEAD (When no custom header uploaded) -->
            @if(!$hasCustomHeader && !$isFullPage)
                <div class="letterhead" style="{{ $vendorNum == 2 ? 'border-bottom: 3px solid #334155;' : ($vendorNum == 3 ? 'border-bottom: 3px dashed #475569;' : 'border-bottom: 3px double #0f172a;') }}">
                    <div>
                        <div class="company-name">{{ $vendorName }}</div>
                        <div class="company-meta">
                            <div>{{ $vendorAddress }}</div>
                            <div>Phone: {{ $vendorPhone }} | NTN: {{ $vendorNtn }} | STRN: {{ $vendorStrn }}@if(!empty($vendorSst)) | SST: {{ $vendorSst }}@endif</div>
                            <div style="font-style: italic; color: #64748b; margin-top: 2px;">General Order Suppliers, Government Contractors & IT Solutions</div>
                        </div>
                    </div>

                    <div class="qtn-badge">
                        <div class="qtn-title">QUOTATION</div>
                        <div class="qtn-meta">Ref: QTN-{{ $bill->bill_number }}-0{{ $vendorNum }}</div>
                        <div class="qtn-meta">Date: {{ $bill->invoice_date->format('d/m/Y') }}</div>
                        <div style="margin-top: 4px;">
                            <span style="display: inline-block; background: #f1f5f9; color: #334155; font-size: 10px; font-weight: bold; padding: 2px 8px; border-radius: 4px; border: 1px solid #cbd5e1;">
                                Competitive Quotation # {{ $vendorNum }}
                            </span>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Addressed To & Subject -->
            <div class="meta-grid">
                <div class="to-box">
                    <div style="color: #64748b; font-size: 11px; text-transform: uppercase; font-weight: bold;">Addressed To:</div>
                    <div class="to-title">{{ $bill->department ? ($bill->department->cost_center_description ?: $bill->department->name) : 'The Directorate of Transit Trade Karachi' }}</div>
                    <div>{{ $bill->department ? ($bill->department->city ?: 'Karachi, Pakistan') : 'Karachi, Pakistan' }}</div>
                </div>

                <div style="text-align: right; font-size: 12px; color: #475569;">
                    <div><strong>Tender / Bill Ref:</strong> {{ $bill->bill_number }}</div>
                    <div><strong>Date:</strong> {{ $bill->invoice_date->format('d/m/Y') }}</div>
                    <div><strong>Valid Till:</strong> {{ $bill->invoice_date->copy()->addDays(30)->format('d/m/Y') }}</div>
                </div>
            </div>

            <!-- Custom Subject Line -->
            <div style="font-weight: bold; margin-bottom: 16px; font-size: 13px; text-decoration: underline;">
                Subject: {{ ($chosenCompany && $chosenCompany->default_subject) ? $chosenCompany->default_subject : 'Quotation for the Supply of Procurement / Store Items' }}
            </div>

            <!-- Items Table -->
            <table class="items-table">
                <thead>
                    <tr>
                        @if($showSrColumn)
                            <th style="width: 6%; text-align: center;">S#</th>
                        @endif
                        <th style="width: {{ $showUnitColumn ? ($showSrColumn ? '48%' : '55%') : ($showSrColumn ? '58%' : '65%') }}; text-align: left;">Particulars / Description of Items</th>
                        @if($showUnitColumn)
                            <th style="width: 10%; text-align: center;">Unit</th>
                        @endif
                        <th style="width: 10%; text-align: center;">Qty</th>
                        <th style="width: 12%; text-align: right;">Unit Rate (PKR)</th>
                        <th style="width: 14%; text-align: right;">Total (PKR)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $idx => $it)
                        <tr>
                            @if($showSrColumn)
                                <td style="text-align: center;">{{ $idx + 1 }}</td>
                            @endif
                            <td><strong>{{ $it['name'] }}</strong></td>
                            @if($showUnitColumn)
                                <td style="text-align: center;">{{ $it['unit'] }}</td>
                            @endif
                            <td style="text-align: center; font-family: monospace;">{{ number_format($it['qty']) }}</td>
                            <td style="text-align: right; font-family: monospace;">{{ number_format($it['unit_price'], 2) }}</td>
                            <td style="text-align: right; font-family: monospace; font-weight: bold;">{{ number_format($it['total_price'], 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                @if($effectiveTotalLayout === 'in_table_footer')
                    <tfoot>
                        <tr>
                            <td colspan="{{ $colSpan }}" style="text-align: right; font-weight: bold; padding: 7px 10px;">SUB TOTAL (PKR):</td>
                            <td style="text-align: right; font-family: monospace; font-weight: bold; padding: 7px 10px;">PKR {{ number_format($totalGross, 2) }}</td>
                        </tr>
                        <tr>
                            <td colspan="{{ $colSpan }}" style="text-align: right; padding: 6px 10px;">Sales Tax GST ({{ $gstRate }}%):</td>
                            <td style="text-align: right; font-family: monospace; padding: 6px 10px;">PKR {{ number_format($gstAmount, 2) }}</td>
                        </tr>
                        <tr class="grand-total-row">
                            <td colspan="{{ $colSpan }}" style="text-align: right; font-weight: 900; font-size: 13.5px; padding: 8px 10px;">GRAND TOTAL:</td>
                            <td style="text-align: right; font-family: monospace; font-weight: 900; font-size: 13.5px; padding: 8px 10px;">PKR {{ number_format($grandTotal, 2) }}</td>
                        </tr>
                    </tfoot>
                @endif
            </table>

            @if($effectiveTotalLayout === 'card_box')
                <!-- Layout 1: Right Floating Box Card -->
                <div class="total-box">
                    <div class="total-row">
                        <span>Items Subtotal:</span>
                        <span style="font-family: monospace; font-weight: bold;">PKR {{ number_format($totalGross, 2) }}</span>
                    </div>
                    <div class="total-row">
                        <span>Sales Tax GST ({{ $gstRate }}%):</span>
                        <span style="font-family: monospace;">PKR {{ number_format($gstAmount, 2) }}</span>
                    </div>
                    <div class="total-row grand-total">
                        <span>Grand Total:</span>
                        <span style="font-family: monospace;">PKR {{ number_format($grandTotal, 2) }}</span>
                    </div>
                </div>
            @elseif($effectiveTotalLayout === 'split_banner')
                <!-- Layout 2: Split Two-Column Layout -->
                <div style="display: flex; justify-content: space-between; gap: 24px; margin-bottom: 24px; align-items: stretch;">
                    <div style="flex: 1; background: #f8fafc; border: 1px solid #cbd5e1; border-radius: 8px; padding: 12px 16px; font-size: 11.5px; display: flex; flex-direction: column; justify-content: space-between;">
                        <div>
                            <div style="font-size: 10px; font-weight: bold; text-transform: uppercase; color: #64748b;">Amount Chargeable in Words:</div>
                            <div style="font-weight: bold; color: #0f172a; font-size: 12.5px; margin-top: 3px;">
                                PKR {{ number_format($grandTotal, 2) }} Only
                            </div>
                        </div>
                        <div style="margin-top: 10px; padding-top: 8px; border-top: 1px solid #e2e8f0; font-size: 10.5px; color: #64748b; display: flex; justify-content: space-between;">
                            <span>Validity: 30 Days</span>
                            <span>All Applicable Taxes Included</span>
                        </div>
                    </div>
                    <div style="width: 310px;">
                        <div class="total-box" style="width: 100%;">
                            <div class="total-row">
                                <span>Items Subtotal:</span>
                                <span style="font-family: monospace; font-weight: bold;">PKR {{ number_format($totalGross, 2) }}</span>
                            </div>
                            <div class="total-row">
                                <span>Sales Tax GST ({{ $gstRate }}%):</span>
                                <span style="font-family: monospace;">PKR {{ number_format($gstAmount, 2) }}</span>
                            </div>
                            <div class="total-row grand-total">
                                <span>Grand Total:</span>
                                <span style="font-family: monospace;">PKR {{ number_format($grandTotal, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            @elseif($effectiveTotalLayout === 'wide_bar')
                <!-- Layout 3: Full-Width Highlight Strip Banner -->
                <div style="margin-bottom: 24px; border: 1.5px solid #cbd5e1; border-radius: 8px; overflow: hidden; display: flex; text-align: center; background: #f8fafc;">
                    <div style="flex: 1; padding: 10px; border-right: 1px solid #cbd5e1;">
                        <div style="font-size: 10px; color: #64748b; text-transform: uppercase; font-weight: bold;">Items Subtotal</div>
                        <div style="font-family: monospace; font-size: 13px; font-weight: bold; color: #0f172a; margin-top: 2px;">PKR {{ number_format($totalGross, 2) }}</div>
                    </div>
                    <div style="flex: 1; padding: 10px; border-right: 1px solid #cbd5e1;">
                        <div style="font-size: 10px; color: #64748b; text-transform: uppercase; font-weight: bold;">Sales Tax GST ({{ $gstRate }}%)</div>
                        <div style="font-family: monospace; font-size: 13px; font-weight: bold; color: #334155; margin-top: 2px;">PKR {{ number_format($gstAmount, 2) }}</div>
                    </div>
                    <div style="flex: 1.2; padding: 10px; background: #0f172a; color: #fff; display: flex; flex-direction: column; justify-content: center;">
                        <div style="font-size: 10px; color: #94a3b8; text-transform: uppercase; font-weight: bold;">Grand Total</div>
                        <div style="font-family: monospace; font-size: 15px; font-weight: 900; color: #38bdf8; margin-top: 2px;">PKR {{ number_format($grandTotal, 2) }}</div>
                    </div>
                </div>
            @endif

            <!-- Dynamic Terms & Conditions -->
            <div class="terms">
                <strong>Terms & Conditions:</strong>
                @if($chosenCompany && $chosenCompany->default_terms)
                    <div style="margin: 4px 0 0 0; white-space: pre-line; line-height: 1.6;">{!! nl2br(e($chosenCompany->default_terms)) !!}</div>
                @else
                    <ol style="margin: 4px 0 0 16px; padding: 0;">
                        <li>Prices are inclusive of all applicable Government Taxes & 18% GST.</li>
                        <li>Delivery period: Within 7–10 days of purchase order / work order issuance.</li>
                        <li>Quotation validity: 30 days from the date of submission.</li>
                    </ol>
                @endif
            </div>

            <!-- Signatures -->
            <div class="sig-row">
                <div class="sig-block">
                    Prepared By (Sales Dept)
                </div>
                <div class="sig-block">
                    For {{ $vendorName }}<br>
                    <span style="font-size: 11px; font-weight: normal; color: #64748b;">
                        {{ ($chosenCompany && $chosenCompany->signatory_title) ? $chosenCompany->signatory_title : 'Authorized Signatory & Stamp' }}
                    </span>
                </div>
            </div>

        </div>

        <!-- 3. UPLOADED CUSTOM FOOTER BANNER -->
        @if($hasCustomFooter)
            <div class="custom-footer-banner" style="height: {{ $chosenCompany->footer_height ?: 80 }}px;">
                <img src="{{ $chosenCompany->footer_image_url }}" alt="Footer Letterhead">
            </div>
        @endif

    </div>

    <!-- Print Mode Switcher Script -->
    <script>
        function setPrintMode(mode) {
            const body = document.body;
            const btnFull = document.getElementById('btn_full_letterhead');
            const btnStat = document.getElementById('btn_stationary');

            if (mode === 'stationary') {
                body.classList.add('stationary-mode');
                btnStat.className = 'btn btn-active';
                btnFull.className = 'btn btn-outline';
            } else {
                body.classList.remove('stationary-mode');
                btnFull.className = 'btn btn-active';
                btnStat.className = 'btn btn-outline';
            }
        }
    </script>
</body>
</html>
