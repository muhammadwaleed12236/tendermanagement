        @if($activeTemplate === 'navy_wave')
            <div class="theme-navy-wave">
                <div class="top-wave-banner">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <div>
                            <h2 style="font-size: 22px; font-weight: 900; letter-spacing: 0.5px;">{{ $vendorName }}</h2>
                            <p style="font-size: 11px; opacity: 0.8; margin-top: 2px;">{{ $vendorAddress }}</p>
                            <p style="font-size: 10.5px; opacity: 0.8; font-family: monospace;">NTN: {{ $vendorNtn }} | STRN: {{ $vendorStrn }} @if($vendorSst)| SST: {{ $vendorSst }}@endif</p>
                        </div>
                        <div style="text-align: right;">
                            <span style="display: inline-block; background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.3); padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: bold;">
                                OFFICIAL VOUCHER
                            </span>
                        </div>
                    </div>

                    <!-- SVG Curved Wave Bottom of Banner -->
                    <svg viewBox="0 0 500 50" preserveAspectRatio="none">
                        <path d="M0,0 C150,50 350,-20 500,25 L500,50 L0,50 Z" fill="#ffffff"></path>
                    </svg>
                </div>

                <div class="wave-content">
                    <div class="title-block">
                        <div>
                            <div class="inv-title">Invoice</div>
                            <div style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; margin-top: 15px;">Invoice To:</div>
                            <div style="font-size: 14px; font-weight: 800; color: #1e293b;">{{ $customerName }}</div>
                            <div class="meta-col">{{ $customerAddress }}</div>
                            <div class="meta-col">{{ $customerDept }}</div>
                        </div>

                        <div style="text-align: right;">
                            <div style="font-size: 12px; margin-bottom: 4px;">
                                <span style="color: #64748b;">Invoice #:</span> <strong>{{ $bill->bill_number }}</strong>
                            </div>
                            <div style="font-size: 12px; margin-bottom: 4px;">
                                <span style="color: #64748b;">Date:</span> <strong>{{ $bill->invoice_date->format('d/m/Y') }}</strong>
                            </div>
                            <div style="font-size: 12px;">
                                <span style="color: #64748b;">Vendor (AGPR):</span> <strong>{{ $vendorNo }}</strong>
                            </div>
                        </div>
                    </div>

                    <!-- Table -->
                    <table class="table-navy">
                        <thead>
                            <tr>
                                <th style="width: 6%;">SL.</th>
                                <th style="width: 44%;">Item Description</th>
                                <th style="width: 14%; text-align: center;">Price</th>
                                <th style="width: 12%; text-align: center;">Qty</th>
                                <th style="width: 24%; text-align: right;">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($items as $i => $item)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td><strong>{{ $item['name'] }}</strong></td>
                                    <td style="text-align: center; font-family: monospace;">PKR {{ number_format($item['unit_price'], 2) }}</td>
                                    <td style="text-align: center;">{{ number_format($item['qty']) }} {{ $item['unit'] }}</td>
                                    <td style="text-align: right; font-family: monospace; font-weight: bold;">PKR {{ number_format($item['total_price'], 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <!-- Totals & Payment Info -->
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-top: 15px;">
                        <div style="font-size: 11.5px; color: #475569; max-width: 380px; line-height: 1.6;">
                            <strong style="color: #0f172a;">Payment Details:</strong><br>
                            Vendor AGPR #: <strong>{{ $vendorNo }}</strong><br>
                            NTN: <strong>{{ $vendorNtn }}</strong> | STRN: <strong>{{ $vendorStrn }}</strong><br>
                            <em>Payable through Government Treasury / AGPR System.</em>
                        </div>

                        <table class="total-table">
                            <tr>
                                <td style="color: #64748b;">Sub Total:</td>
                                <td style="font-family: monospace; font-weight: bold;">PKR {{ number_format($grossTotal, 2) }}</td>
                            </tr>
                            <tr>
                                <td style="color: #64748b;">Sales Tax GST ({{ $gstRate }}%):</td>
                                <td style="font-family: monospace; font-weight: bold;">+ PKR {{ number_format($gstAmount, 2) }}</td>
                            </tr>
                            @if($taxDeduction > 0)
                            <tr>
                                <td style="color: #be123c;">Less Withholding:</td>
                                <td style="font-family: monospace; font-weight: bold; color: #be123c;">- PKR {{ number_format($taxDeduction, 2) }}</td>
                            </tr>
                            @endif
                            <tr class="grand-row">
                                <td>TOTAL:</td>
                                <td style="font-family: monospace;">PKR {{ number_format($grandTotal, 2) }}</td>
                            </tr>
                        </table>
                    </div>

                    <div class="sign-row">
                        <div class="sign-line">Prepared By</div>
                        <div class="sign-line">Checked & Verified</div>
                        <div class="sign-line">Authorized Signatory</div>
                    </div>
                </div>
            </div>

        {{-- -------------------------------------------------------------------------
             PATTERN 2: CORPORATE MONOCHROME (media_1794752434236_2697d4b3.png)
             ------------------------------------------------------------------------- --}}
        @elseif($activeTemplate === 'classic_mono')
            <div class="theme-classic-mono">
                <div class="header-row">
                    <div>
                        <div class="inv-huge">INVOICE</div>
                    </div>
                    <div style="text-align: right; font-size: 12px; line-height: 1.5;">
                        <div><strong>Invoice no:</strong> {{ $bill->bill_number }}</div>
                        <div><strong>Issued:</strong> {{ $bill->invoice_date->format('d/m/Y') }}</div>
                    </div>
                </div>

                <div class="parties-grid">
                    <div>
                        <div class="party-head">Billed To</div>
                        <div style="font-size: 13px; font-weight: 800;">{{ $customerName }}</div>
                        <div>{{ $customerAddress }}</div>
                        <div>{{ $customerDept }}</div>
                    </div>

                    <div>
                        <div class="party-head">Sent By</div>
                        <div style="font-size: 13px; font-weight: 800;">{{ $vendorName }}</div>
                        <div>{{ $vendorAddress }}</div>
                        <div>Phone: {{ $vendorPhone }}</div>
                        <div>NTN: {{ $vendorNtn }} | STRN: {{ $vendorStrn }}</div>
                    </div>
                </div>

                <table class="mono-table">
                    <thead>
                        <tr>
                            <th style="width: 48%;">Item Description</th>
                            <th style="width: 14%; text-align: center;">Quantity</th>
                            <th style="width: 18%; text-align: right;">Unit Price</th>
                            <th style="width: 20%; text-align: right;">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $item)
                            <tr>
                                <td><strong>{{ $item['name'] }}</strong></td>
                                <td style="text-align: center;">{{ number_format($item['qty']) }} {{ $item['unit'] }}</td>
                                <td style="text-align: right; font-family: monospace;">${{ number_format($item['unit_price'], 2) }}</td>
                                <td style="text-align: right; font-family: monospace; font-weight: bold;">${{ number_format($item['total_price'], 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-top: 15px;">
                    <div style="font-size: 11.5px; line-height: 1.6;">
                        <strong>PAYMENT DETAILS</strong><br>
                        Bank / AGPR Vendor #: {{ $vendorNo }}<br>
                        Terms: Standard Commercial / Government Net 30
                    </div>

                    <table class="mono-totals">
                        <tr>
                            <td>Subtotal:</td>
                            <td style="font-family: monospace;">${{ number_format($grossTotal, 2) }}</td>
                        </tr>
                        <tr>
                            <td>Tax ({{ $gstRate }}%):</td>
                            <td style="font-family: monospace;">${{ number_format($gstAmount, 2) }}</td>
                        </tr>
                        <tr class="due-row">
                            <td>Amount Due:</td>
                            <td style="font-family: monospace;">${{ number_format($grandTotal, 2) }}</td>
                        </tr>
                    </table>
                </div>

                <div style="text-align: center; margin-top: 50px; font-size: 12px; color: #475569; font-weight: 600;">
                    Thank you for your business!
                </div>
            </div>

        {{-- -------------------------------------------------------------------------
             PATTERN 3: MODERN TERRACOTTA & ORANGE (media_1794752449639_6c578502.png)
             ------------------------------------------------------------------------- --}}
        @elseif($activeTemplate === 'vibrant_orange')
            <div class="theme-vibrant-orange">
                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 25px;">
                    <div>
                        <div class="brand-title">{{ $vendorName }}</div>
                        <div style="font-size: 11px; color: #64748b; margin-top: 2px;">{{ $vendorAddress }}</div>
                        <div style="font-size: 10.5px; color: #64748b; font-family: monospace;">NTN: {{ $vendorNtn }} | STRN: {{ $vendorStrn }}</div>
                    </div>

                    <div style="text-align: right; z-index: 2;">
                        <div class="orange-title">INVOICE</div>
                        <div style="font-size: 12px; font-weight: bold; color: #475569; margin-top: 4px;">{{ $bill->invoice_date->format('F d, Y') }}</div>
                        <div style="font-size: 11px; color: #64748b; font-family: monospace;">NO. {{ $bill->bill_number }}</div>
                    </div>
                </div>

                <div style="margin: 20px 0; font-size: 12px;">
                    <span style="font-size: 10.5px; font-weight: bold; color: #64748b;">TO.</span>
                    <div style="font-size: 14px; font-weight: 800; color: #0f172a;">{{ $customerName }}</div>
                    <div style="color: #475569;">{{ $customerAddress }} • {{ $customerDept }}</div>
                </div>

                <table class="orange-table">
                    <thead>
                        <tr>
                            <th style="width: 10%; text-align: center;">QTY</th>
                            <th style="width: 50%;">DESCRIPTION</th>
                            <th style="width: 20%; text-align: right;">PRICE</th>
                            <th style="width: 20%; text-align: right;">TOTAL</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $item)
                            <tr>
                                <td style="text-align: center; font-weight: bold;">{{ number_format($item['qty']) }}</td>
                                <td>
                                    <strong>{{ $item['name'] }}</strong>
                                    <div style="font-size: 10px; color: #64748b;">Unit: {{ $item['unit'] }}</div>
                                </td>
                                <td style="text-align: right; font-family: monospace;">${{ number_format($item['unit_price'], 2) }}</td>
                                <td style="text-align: right; font-family: monospace; font-weight: bold;">${{ number_format($item['total_price'], 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-top: 15px;">
                    <div style="font-size: 11px; color: #64748b;">
                        <strong style="color: #0f172a;">Payment Method:</strong><br>
                        AGPR Direct Voucher Credit • Vendor # {{ $vendorNo }}
                    </div>

                    <div style="width: 320px;">
                        <div style="display: flex; justify-content: space-between; font-size: 12px; padding: 3px 0;">
                            <span>Sub Total:</span>
                            <span style="font-family: monospace;">${{ number_format($grossTotal, 2) }}</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; font-size: 12px; padding: 3px 0;">
                            <span>Tax ({{ $gstRate }}%):</span>
                            <span style="font-family: monospace;">${{ number_format($gstAmount, 2) }}</span>
                        </div>
                        <div class="orange-banner-total">
                            <span>GRAND TOTAL</span>
                            <span style="font-family: monospace;">${{ number_format($grandTotal, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>

        {{-- -------------------------------------------------------------------------
             PATTERN 4: CRIMSON ROSE & STRIPE (media_1794752459884_b3c85abd.png)
             ------------------------------------------------------------------------- --}}
        @elseif($activeTemplate === 'crimson_rose')
            <div class="theme-crimson-rose">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div class="crimson-title">INVOICE</div>
                    <div style="text-align: right;">
                        <div style="font-size: 18px; font-weight: 900; color: #be123c;">{{ $vendorName }}</div>
                        <div style="font-size: 10.5px; color: #64748b;">NTN: {{ $vendorNtn }} | STRN: {{ $vendorStrn }}</div>
                    </div>
                </div>

                <div class="top-rule-box">
                    <div><strong>INVOICE NUMBER:</strong> {{ $bill->bill_number }}</div>
                    <div><strong>INVOICE DATE:</strong> {{ $bill->invoice_date->format('M d, Y') }}</div>
                    <div><strong>CUSTOMER ID:</strong> CUST-{{ $bill->department_id }}</div>
                    <div><strong>DUE DATE:</strong> {{ $bill->invoice_date->addDays(30)->format('M d, Y') }}</div>
                </div>

                <div style="display: flex; justify-content: space-between; margin-bottom: 20px; font-size: 12px;">
                    <div>
                        <strong style="color: #be123c; text-transform: uppercase; font-size: 10.5px;">BILLED TO:</strong>
                        <div style="font-size: 13px; font-weight: 800;">{{ $customerName }}</div>
                        <div style="color: #64748b;">{{ $customerAddress }}</div>
                    </div>
                    <div style="text-align: right;">
                        <strong style="color: #be123c; text-transform: uppercase; font-size: 10.5px;">PAYMENT INFO:</strong>
                        <div>Vendor Account #: {{ $vendorNo }}</div>
                        <div style="color: #64748b;">Govt Bank Treasury</div>
                    </div>
                </div>

                <table class="rose-table">
                    <thead>
                        <tr>
                            <th style="width: 50%;">DESCRIPTION</th>
                            <th style="width: 18%; text-align: right;">UNIT COST</th>
                            <th style="width: 14%; text-align: center;">QUANTITY</th>
                            <th style="width: 18%; text-align: right;">AMOUNT</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $item)
                            <tr>
                                <td>{{ $item['name'] }}</td>
                                <td style="text-align: right; font-family: monospace;">${{ number_format($item['unit_price'], 2) }}</td>
                                <td style="text-align: center;">{{ number_format($item['qty']) }}</td>
                                <td style="text-align: right; font-family: monospace; font-weight: bold;">${{ number_format($item['total_price'], 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-top: 20px;">
                    <div style="font-size: 11px; color: #64748b;">
                        <strong>TERMS AND CONDITIONS:</strong><br>
                        Payment due within 30 days of invoice verification.
                    </div>

                    <div class="rose-totals-box">
                        <div class="row">
                            <span>Subtotal:</span>
                            <span style="font-family: monospace;">${{ number_format($grossTotal, 2) }}</span>
                        </div>
                        <div class="row">
                            <span>Tax ({{ $gstRate }}%):</span>
                            <span style="font-family: monospace;">${{ number_format($gstAmount, 2) }}</span>
                        </div>
                        <div class="row bold-row">
                            <span>TOTAL:</span>
                            <span style="font-family: monospace;">${{ number_format($grandTotal, 2) }}</span>
                        </div>
                    </div>
                </div>

                <div style="text-align: center; margin-top: 45px; font-weight: 800; font-size: 12.5px; color: #be123c; letter-spacing: 1px;">
                    THANK YOU FOR YOUR BUSINESS!
                </div>
            </div>

        {{-- -------------------------------------------------------------------------
             PATTERN 5: GEOMETRIC CIRCLE & AMBER (media_1794752469493_c7cfa7b8.jpg)
             ------------------------------------------------------------------------- --}}
        @elseif($activeTemplate === 'geometric_amber')
            <div class="theme-geometric-amber">
                <div class="geo-header">
                    <div>
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <span style="font-size: 20px;">💼</span>
                            <h2 style="font-size: 20px; font-weight: 900; color: #1e293b;">{{ $vendorName }}</h2>
                        </div>
                        <p style="font-size: 11px; color: #64748b; margin-top: 3px;">{{ $vendorAddress }}</p>
                        <p style="font-size: 10.5px; color: #64748b; font-family: monospace;">NTN: {{ $vendorNtn }} | STRN: {{ $vendorStrn }}</p>
                    </div>

                    <div class="circles-art">
                        <div class="c-red"></div>
                        <div class="c-dark"></div>
                        <div class="c-yellow"></div>
                    </div>
                </div>

                <div class="center-title">INVOICE</div>

                <div style="display: flex; justify-content: space-between; font-size: 12px; margin-bottom: 20px;">
                    <div>
                        <span style="color: #64748b;">Client Name:</span> <strong>{{ $customerName }}</strong><br>
                        <span style="color: #64748b;">Address:</span> {{ $customerAddress }}
                    </div>
                    <div style="text-align: right;">
                        <span style="color: #64748b;">Invoice No:</span> <strong>{{ $bill->bill_number }}</strong><br>
                        <span style="color: #64748b;">Issue Date:</span> {{ $bill->invoice_date->format('d M Y') }}
                    </div>
                </div>

                <table class="dark-table">
                    <thead>
                        <tr>
                            <th style="width: 50%;">Item Description</th>
                            <th style="width: 18%; text-align: right;">Prices</th>
                            <th style="width: 14%; text-align: center;">Count</th>
                            <th style="width: 18%; text-align: right;">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $item)
                            <tr>
                                <td><strong>{{ $item['name'] }}</strong></td>
                                <td style="text-align: right; font-family: monospace;">${{ number_format($item['unit_price'], 2) }}</td>
                                <td style="text-align: center;">{{ number_format($item['qty']) }}</td>
                                <td style="text-align: right; font-family: monospace; font-weight: bold;">${{ number_format($item['total_price'], 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-top: 20px;">
                    <div style="font-size: 11.5px; color: #64748b;">
                        <strong style="color: #1e293b;">Payment Methods:</strong><br>
                        AGPR Vendor #: {{ $vendorNo }}<br>
                        <span style="color: #d97706; font-weight: bold;">Direct Treasury Remittance</span>
                    </div>

                    <div class="amber-card">
                        <div class="row">
                            <span>Sub Total:</span>
                            <span style="font-family: monospace;">${{ number_format($grossTotal, 2) }}</span>
                        </div>
                        <div class="row">
                            <span>Sales Tax ({{ $gstRate }}%):</span>
                            <span style="font-family: monospace;">${{ number_format($gstAmount, 2) }}</span>
                        </div>
                        <div class="row bold-row">
                            <span>Grand Total:</span>
                            <span style="font-family: monospace;">${{ number_format($grandTotal, 2) }}</span>
                        </div>
                    </div>
                </div>

                <div style="text-align: center; margin-top: 40px; font-weight: 700; font-size: 12px; color: #475569;">
                    ❤️ Thank You for Business With Us
                </div>
            </div>

        {{-- -------------------------------------------------------------------------
             PATTERN 6: LIME GREEN & NAVY DIAGONAL (media_1794752486810_4547ef05.png)
             ------------------------------------------------------------------------- --}}
        @elseif($activeTemplate === 'lime_navy')
            <div class="theme-lime-navy">
                <div class="diag-header">
                    <div class="diag-header-text">
                        <div style="color: #0f172a; font-size: 20px; font-weight: 900; letter-spacing: 0.5px;">
                            {{ $vendorName }}
                        </div>
                        <div style="color: #fff; font-size: 22px; font-weight: 900; letter-spacing: 1px;">
                            INVOICE
                        </div>
                    </div>
                </div>

                <div class="diag-body">
                    <div style="display: flex; justify-content: space-between; font-size: 12px; margin-bottom: 25px;">
                        <div>
                            <div style="font-size: 11px; font-weight: bold; color: #65a30d; text-transform: uppercase;">Invoice to:</div>
                            <div style="font-size: 14px; font-weight: 800; color: #0f172a;">{{ $customerName }}</div>
                            <div style="color: #64748b;">{{ $customerAddress }} • {{ $customerDept }}</div>
                        </div>

                        <div style="text-align: right; font-size: 12px; line-height: 1.6;">
                            <div><span style="color: #64748b;">Invoice #:</span> <strong>{{ $bill->bill_number }}</strong></div>
                            <div><span style="color: #64748b;">Date:</span> <strong>{{ $bill->invoice_date->format('d/m/Y') }}</strong></div>
                            <div><span style="color: #64748b;">NTN:</span> <strong>{{ $vendorNtn }}</strong></div>
                        </div>
                    </div>

                    <table class="lime-table">
                        <thead>
                            <tr>
                                <th style="width: 50%;">DESCRIPTION</th>
                                <th style="width: 18%; text-align: right;">PRICE</th>
                                <th style="width: 14%; text-align: center;">QTY</th>
                                <th style="width: 18%; text-align: right;">TOTAL</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($items as $item)
                                <tr>
                                    <td><strong>{{ $item['name'] }}</strong></td>
                                    <td style="text-align: right; font-family: monospace;">PKR {{ number_format($item['unit_price'], 2) }}</td>
                                    <td style="text-align: center;">{{ number_format($item['qty']) }}</td>
                                    <td style="text-align: right; font-family: monospace; font-weight: bold;">PKR {{ number_format($item['total_price'], 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-top: 20px;">
                        <div style="font-size: 11.5px; color: #475569;">
                            <strong>Payment Info:</strong><br>
                            Vendor No (AGPR): {{ $vendorNo }}<br>
                            STRN: {{ $vendorStrn }} @if($vendorSst)| SST: {{ $vendorSst }}@endif
                        </div>

                        <div style="width: 280px; font-size: 12.5px;">
                            <div style="display: flex; justify-content: space-between; padding: 3px 0;">
                                <span>Sub Total:</span>
                                <span style="font-family: monospace; font-weight: bold;">PKR {{ number_format($grossTotal, 2) }}</span>
                            </div>
                            <div style="display: flex; justify-content: space-between; padding: 3px 0;">
                                <span>Sales Tax ({{ $gstRate }}%):</span>
                                <span style="font-family: monospace; font-weight: bold;">PKR {{ number_format($gstAmount, 2) }}</span>
                            </div>
                            <div style="display: flex; justify-content: space-between; padding: 6px 0; border-top: 2px solid #65a30d; font-size: 14px; font-weight: 900; color: #0f172a;">
                                <span>Grand Total:</span>
                                <span style="font-family: monospace; color: #65a30d;">PKR {{ number_format($grandTotal, 2) }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="diag-footer"></div>
                </div>
            </div>

        {{-- -------------------------------------------------------------------------
             PATTERN 7: MINIMAL CHARCOAL WAVE (media_1794752496369_aeffa853.png)
             ------------------------------------------------------------------------- --}}
        @else
            <div class="theme-charcoal-wave">
                <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                    <div>
                        <div style="font-size: 18px; font-weight: 900; color: #0f172a;">{{ $vendorName }}</div>
                        <div style="font-size: 11px; color: #64748b;">{{ $vendorAddress }}</div>
                    </div>
                    <div style="text-align: right; font-size: 12px; font-family: monospace; font-weight: bold; color: #475569;">
                        NO. {{ $bill->bill_number }}
                    </div>
                </div>

                <div class="big-black-title">INVOICE</div>

                <div style="font-size: 12px; color: #64748b; margin-bottom: 25px;">
                    <strong>Date:</strong> {{ $bill->invoice_date->format('d F, Y') }}
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 40px; font-size: 12px; margin-bottom: 30px;">
                    <div>
                        <strong style="font-size: 11px; text-transform: uppercase;">Billed to:</strong>
                        <div style="font-size: 13.5px; font-weight: 800; color: #0f172a; margin-top: 2px;">{{ $customerName }}</div>
                        <div style="color: #64748b;">{{ $customerAddress }}</div>
                    </div>

                    <div>
                        <strong style="font-size: 11px; text-transform: uppercase;">From:</strong>
                        <div style="font-size: 13.5px; font-weight: 800; color: #0f172a; margin-top: 2px;">{{ $vendorName }}</div>
                        <div style="color: #64748b;">Phone: {{ $vendorPhone }}</div>
                        <div style="color: #64748b;">NTN: {{ $vendorNtn }} | STRN: {{ $vendorStrn }}</div>
                    </div>
                </div>

                <table class="pill-table">
                    <thead>
                        <tr>
                            <th style="width: 50%;">Item</th>
                            <th style="width: 14%; text-align: center;">Quantity</th>
                            <th style="width: 18%; text-align: right;">Price</th>
                            <th style="width: 18%; text-align: right;">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $item)
                            <tr>
                                <td><strong>{{ $item['name'] }}</strong></td>
                                <td style="text-align: center;">{{ number_format($item['qty']) }}</td>
                                <td style="text-align: right; font-family: monospace;">${{ number_format($item['unit_price'], 2) }}</td>
                                <td style="text-align: right; font-family: monospace; font-weight: bold;">${{ number_format($item['total_price'], 2) }}</td>
                            </tr>
                        @endforeach
                        <tr style="border-top: 2px solid #0f172a;">
                            <td colspan="3" style="text-align: right; font-weight: 900; font-size: 13px;">Total:</td>
                            <td style="text-align: right; font-family: monospace; font-weight: 900; font-size: 14px;">${{ number_format($grandTotal, 2) }}</td>
                        </tr>
                    </tbody>
                </table>

                <div style="font-size: 11.5px; color: #64748b; margin-top: 30px;">
                    <strong>Payment method:</strong> Treasury Transfer / Vendor # {{ $vendorNo }}<br>
                    <strong>Note:</strong> Thank you for choosing us!
                </div>

                <!-- Dual Bottom Charcoal Wave -->
                <div class="bottom-wave">
                    <svg viewBox="0 0 500 55" preserveAspectRatio="none">
                        <path d="M0,35 C150,10 350,60 500,20 L500,55 L0,55 Z" fill="#94a3b8" opacity="0.4"></path>
                        <path d="M0,45 C200,20 300,50 500,30 L500,55 L0,55 Z" fill="#334155"></path>
                    </svg>
                </div>
            </div>
        @endif
