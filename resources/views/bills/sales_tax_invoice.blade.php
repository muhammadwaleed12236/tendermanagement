<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Tax Invoice - Bill # {{ $bill->bill_number }}</title>
    <style>
        * {
            box-sizing: border-box;
        }
        body {
            font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
            color: #000;
            background: #fff;
            margin: 0;
            padding: 24px;
            font-size: 13px;
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

        /* Sales Tax Invoice Sheet Styling (Excel-Exact) */
        .invoice-sheet {
            max-width: 950px;
            margin: 0 auto;
            border: 1px solid #000;
            background: #fff;
        }

        .title-bar {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            padding: 8px;
            border-bottom: 1px solid #000;
            text-transform: uppercase;
            letter-spacing: 1px;
            background: #fafafa;
        }

        .meta-table {
            width: 100%;
            border-collapse: collapse;
        }
        .meta-table td {
            border: 1px solid #000;
            padding: 6px 10px;
            vertical-align: top;
            font-size: 12px;
        }
        .meta-label {
            font-weight: bold;
            width: 18%;
            background: #fafafa;
        }
        .meta-val {
            width: 32%;
        }

        .sale-row {
            padding: 6px 10px;
            border-bottom: 1px solid #000;
            font-weight: bold;
            font-size: 12px;
            background: #fafafa;
        }

        /* Items Grid (Matching Screenshot Columns) */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }
        .items-table th {
            border: 1px solid #000;
            padding: 8px 6px;
            text-align: center;
            font-weight: bold;
            background: #f8fafc;
            line-height: 1.3;
        }
        .items-table td {
            border: 1px solid #000;
            padding: 8px 6px;
            vertical-align: middle;
        }

        .col-desc { text-align: left; width: 34%; }
        .col-qty { text-align: center; width: 7%; }
        .col-total-amt { text-align: right; width: 13%; }
        .col-val-excl { text-align: right; width: 15%; }
        .col-rate { text-align: center; width: 9%; }
        .col-tax-amt { text-align: right; width: 11%; }
        .col-val-incl { text-align: right; width: 14%; }

        .total-row td {
            font-weight: bold;
            background: #f8fafc;
            border-top: 2px solid #000;
        }

        .signatures {
            margin-top: 50px;
            display: flex;
            justify-content: space-between;
            padding: 0 40px 30px 40px;
            font-size: 12px;
        }
        .sig-block {
            text-align: center;
            width: 200px;
            border-top: 1px dashed #000;
            padding-top: 6px;
            font-weight: bold;
        }

        @media print {
            .no-print {
                display: none !important;
            }
            body {
                padding: 0;
            }
            .invoice-sheet {
                border: 1px solid #000;
                width: 100%;
                max-width: 100%;
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
                🖨️ Print Sales Tax Invoice
            </button>
        </div>

        <div class="btn-group">
            <span style="font-size: 11px; font-weight: bold; color: #64748b; margin-right: 4px;">SWITCH DOCUMENT:</span>
            <a href="{{ route('bills.quotation', [$bill, 1]) }}" class="btn btn-outline">Quotation 1</a>
            <a href="{{ route('bills.quotation', [$bill, 2]) }}" class="btn btn-outline">Quotation 2</a>
            <a href="{{ route('bills.quotation', [$bill, 3]) }}" class="btn btn-outline">Quotation 3</a>
            <a href="{{ route('bills.comparative-statement', $bill) }}" class="btn btn-outline" style="color: #047857; font-weight: bold;">Comparative Statement</a>
            <a href="{{ route('bills.invoice-design', $bill) }}" class="btn btn-outline" style="color: #0056d2; font-weight: bold; border-color: #bfdbfe; background: #eff6ff;">🎨 Invoice Designs (7 Patterns)</a>
        </div>
    </div>

    <!-- Official Sales Tax Invoice Sheet (Exact Match of User's Excel Screenshot) -->
    <div class="invoice-sheet">
        <div class="title-bar">
            Sales Tax Invoice
        </div>

        <table class="meta-table">
            <tr>
                <td class="meta-label">Name of Supplier's</td>
                <td class="meta-val"><strong>{{ $bill->company ? $bill->company->company_name : ($bill->vendor ? ($bill->vendor->company_name ?: $bill->vendor->name) : 'M/S. COMZON') }}</strong></td>
                <td class="meta-label">Name of Buyer's</td>
                <td class="meta-val"><strong>{{ $bill->department ? ($bill->department->cost_center_description ?: $bill->department->name) : 'The Directorate of Transit Trade Karachi' }}</strong></td>
            </tr>
            <tr>
                <td class="meta-label">Address</td>
                <td class="meta-val">{{ $bill->company?->address ?: 'Office # 1/2 Block # 6, PECHS Nursery Karachi' }}</td>
                <td class="meta-label">Address</td>
                <td class="meta-val">{{ $bill->department ? ($bill->department->city ?: 'Karachi') : 'Karachi' }}</td>
            </tr>
            <tr>
                <td class="meta-label">Contact #.</td>
                <td class="meta-val">{{ $bill->company?->contact_number ?: ($bill->vendor?->phone ?: '0331-2449892') }}</td>
                <td class="meta-label">Contact #.</td>
                <td class="meta-val">-</td>
            </tr>
            <tr>
                <td class="meta-label">NTN #.</td>
                <td class="meta-val"><strong>{{ $bill->ntn_number ?: ($bill->company?->ntn ?: ($bill->vendor?->tax_number ?: '3437101-0')) }}</strong></td>
                <td class="meta-label">ST Registration #.</td>
                <td class="meta-val"><strong>{{ $bill->strn_number ?: ($bill->company?->gst ?: ($bill->vendor?->strn_number ?: '3277876289057')) }}</strong></td>
            </tr>
            @if(($bill->sst_number ?: $bill->company?->sst) || ($bill->agpr_vendor_no ?: $bill->company?->vendor_no))
            <tr>
                <td class="meta-label">SST / SRB #.</td>
                <td class="meta-val"><strong>{{ $bill->sst_number ?: ($bill->company?->sst ?: '-') }}</strong></td>
                <td class="meta-label">Vendor # (AGPR)</td>
                <td class="meta-val"><strong>{{ $bill->agpr_vendor_no ?: ($bill->company?->vendor_no ?: '-') }}</strong></td>
            </tr>
            @endif
            <tr>
                <td class="meta-label">Invoice #</td>
                <td class="meta-val"><strong>{{ $bill->bill_number }}</strong></td>
                <td class="meta-label">Invoice Date</td>
                <td class="meta-val">{{ $bill->invoice_date->format('d/m/Y') }}</td>
            </tr>
        </table>

        <div class="sale-row">
            Sale : General Sales Tax
        </div>

        <!-- Line Items Table -->
        <table class="items-table">
            <thead>
                <tr>
                    <th class="col-desc">Particular Of Items</th>
                    <th class="col-qty">Qty</th>
                    <th class="col-total-amt">Total Amount</th>
                    <th class="col-val-excl">Value Excluding Sales Tax</th>
                    <th class="col-rate">Rate Sales Tax</th>
                    <th class="col-tax-amt">Sales Tax Amount</th>
                    <th class="col-val-incl">Value Including Sales Tax</th>
                </tr>
            </thead>
            <tbody>
                @foreach($items as $row)
                    <tr>
                        <td class="col-desc">{{ $row['name'] }}</td>
                        <td class="col-qty">{{ number_format($row['qty']) }}</td>
                        <td class="col-total-amt font-mono">{{ number_format($row['val_incl_tax'], 2) }}</td>
                        <td class="col-val-excl font-mono">{{ number_format($row['val_excl_tax'], 2) }}</td>
                        <td class="col-rate">{{ $row['gst_rate'] }}%</td>
                        <td class="col-tax-amt font-mono">{{ number_format($row['gst_amount'], 2) }}</td>
                        <td class="col-val-incl font-mono font-bold">{{ number_format($row['val_incl_tax'], 2) }}</td>
                    </tr>
                @endforeach

                <!-- Merged Grand Total Row (Exactly as user requested) -->
                <tr class="total-row">
                    <td style="text-align: right; padding-right: 12px;"><strong>TOTAL:</strong></td>
                    <td class="col-qty"><strong>{{ number_format($totalQty) }}</strong></td>
                    <td class="col-total-amt font-mono"><strong>{{ number_format($totalInclTax, 2) }}</strong></td>
                    <td class="col-val-excl font-mono"><strong>{{ number_format($totalExclTax, 2) }}</strong></td>
                    <td class="col-rate"><strong>{{ $gstRate }}%</strong></td>
                    <td class="col-tax-amt font-mono"><strong>{{ number_format($totalGst, 2) }}</strong></td>
                    <td class="col-val-incl font-mono" style="font-size: 13px;"><strong>{{ number_format($totalInclTax, 2) }}</strong></td>
                </tr>
            </tbody>
        </table>

        <!-- Signatures Footer -->
        <div class="signatures">
            <div class="sig-block">
                Prepared By
            </div>
            <div class="sig-block">
                Checked By
            </div>
            <div class="sig-block">
                Authorized Signatory / Supplier
            </div>
        </div>
    </div>

</body>
</html>
