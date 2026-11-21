<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bill # {{ $bill->bill_number }} - Print</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            color: #111827;
            background: #fff;
            padding: 30px;
            margin: 0;
            font-size: 13px;
        }

        .no-print {
            margin-bottom: 25px;
        }

        .print-btn {
            background: #1e293b;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 13px;
            font-weight: bold;
            border-radius: 6px;
            cursor: pointer;
        }

        /* Top Header */
        .top-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 20px;
        }

        .to-section {
            font-size: 14px;
            line-height: 1.5;
        }

        .to-title {
            font-size: 16px;
            font-weight: 900;
            margin-top: 2px;
        }

        .meta-box {
            border: 2px dashed #111827;
            padding: 10px 16px;
            min-width: 260px;
            font-family: 'Courier New', Courier, monospace;
            font-size: 13px;
            line-height: 1.6;
        }

        .meta-box-header {
            display: flex;
            justify-content: space-between;
            border-bottom: 1px solid #9ca3af;
            padding-bottom: 4px;
            margin-bottom: 4px;
            font-size: 16px;
            font-weight: 900;
        }

        /* Dual Cards (Image 1 Style) */
        .info-grid {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
        }

        .info-card {
            flex: 1;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            padding: 10px 14px;
            background: #f9fafb;
            font-size: 11px;
            line-height: 1.6;
        }

        .info-card-header {
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 4px;
            margin-bottom: 6px;
            color: #1f2937;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
        }

        .info-table td {
            padding: 2px 0;
            font-family: 'Courier New', Courier, monospace;
        }

        .info-table td.lbl {
            color: #4b5563;
            width: 40%;
            font-weight: bold;
        }

        .info-table td.val {
            color: #111827;
            font-weight: 900;
        }

        /* Center Title (Image 2 Style) */
        .memo-center {
            text-align: center;
            border-top: 2px dashed #111827;
            border-bottom: 2px dashed #111827;
            padding: 8px 0;
            margin-bottom: 18px;
        }

        .memo-title {
            font-size: 18px;
            font-weight: 900;
            letter-spacing: 2px;
            margin: 0;
        }

        .memo-subhead {
            font-size: 14px;
            font-weight: bold;
            text-decoration: underline;
            margin-top: 4px;
        }

        /* Products Table (Image 2 Style) */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #111827;
            margin-bottom: 15px;
        }

        .items-table th, .items-table td {
            border: 1px solid #d1d5db;
            padding: 6px 10px;
            font-size: 12px;
        }

        .items-table th {
            background: #f3f4f6;
            color: #111827;
            font-weight: 900;
            text-transform: uppercase;
            border-bottom: 2px solid #111827;
        }

        .text-center { text-align: center; }
        .text-right { text-align: right; }

        /* Totals Box (Image 2 Style) */
        .totals-container {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 30px;
        }

        .totals-table {
            width: 280px;
            border: 2px solid #111827;
            border-collapse: collapse;
            font-size: 13px;
        }

        .totals-table td {
            padding: 6px 12px;
            border-top: 1px solid #111827;
        }

        .totals-table tr.grand {
            border-top: 2px solid #111827;
            background: #f3f4f6;
            font-weight: 900;
            font-size: 14px;
        }

        /* Signatures Section */
        .signatures {
            display: flex;
            justify-content: space-between;
            margin-top: 70px;
            padding-top: 10px;
        }

        .sig-box {
            width: 180px;
            border-top: 1px solid #6b7280;
            text-align: center;
            font-size: 11px;
            font-weight: bold;
            color: #374151;
            padding-top: 6px;
        }

        @media print {
            .no-print {
                display: none;
            }
            body {
                padding: 10px;
            }
        }
    </style>
