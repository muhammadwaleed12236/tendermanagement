        /* ========================================================
           PATTERN 1: EXECUTIVE NAVY WAVE (media_1794752427767_4fedb400.png)
           ======================================================== */
        .theme-navy-wave {
            background-color: #ffffff;
        }
        .theme-navy-wave .top-wave-banner {
            background: linear-gradient(135deg, #1b2a47 0%, #0f172a 100%);
            padding: 30px 45px 50px 45px;
            color: #fff;
            position: relative;
        }
        .theme-navy-wave .top-wave-banner svg {
            position: absolute;
            bottom: -1px;
            left: 0;
            width: 100%;
            height: 48px;
        }
        .theme-navy-wave .wave-content {
            padding: 10px 45px 35px 45px;
        }
        .theme-navy-wave .title-block {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 25px;
        }
        .theme-navy-wave .inv-title {
            font-size: 34px;
            font-weight: 900;
            color: #1b2a47;
            letter-spacing: -0.5px;
        }
        .theme-navy-wave .meta-col {
            font-size: 12px;
            color: #475569;
            line-height: 1.5;
        }
        .theme-navy-wave .table-navy {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin: 25px 0;
        }
        .theme-navy-wave .table-navy th {
            background: #1b2a47;
            color: #fff;
            font-size: 11.5px;
            font-weight: 700;
            padding: 10px 14px;
            text-align: left;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .theme-navy-wave .table-navy th:first-child { border-radius: 8px 0 0 8px; }
        .theme-navy-wave .table-navy th:last-child { border-radius: 0 8px 8px 0; text-align: right; }
        .theme-navy-wave .table-navy td {
            padding: 11px 14px;
            border-bottom: 1px solid #f1f5f9;
            font-size: 12.5px;
            color: #334155;
        }
        .theme-navy-wave .table-navy tr:nth-child(even) td { background: #f8fafc; }
        .theme-navy-wave .total-box {
            margin-top: 20px;
            display: flex;
            justify-content: flex-end;
        }
        .theme-navy-wave .total-table {
            width: 320px;
            border-collapse: collapse;
            font-size: 12.5px;
        }
        .theme-navy-wave .total-table td {
            padding: 6px 12px;
            text-align: right;
        }
        .theme-navy-wave .total-table .grand-row {
            border-top: 2px solid #1b2a47;
            font-size: 16px;
            font-weight: 900;
            color: #1b2a47;
            padding-top: 8px;
        }

        /* ========================================================
           PATTERN 2: CORPORATE MONOCHROME (media_1794752434236_2697d4b3.png)
           ======================================================== */
        .theme-classic-mono {
            padding: 50px 55px;
            font-family: 'Inter', sans-serif;
            color: #000;
        }
        .theme-classic-mono .header-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-bottom: 30px;
        }
        .theme-classic-mono .inv-huge {
            font-size: 40px;
            font-weight: 900;
            letter-spacing: 2px;
            color: #000;
        }
        .theme-classic-mono .divider-rule {
            border-top: 2px solid #000;
            margin: 12px 0 20px 0;
        }
        .theme-classic-mono .parties-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            font-size: 12px;
            line-height: 1.6;
            margin-bottom: 35px;
        }
        .theme-classic-mono .party-head {
            font-weight: 800;
            text-transform: uppercase;
            font-size: 11px;
            letter-spacing: 1px;
            padding-bottom: 4px;
            border-bottom: 1px solid #000;
            margin-bottom: 8px;
        }
        .theme-classic-mono .mono-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
            margin-bottom: 30px;
        }
        .theme-classic-mono .mono-table th {
            border-bottom: 1.5px solid #000;
            padding: 8px 6px;
            text-align: left;
            font-weight: 800;
            font-size: 11px;
            text-transform: uppercase;
        }
        .theme-classic-mono .mono-table td {
            border-bottom: 1px solid #e5e5e5;
            padding: 9px 6px;
        }
        .theme-classic-mono .mono-totals {
            margin-left: auto;
            width: 320px;
            font-size: 12.5px;
            border-collapse: collapse;
        }
        .theme-classic-mono .mono-totals td {
            padding: 5px 6px;
            text-align: right;
        }
        .theme-classic-mono .mono-totals .due-row {
            border-top: 2px solid #000;
            border-bottom: 2px solid #000;
            font-weight: 900;
            font-size: 14px;
            padding: 8px 6px;
        }

        /* ========================================================
           PATTERN 3: MODERN TERRACOTTA & ORANGE (media_1794752449639_6c578502.png)
           ======================================================== */
        .theme-vibrant-orange {
            padding: 45px 50px;
            position: relative;
        }
        .theme-vibrant-orange::before {
            content: "";
            position: absolute;
            top: 0;
            right: 0;
            width: 180px;
            height: 90px;
            background: #ea580c;
            clip-path: polygon(100% 0, 0 0, 100% 100%);
        }
        .theme-vibrant-orange::after {
            content: "";
            position: absolute;
            bottom: 0;
            left: 0;
            width: 120px;
            height: 70px;
            background: #ea580c;
            clip-path: polygon(0 0, 0 100%, 100% 100%);
        }
        .theme-vibrant-orange .brand-title {
            font-family: 'Playfair Display', serif;
            font-size: 26px;
            font-weight: 900;
            color: #ea580c;
            font-style: italic;
        }
        .theme-vibrant-orange .orange-title {
            font-size: 30px;
            font-weight: 900;
            color: #ea580c;
            letter-spacing: 1px;
            text-transform: uppercase;
        }
        .theme-vibrant-orange .orange-table {
            width: 100%;
            border-collapse: collapse;
            margin: 25px 0;
            font-size: 12px;
        }
        .theme-vibrant-orange .orange-table th {
            border-top: 1.5px solid #334155;
            border-bottom: 1.5px solid #334155;
            padding: 8px 10px;
            text-transform: uppercase;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 0.5px;
        }
        .theme-vibrant-orange .orange-table td {
            padding: 10px 10px;
            border-bottom: 1px solid #f1f5f9;
        }
        .theme-vibrant-orange .orange-banner-total {
            background: #ea580c;
            color: #fff;
            padding: 10px 20px;
            font-weight: 900;
            font-size: 15px;
            display: inline-flex;
            justify-content: space-between;
            align-items: center;
            border-radius: 4px;
            width: 100%;
            margin-top: 10px;
        }

        /* ========================================================
           PATTERN 4: CRIMSON ROSE & STRIPE (media_1794752459884_b3c85abd.png)
           ======================================================== */
        .theme-crimson-rose {
            padding: 40px 45px;
            border-top: 6px solid #be123c;
        }
        .theme-crimson-rose .crimson-title {
            font-size: 36px;
            font-weight: 900;
            color: #0f172a;
            letter-spacing: 1.5px;
        }
        .theme-crimson-rose .top-rule-box {
            border-top: 1.5px solid #be123c;
            border-bottom: 1.5px solid #be123c;
            padding: 8px 10px;
            margin: 15px 0 25px 0;
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
            font-size: 11px;
        }
        .theme-crimson-rose .rose-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
            margin: 20px 0;
        }
        .theme-crimson-rose .rose-table th {
            padding: 8px 10px;
            text-align: left;
            font-weight: 800;
            color: #0f172a;
            border-bottom: 1px solid #e2e8f0;
            text-transform: uppercase;
            font-size: 11px;
        }
        .theme-crimson-rose .rose-table td {
            padding: 8px 10px;
        }
        .theme-crimson-rose .rose-table tr:nth-child(even) td {
            background: #ffe4e6;
            color: #881337;
            font-weight: 600;
        }
        .theme-crimson-rose .rose-totals-box {
            margin-left: auto;
            width: 280px;
            background: #ffe4e6;
            padding: 10px 14px;
            border-radius: 4px;
            font-size: 12px;
        }
        .theme-crimson-rose .rose-totals-box .row {
            display: flex;
            justify-content: space-between;
            padding: 3px 0;
        }
        .theme-crimson-rose .rose-totals-box .bold-row {
            font-weight: 900;
            border-top: 1px solid #fda4af;
            padding-top: 6px;
            margin-top: 4px;
            color: #881337;
            font-size: 13.5px;
        }

        /* ========================================================
           PATTERN 5: GEOMETRIC CIRCLE & AMBER (media_1794752469493_c7cfa7b8.jpg)
           ======================================================== */
        .theme-geometric-amber {
            padding: 40px 48px;
            position: relative;
        }
        .theme-geometric-amber .geo-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 25px;
        }
        .theme-geometric-amber .circles-art {
            display: flex;
            align-items: center;
            gap: -15px;
        }
        .theme-geometric-amber .c-red { width: 55px; height: 55px; border-radius: 50%; background: #ef4444; }
        .theme-geometric-amber .c-dark { width: 42px; height: 42px; border-radius: 50%; background: #374151; margin-left: -15px; }
        .theme-geometric-amber .c-yellow { width: 32px; height: 32px; border-radius: 50%; background: #f59e0b; margin-left: -10px; }
        .theme-geometric-amber .center-title {
            text-align: center;
            font-size: 26px;
            font-weight: 900;
            letter-spacing: 2px;
            color: #1e293b;
            margin: 20px 0;
            text-transform: uppercase;
        }
        .theme-geometric-amber .dark-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
            margin: 20px 0;
        }
        .theme-geometric-amber .dark-table th {
            background: #475569;
            color: #fff;
            padding: 9px 12px;
            font-weight: 700;
            font-size: 11px;
            text-transform: uppercase;
        }
        .theme-geometric-amber .dark-table td {
            padding: 9px 12px;
            border-bottom: 1px solid #f1f5f9;
        }
        .theme-geometric-amber .dark-table tr:nth-child(even) td { background: #f8fafc; }
        .theme-geometric-amber .amber-card {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: #fff;
            padding: 14px 18px;
            border-radius: 6px;
            width: 320px;
            margin-left: auto;
            box-shadow: 0 4px 10px rgba(217, 119, 6, 0.2);
        }
        .theme-geometric-amber .amber-card .row {
            display: flex;
            justify-content: space-between;
            padding: 3px 0;
            font-size: 12px;
        }
        .theme-geometric-amber .amber-card .bold-row {
            font-size: 15px;
            font-weight: 900;
            border-top: 1px solid rgba(255,255,255,0.3);
            margin-top: 5px;
            padding-top: 6px;
        }

        /* ========================================================
           PATTERN 6: LIME GREEN & NAVY DIAGONAL (media_1794752486810_4547ef05.png)
           ======================================================== */
        .theme-lime-navy {
            position: relative;
        }
        .theme-lime-navy .diag-header {
            height: 90px;
            position: relative;
            background: #0f172a;
            overflow: hidden;
        }
        .theme-lime-navy .diag-header::before {
            content: "";
            position: absolute;
            top: -20px;
            left: -20px;
            width: 75%;
            height: 140px;
            background: #84cc16;
            transform: skewX(-28deg);
        }
        .theme-lime-navy .diag-header-text {
            position: relative;
            z-index: 2;
            padding: 24px 45px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .theme-lime-navy .diag-body {
            padding: 30px 45px;
        }
        .theme-lime-navy .lime-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
            margin: 25px 0;
        }
        .theme-lime-navy .lime-table th {
            background: #84cc16;
            color: #0f172a;
            padding: 9px 12px;
            font-weight: 800;
            font-size: 11px;
            text-transform: uppercase;
        }
        .theme-lime-navy .lime-table td {
            padding: 9px 12px;
            border-bottom: 1px solid #f1f5f9;
        }
        .theme-lime-navy .diag-footer {
            height: 16px;
            background: #0f172a;
            position: relative;
            margin-top: 40px;
        }
        .theme-lime-navy .diag-footer::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 50%;
            height: 100%;
            background: #84cc16;
        }

        /* ========================================================
           PATTERN 7: MINIMAL CHARCOAL WAVE (media_1794752496369_aeffa853.png)
           ======================================================== */
        .theme-charcoal-wave {
            padding: 45px 50px 70px 50px;
            position: relative;
        }
        .theme-charcoal-wave .big-black-title {
            font-size: 44px;
            font-weight: 900;
            color: #0f172a;
            letter-spacing: -1px;
            margin: 15px 0 25px 0;
        }
        .theme-charcoal-wave .pill-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            font-size: 12px;
            margin: 25px 0;
        }
        .theme-charcoal-wave .pill-table th {
            background: #e2e8f0;
            color: #1e293b;
            padding: 10px 14px;
            font-weight: 800;
            font-size: 11px;
            text-transform: uppercase;
        }
        .theme-charcoal-wave .pill-table th:first-child { border-radius: 6px 0 0 6px; }
        .theme-charcoal-wave .pill-table th:last-child { border-radius: 0 6px 6px 0; text-align: right; }
        .theme-charcoal-wave .pill-table td {
            padding: 10px 14px;
            border-bottom: 1px solid #f1f5f9;
        }
        .theme-charcoal-wave .bottom-wave {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 55px;
            overflow: hidden;
            line-height: 0;
        }
        .theme-charcoal-wave .bottom-wave svg {
            width: 100%;
            height: 55px;
        }

        /* Universal Signature & Footer Blocks */
        .sign-row {
            margin-top: 40px;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            padding-top: 20px;
        }
        .sign-line {
            width: 180px;
            border-top: 1px solid #94a3b8;
            text-align: center;
            padding-top: 6px;
            font-size: 11px;
            font-weight: 700;
            color: #475569;
        }

        /* Print Override */
