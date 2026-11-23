<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Daily Billing Report</title>
    <style>
        body {
            font-family: Calibri, 'Segoe UI', Arial, sans-serif;
            font-size: 11pt;
            color: #000000;
        }
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th {
            background-color: #f7cbb0; /* Peach / Salmon tone from user Excel sheet */
            color: #111827;
            font-weight: bold;
            font-size: 10.5pt;
            text-align: center;
            vertical-align: middle;
            border: 1px solid #000000;
            padding: 8px 10px;
            white-space: nowrap;
        }
        td {
            border: 1px solid #7f8c8d;
            padding: 6px 10px;
            font-size: 10pt;
            vertical-align: middle;
        }
        .row-even {
            background-color: #ffffff;
        }
        .row-odd {
            background-color: #eef3f8; /* Soft blue-gray tint from screenshot */
        }
        .text-center { text-align: center; }
        .text-left { text-align: left; }
        .text-right { text-align: right; }
        .num-format {
            text-align: right;
            font-weight: bold;
        }
        .total-row td {
            background-color: #fed7aa;
            font-weight: bold;
            border-top: 2px solid #000000;
            border-bottom: 2px solid #000000;
        }
    </style>
</head>
<body>
    <table>
        <thead>
            <tr>
                <th style="width: 6px;">Sr #</th>
                <th style="width: 14px;">Bill CreateDate</th>
                <th style="width: 12px;">Bill No</th>
                <th style="width: 12px;">Quarter</th>
                <th style="width: 15px;">Head of Account</th>
                <th style="width: 40px;">Head Description</th>
                <th style="width: 14px;">Cost Center</th>
                <th style="width: 32px;">Department Name</th>
                <th style="width: 10px;">GST/SST</th>
                <th style="width: 14px;">Amount</th>
                <th style="width: 28px;">Vendor Name</th>
                <th style="width: 16px;">Bill Dispatch Date</th>
                <th style="width: 25px;">Remarks</th>
            </tr>
        </thead>
        <tbody>
            @php
                $sr = 1;
                $grandTotal = 0;
            @endphp
            @forelse($bills as $index => $bill)
                @php
                    $amt = (float)($bill->net_amount ?: $bill->gross_amount);
                    $grandTotal += $amt;
                    $rowClass = ($index % 2 == 0) ? 'row-even' : 'row-odd';
                @endphp
                <tr class="{{ $rowClass }}">
                    <td class="text-center">{{ $sr++ }}</td>
                    <td class="text-center">{{ $bill->formatted_create_date }}</td>
                    <td class="text-center">{{ $bill->bill_number }}</td>
                    <td class="text-center">{{ $bill->quarter_display }}</td>
                    <td class="text-center">{{ $bill->head_code_display }}</td>
                    <td class="text-left">{{ $bill->head_description_display }}</td>
                    <td class="text-center">{{ $bill->cost_center_code_display }}</td>
                    <td class="text-left">{{ $bill->department_name_display }}</td>
                    <td class="text-center">{{ $bill->gst_display }}</td>
                    <td class="num-format">{{ number_format($amt) }}</td>
                    <td class="text-left">{{ $bill->vendor_name_display }}</td>
                    <td class="text-center">{{ $bill->formatted_dispatch_date }}</td>
                    <td class="text-left">{{ $bill->remarks ?: '' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="13" class="text-center" style="padding: 20px;">No billing records found for the selected filter.</td>
                </tr>
            @endforelse
            @if(count($bills) > 0)
                <tr class="total-row">
                    <td colspan="9" class="text-right" style="text-align: right; padding-right: 15px;">TOTAL:</td>
                    <td class="num-format">{{ number_format($grandTotal) }}</td>
                    <td colspan="3"></td>
                </tr>
            @endif
        </tbody>
    </table>
</body>
</html>
