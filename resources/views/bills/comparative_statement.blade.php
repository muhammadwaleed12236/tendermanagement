<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comparative Statement (3 Quotations) - Bill # {{ $bill->bill_number }}</title>
    <style>
        * {
            box-sizing: border-box;
        }
        body {
            font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
            color: #0f172a;
            background: #fff;
            margin: 0;
            padding: 24px;
            font-size: 12px;
        }
        .no-print {
            background: #f1f5f9;
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border: 1px solid #cbd5e1;
        }
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 14px;
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
        .btn-group {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        .cs-paper {
            max-width: 1050px;
            margin: 0 auto;
            border: 1px solid #94a3b8;
            padding: 30px 36px;
            background: #fff;
        }

        .cs-header {
            text-align: center;
            border-bottom: 2px solid #0f172a;
            padding-bottom: 12px;
            margin-bottom: 16px;
        }
        .cs-title {
            font-size: 18px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .cs-dept {
            font-size: 14px;
            font-weight: bold;
            color: #334155;
            margin-top: 4px;
        }
        .cs-sub {
            font-size: 11px;
            color: #64748b;
            margin-top: 2px;
        }

        .cs-meta-grid {
            display: flex;
            justify-content: space-between;
            margin-bottom: 16px;
            font-size: 12px;
        }

        /* Matrix Table */
        .cs-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11.5px;
            margin-bottom: 20px;
        }
        .cs-table th, .cs-table td {
            border: 1px solid #64748b;
            padding: 6px 8px;
        }
        .cs-table th {
            background: #f1f5f9;
            text-align: center;
            font-weight: bold;
        }
        .l1-head {
            background: #dcfce7 !important;
            color: #14532d;
            border-bottom: 2px solid #16a34a !important;
        }
        .l1-cell {
            background: #f0fdf4;
            font-weight: bold;
        }

        .recommendation-box {
            border: 2px solid #16a34a;
            background: #f0fdf4;
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 30px;
            font-size: 12px;
            line-height: 1.5;
        }

        .signatures {
            margin-top: 50px;
            display: flex;
            justify-content: space-between;
            gap: 20px;
        }
        .sig-block {
            text-align: center;
            flex: 1;
            border-top: 1px solid #0f172a;
            padding-top: 6px;
            font-size: 11px;
            font-weight: bold;
            line-height: 1.4;
        }

        @media print {
            .no-print {
                display: none !important;
            }
            body {
                padding: 0;
            }
            .cs-paper {
                border: none;
                width: 100%;
                max-width: 100%;
                padding: 10px 0;
            }
        }
    </style>
</head>
<body>

    <!-- Top Action Bar -->
    <div class="no-print">
        <div class="btn-group">
            <a href="{{ route('bills.show', $bill) }}" class="btn btn-outline">&larr; Back to Bill</a>
            <button onclick="window.print()" class="btn btn-primary">
                🖨️ Print Comparative Statement
            </button>
        </div>

        @if(isset($allCompanies) && $allCompanies->count() > 1)
        <!-- 3-Vendor Company Selectors -->
        <div class="btn-group" style="align-items: center; background: #fff; padding: 6px 12px; border-radius: 8px; border: 1px solid #cbd5e1; gap: 8px;">
            <span style="font-size: 11px; font-weight: bold; color: #475569;">COMPANIES:</span>
            
            <div style="display: flex; align-items: center; gap: 4px;">
                <span style="font-size: 10px; font-weight: bold; color: #047857;">V1:</span>
                <select id="cs_c1" onchange="refreshCsCompanies()" style="padding: 4px 8px; font-size: 11px; font-weight: bold; border-radius: 4px; border: 1px solid #cbd5e1; background: #f0fdf4; color: #065f46;">
                    @foreach($allCompanies as $c)
                        <option value="{{ $c->id }}" {{ ($company1 && $company1->id == $c->id) ? 'selected' : '' }}>
                            {{ $c->company_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div style="display: flex; align-items: center; gap: 4px;">
                <span style="font-size: 10px; font-weight: bold; color: #475569;">V2:</span>
                <select id="cs_c2" onchange="refreshCsCompanies()" style="padding: 4px 8px; font-size: 11px; font-weight: bold; border-radius: 4px; border: 1px solid #cbd5e1; background: #f8fafc; color: #334155;">
                    @foreach($allCompanies as $c)
                        <option value="{{ $c->id }}" {{ ($company2 && $company2->id == $c->id) ? 'selected' : '' }}>
                            {{ $c->company_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div style="display: flex; align-items: center; gap: 4px;">
                <span style="font-size: 10px; font-weight: bold; color: #475569;">V3:</span>
                <select id="cs_c3" onchange="refreshCsCompanies()" style="padding: 4px 8px; font-size: 11px; font-weight: bold; border-radius: 4px; border: 1px solid #cbd5e1; background: #f8fafc; color: #334155;">
                    @foreach($allCompanies as $c)
                        <option value="{{ $c->id }}" {{ ($company3 && $company3->id == $c->id) ? 'selected' : '' }}>
                            {{ $c->company_name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
        @endif

        <div class="btn-group">
            <span style="font-size: 11px; font-weight: bold; color: #64748b;">DOCUMENTS:</span>
            <a href="{{ route('bills.sales-tax-invoice', $bill) }}" class="btn btn-outline">Sales Tax Invoice</a>
            <a href="{{ route('bills.quotation', [$bill, 1]) }}" class="btn btn-outline">Quotation 1</a>
            <a href="{{ route('bills.quotation', [$bill, 2]) }}" class="btn btn-outline">Quotation 2</a>
            <a href="{{ route('bills.quotation', [$bill, 3]) }}" class="btn btn-outline">Quotation 3</a>
            <a href="{{ route('bills.invoice-design', $bill) }}" class="btn btn-outline" style="color: #0056d2; font-weight: bold; border-color: #bfdbfe; background: #eff6ff;">🎨 Invoice Designs (7 Patterns)</a>
        </div>
    </div>

    <!-- Comparative Statement Sheet -->
    <div class="cs-paper">
        <div class="cs-header">
            <div class="cs-title">Comparative Statement of Quotations</div>
            <div class="cs-dept">{{ $bill->department ? ($bill->department->cost_center_description ?: $bill->department->name) : 'The Directorate of Transit Trade Karachi' }}</div>
            <div class="cs-sub">Procurement of Store / Office Supplies under Bill / Tender Reference # {{ $bill->bill_number }}</div>
        </div>

        <div class="cs-meta-grid">
            <div>
                <strong>Dated:</strong> {{ $bill->invoice_date->format('d/m/Y') }}<br>
                <strong>Head of Account:</strong> {{ $bill->head_of_account ?: 'Cost of Other Stores' }}
            </div>
            <div style="text-align: right;">
                <strong>Evaluation Rule:</strong> Most Advantageous Lowest Bidder (L1)<br>
                <strong>Total Quotations Evaluated:</strong> 03 (Three)
            </div>
        </div>

        <!-- 3-Vendor Matrix Table -->
        <table class="cs-table">
            <thead>
                <tr>
                    <th rowspan="2" style="width: 4%;">#</th>
                    <th rowspan="2" style="width: 28%; text-align: left;">Particulars of Item</th>
                    <th rowspan="2" style="width: 6%;">Qty</th>
                    <th colspan="2" class="l1-head" style="width: 20%;">
                        1. {{ $vendor1Name }}<br>
                        <span style="font-size: 10px; font-weight: normal;">(Quotation 1 - Lowest L1)</span>
                        @if(!empty($company1?->ntn))
                            <div style="font-size: 9px; font-weight: normal; color: #065f46;">NTN: {{ $company1->ntn }}</div>
                        @endif
                    </th>
                    <th colspan="2" style="width: 21%;">
                        2. {{ $vendor2Name }}<br>
                        <span style="font-size: 10px; font-weight: normal;">(Quotation 2)</span>
                        @if(!empty($company2?->ntn))
                            <div style="font-size: 9px; font-weight: normal; color: #64748b;">NTN: {{ $company2->ntn }}</div>
                        @endif
                    </th>
                    <th colspan="2" style="width: 21%;">
                        3. {{ $vendor3Name }}<br>
                        <span style="font-size: 10px; font-weight: normal;">(Quotation 3)</span>
                        @if(!empty($company3?->ntn))
                            <div style="font-size: 9px; font-weight: normal; color: #64748b;">NTN: {{ $company3->ntn }}</div>
                        @endif
                    </th>
                </tr>
                <tr>
                    <th class="l1-head">Rate</th>
                    <th class="l1-head">Total</th>
                    <th>Rate</th>
                    <th>Total</th>
                    <th>Rate</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($items as $idx => $it)
                    <tr>
                        <td style="text-align: center; color: #64748b;">{{ $idx + 1 }}</td>
                        <td>{{ $it['name'] }}</td>
                        <td style="text-align: center;">{{ number_format($it['qty']) }}</td>

                        <!-- Vendor 1 (L1) -->
                        <td class="l1-cell" style="text-align: right; font-family: monospace;">{{ number_format($it['rate1'], 2) }}</td>
                        <td class="l1-cell" style="text-align: right; font-family: monospace;">{{ number_format($it['tot1'], 2) }}</td>

                        <!-- Vendor 2 (+5%) -->
                        <td style="text-align: right; font-family: monospace;">{{ number_format($it['rate2'], 2) }}</td>
                        <td style="text-align: right; font-family: monospace;">{{ number_format($it['tot2'], 2) }}</td>

                        <!-- Vendor 3 (+8%) -->
                        <td style="text-align: right; font-family: monospace;">{{ number_format($it['rate3'], 2) }}</td>
                        <td style="text-align: right; font-family: monospace;">{{ number_format($it['tot3'], 2) }}</td>
                    </tr>
                @endforeach

                <!-- Subtotal Row -->
                <tr style="font-weight: bold; background: #f8fafc;">
                    <td colspan="3" style="text-align: right;">Subtotal (Excl. Tax):</td>
                    <td colspan="2" class="l1-cell" style="text-align: right; font-family: monospace;">PKR {{ number_format($totalQ1, 2) }}</td>
                    <td colspan="2" style="text-align: right; font-family: monospace;">PKR {{ number_format($totalQ2, 2) }}</td>
                    <td colspan="2" style="text-align: right; font-family: monospace;">PKR {{ number_format($totalQ3, 2) }}</td>
                </tr>

                <!-- GST Row -->
                <tr style="background: #fafafa;">
                    <td colspan="3" style="text-align: right;">Sales Tax GST ({{ $gstRate }}%):</td>
                    <td colspan="2" class="l1-cell" style="text-align: right; font-family: monospace;">PKR {{ number_format($gst1, 2) }}</td>
                    <td colspan="2" style="text-align: right; font-family: monospace;">PKR {{ number_format($gst2, 2) }}</td>
                    <td colspan="2" style="text-align: right; font-family: monospace;">PKR {{ number_format($gst3, 2) }}</td>
                </tr>

                <!-- Grand Total Row -->
                <tr style="font-weight: 900; font-size: 12.5px; border-top: 2px solid #0f172a;">
                    <td colspan="3" style="text-align: right;">GRAND TOTAL (Incl. Tax):</td>
                    <td colspan="2" class="l1-cell" style="text-align: right; font-family: monospace; color: #15803d; font-size: 13px;">
                        PKR {{ number_format($net1, 2) }}<br>
                        <span style="font-size: 9px; color: #166534; text-transform: uppercase;">(Lowest - L1)</span>
                    </td>
                    <td colspan="2" style="text-align: right; font-family: monospace; color: #334155;">
                        PKR {{ number_format($net2, 2) }}
                    </td>
                    <td colspan="2" style="text-align: right; font-family: monospace; color: #334155;">
                        PKR {{ number_format($net3, 2) }}
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- Recommendation Box -->
        <div class="recommendation-box">
            <strong style="color: #14532d; font-size: 12.5px;">EVALUATION & RECOMMENDATION:</strong><br>
            After thorough scrutiny and comparative examination of the above 03 (three) competitive quotations received, 
            <strong>{{ $vendor1Name }}</strong> has offered the lowest evaluated competitive rate totaling 
            <strong>PKR {{ number_format($net1, 2) }}</strong> (including {{ $gstRate }}% Sales Tax). 
            The rates quoted by <strong>{{ $vendor1Name }}</strong> are reasonable, economical, and strictly in compliance with market standards and procurement regulations. 
            Therefore, it is unanimously recommended to award the purchase / supply order to <strong>{{ $vendor1Name }} (Lowest Evaluated Bidder - L1)</strong>.
        </div>

        <!-- Procurement Committee Signatures -->
        <div class="signatures">
            <div class="sig-block">
                Member - Purchase Committee<br>
                <span style="font-weight: normal; color: #64748b;">Accounts / Store Officer</span>
            </div>
            <div class="sig-block">
                Member - Technical Committee<br>
                <span style="font-weight: normal; color: #64748b;">Assistant Director / Superintendent</span>
            </div>
            <div class="sig-block">
                Chairman - Purchase Committee<br>
                <span style="font-weight: normal; color: #64748b;">Drawing & Disbursing Officer (DDO)</span>
            </div>
        </div>

    </div>

    <script>
        function refreshCsCompanies() {
            const c1 = document.getElementById('cs_c1')?.value || '';
            const c2 = document.getElementById('cs_c2')?.value || '';
            const c3 = document.getElementById('cs_c3')?.value || '';
            window.location.href = '{{ route('bills.comparative-statement', $bill) }}?c1=' + c1 + '&c2=' + c2 + '&c3=' + c3;
        }
    </script>
</body>
</html>