</head>
<body>

    <div class="no-print">
        <button class="print-btn" onclick="window.print()">🖨️ Print Cash Memo / Save as PDF</button>
    </div>

    <!-- 1. Top Section: To, & Meta Box -->
    <div class="top-row">
        <div class="to-section">
            <div>To,</div>
            <div class="to-title" style="padding-left: 20px;">
                {{ $bill->department ? ($bill->department->cost_center_description ?: $bill->department->name) : 'Office of the XYZ' }}
            </div>
            <div style="padding-left: 20px; color: #4b5563; font-weight: bold;">
                {{ $bill->department ? ($bill->department->city ?: 'Karachi.') : 'Karachi.' }}
            </div>
        </div>

        <div class="meta-box">
            <div class="meta-box-header">
                <span>BILL #.</span>
                <span>{{ $bill->bill_number }}</span>
            </div>
            <div>Company: <strong>{{ $bill->company ? $bill->company->company_name : ($bill->vendor ? ($bill->vendor->company_name ?: $bill->vendor->name) : 'M/S. COMZON') }}</strong></div>
            <div>NTN #: <strong>{{ $bill->ntn_number ?: ($bill->company?->ntn ?: ($bill->vendor?->tax_number ?: '2128115-7')) }}</strong></div>
            <div>Sale Tax #: <strong>{{ $bill->strn_number ?: ($bill->company?->gst ?: ($bill->vendor?->strn_number ?: '1700212811518')) }}</strong></div>
            @if($bill->sst_number ?: $bill->company?->sst)
            <div>SST #: <strong>{{ $bill->sst_number ?: $bill->company?->sst }}</strong></div>
            @endif
            <div>AGPR Vendor #: <strong>{{ $bill->agpr_vendor_no ?: ($bill->company?->vendor_no ?: ($bill->vendor?->agpr_vendor_no ?: '30368984')) }}</strong></div>
        </div>
    </div>

    <!-- 2. Dual Customer & Shipper Cards (Image 1 Style) -->
    <div class="info-grid">
        <div class="info-card">
            <div class="info-card-header">CUSTOMER INFORMATION</div>
            <table class="info-table">
                <tr>
                    <td class="lbl">CUSTOMER NAME</td>
                    <td class="val">: {{ $bill->department ? ($bill->department->cost_center_description ?: $bill->department->name) : 'Office of the XYZ' }}</td>
                </tr>
                <tr>
                    <td class="lbl">ADDRESS</td>
                    <td class="val">: {{ $bill->department ? ($bill->department->city ?: 'Karachi.') : 'Karachi.' }}</td>
                </tr>
                <tr>
                    <td class="lbl">DEPARTMENT</td>
                    <td class="val">: {{ $bill->department ? $bill->department->name : 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="lbl">CONTACT PERSON</td>
                    <td class="val">: {{ $bill->department ? ($bill->department->head_of_department ?: 'Directorate') : 'Directorate' }}</td>
                </tr>
            </table>
        </div>

        <div class="info-card">
            <div class="info-card-header">SHIPPER INFORMATION</div>
            <table class="info-table">
                <tr>
                    <td class="lbl">COMPANY NAME</td>
                    <td class="val">: {{ $bill->company ? $bill->company->company_name : ($bill->vendor ? ($bill->vendor->company_name ?: $bill->vendor->name) : 'M/S. COMZON') }}</td>
                </tr>
                <tr>
                    <td class="lbl">INVOICE NUMBER</td>
                    <td class="val">: {{ $bill->bill_number }}</td>
                </tr>
                <tr>
                    <td class="lbl">INVOICE DATE</td>
                    <td class="val">: {{ $bill->invoice_date->format('d-F-Y') }}</td>
                </tr>
                <tr>
                    <td class="lbl">NTN NUMBER</td>
                    <td class="val">: {{ $bill->ntn_number ?: ($bill->company?->ntn ?: ($bill->vendor?->tax_number ?: '2128115-7')) }}</td>
                </tr>
                <tr>
                    <td class="lbl">STRN NUMBER</td>
                    <td class="val">: {{ $bill->strn_number ?: ($bill->company?->gst ?: ($bill->vendor?->strn_number ?: '1700212811518')) }}</td>
                </tr>
                @if($bill->sst_number ?: $bill->company?->sst)
                <tr>
                    <td class="lbl">SST NUMBER</td>
                    <td class="val">: {{ $bill->sst_number ?: $bill->company?->sst }}</td>
                </tr>
                @endif
                <tr>
                    <td class="lbl">AGPR VENDOR #</td>
                    <td class="val">: {{ $bill->agpr_vendor_no ?: ($bill->company?->vendor_no ?: ($bill->vendor?->agpr_vendor_no ?: '30368984')) }}</td>
                </tr>
                <tr>
                    <td class="lbl">CONTACT DETAILS</td>
                    <td class="val">: {{ $bill->company?->contact_number ?: ($bill->vendor?->phone ?: '0331-2449892') }}</td>
                </tr>
            </table>
        </div>
    </div>

    <!-- 3. Centered Title (Image 2 Style) -->
    <div class="memo-center">
        <h2 class="memo-title">BILL / CASH MEMO</h2>
        <div class="memo-subhead">
            {{ $bill->head_of_account ?: ($bill->department->cost_center_description ?: 'Cost of Other Stores') }}
        </div>
    </div>

    <!-- 4. Products Table (Image 2 Style) -->
    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 40px;" class="text-center">S. #.</th>
                <th>Description</th>
                <th style="width: 60px;" class="text-center">Qty</th>
                <th style="width: 90px;" class="text-right">Rate</th>
                <th style="width: 100px;" class="text-right">Amount</th>
            </tr>
        </thead>
        <tbody>
            @forelse($bill->items as $idx => $item)
                <tr>
                    <td class="text-center">{{ $idx + 1 }}</td>
                    <td>{{ $item->item_name }}</td>
                    <td class="text-center">{{ (int)$item->quantity == $item->quantity ? (int)$item->quantity : $item->quantity }}</td>
                    <td class="text-right">{{ number_format($item->unit_price) }}</td>
                    <td class="text-right" style="font-weight: bold;">{{ number_format($item->total_price) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">No items listed.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- 5. Totals Box (Image 2 Style) -->
    <div class="totals-container">
        <table class="totals-table">
            <tr>
                <td style="font-weight: bold; width: 50%;">Amount</td>
                <td class="text-right" style="font-weight: bold;">{{ number_format($bill->gross_amount) }}</td>
            </tr>
            <tr>
                <td style="font-weight: bold;">{{ $bill->gst_rate > 0 ? (int)$bill->gst_rate . '%' : '18%' }} GST</td>
                <td class="text-right" style="font-weight: bold;">{{ number_format($bill->gst_amount) }}</td>
            </tr>
            @if($bill->tax_deduction > 0)
                <tr>
                    <td style="color: #b91c1c;">Deduction</td>
                    <td class="text-right" style="color: #b91c1c; font-weight: bold;">-{{ number_format($bill->tax_deduction) }}</td>
                </tr>
            @endif
            <tr class="grand">
                <td>Total Amount</td>
                <td class="text-right">{{ number_format($bill->net_amount) }}</td>
            </tr>
        </table>
    </div>

    <!-- 6. Signatures Section -->
    <div class="signatures">
        <div class="sig-box">Store Keeper / Receiver</div>
        <div class="sig-box">Assistant Accounts Officer</div>
        <div class="sig-box">Drawing & Disbursing Officer (DDO)</div>
        <div class="sig-box">Vendor / Supplier Signature</div>
    </div>

</body>
</html>
