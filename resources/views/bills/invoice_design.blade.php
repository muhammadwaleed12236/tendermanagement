<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $templateOptions[$activeTemplate]['name'] ?? 'Commercial Invoice' }} - Bill # {{ $bill->bill_number }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Playfair+Display:wght@700;900&family=Plus+Jakarta+Sans:wght@500;700;800&family=Space+Grotesk:wght@500;700&display=swap" rel="stylesheet">
    
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background: #f1f5f9;
            color: #1e293b;
            padding: 24px;
            font-size: 13px;
        }

        /* Top Action Bar */
        .no-print {
            max-width: 900px;
            margin: 0 auto 20px auto;
            background: #ffffff;
            padding: 12px 18px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            border: 1px solid #e2e8f0;
        }
        .btn-group {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 7px 14px;
            font-size: 12px;
            font-weight: 700;
            border-radius: 8px;
            text-decoration: none;
            cursor: pointer;
            border: none;
            transition: all 0.15s;
        }
        .btn-primary { background: #0f172a; color: #fff; }
        .btn-primary:hover { background: #1e293b; }
        .btn-outline { background: #fff; color: #334155; border: 1px solid #cbd5e1; }
        .btn-outline:hover { background: #f8fafc; }
        .select-pill {
            padding: 6px 10px;
            font-size: 12px;
            font-weight: 700;
            border-radius: 8px;
            border: 1px solid #cbd5e1;
            background: #fff;
            color: #0f172a;
            cursor: pointer;
            outline: none;
        }

        /* Invoice Paper Standard Container */
        .invoice-sheet {
            max-width: 860px;
            margin: 0 auto;
            background: #fff;
            box-shadow: 0 10px 25px -5px rgba(0,0,0,0.1), 0 8px 10px -6px rgba(0,0,0,0.05);
            border-radius: 4px;
            overflow: hidden;
            position: relative;
            min-height: 1100px;
        }

        @include('bills.partials.invoice_templates_css')
        @media print {
            body { background: #fff; padding: 0; }
            .no-print { display: none !important; }
            .invoice-sheet {
                box-shadow: none;
                border: none;
                width: 100%;
                max-width: 100%;
                min-height: auto;
            }
        }
    </style>
</head>
<body>

    <!-- Top Action Bar (Live Controls for Design Pattern, Company & Rates) -->
    <div class="no-print">
        <div class="btn-group">
            <a href="{{ route('bills.show', $bill) }}" class="btn btn-outline">&larr; Back to Bill</a>
            <button onclick="window.print()" class="btn btn-primary">
                🖨️ Print Invoice
            </button>
        </div>

        <!-- Live Interactive Selectors -->
        <div class="btn-group">
            <!-- 1. Design Pattern Selector -->
            <div style="display: flex; align-items: center; gap: 5px;">
                <span style="font-size: 11px; font-weight: 800; color: #475569;">PATTERN:</span>
                <select onchange="changeTemplate(this.value)" class="select-pill" style="border-color: #6366f1; color: #4338ca; background: #eef2ff;">
                    @foreach($templateOptions as $k => $opt)
                        <option value="{{ $k }}" {{ $activeTemplate === $k ? 'selected' : '' }}>
                            {{ $opt['name'] }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- 2. Company Selector -->
            <div style="display: flex; align-items: center; gap: 5px;">
                <span style="font-size: 11px; font-weight: 800; color: #475569;">COMPANY:</span>
                <select onchange="changeCompany(this.value)" class="select-pill">
                    @foreach($allCompanies as $c)
                        <option value="{{ $c->id }}" {{ $company && $company->id == $c->id ? 'selected' : '' }}>
                            {{ $c->company_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- 3. Rate Markup Selector -->
            <div style="display: flex; align-items: center; gap: 5px;">
                <span style="font-size: 11px; font-weight: 800; color: #475569;">RATE:</span>
                <select onchange="changeMarkup(this.value)" class="select-pill">
                    <option value="0" {{ $markupPercent == 0 ? 'selected' : '' }}>Original (0%)</option>
                    <option value="5" {{ $markupPercent == 5 ? 'selected' : '' }}>+5% Rate (Q2)</option>
                    <option value="8" {{ $markupPercent == 8 ? 'selected' : '' }}>+8% Rate (Q3)</option>
                </select>
            </div>
        </div>
    </div>


    <!-- ========================================================
         INVOICE SHEET (DYNAMICALLY RENDERED BY SELECTED PATTERN)
         ======================================================== -->
    <div class="invoice-sheet">
        @include('bills.partials.invoice_template_body')

    </div>

    <!-- Live Switching JavaScript -->
    <script>
        function changeTemplate(tpl) {
            const url = new URL(window.location.href);
            url.searchParams.set('template', tpl);
            window.location.href = url.toString();
        }

        function changeCompany(compId) {
            const url = new URL(window.location.href);
            url.searchParams.set('company_id', compId);
            window.location.href = url.toString();
        }

        function changeMarkup(m) {
            const url = new URL(window.location.href);
            url.searchParams.set('markup', m);
            window.location.href = url.toString();
        }
    </script>
</body>
</html>
