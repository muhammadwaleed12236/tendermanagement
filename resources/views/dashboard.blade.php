<x-app-layout>
    <!-- Funky, Colorful, Ultra-Attractive 3D SaaS Stylesheet matching media_1794958310999_435bfd77 -->
    <style>
        body {
            background-color: #e6e0d4 !important;
        }

        .dashboard-canvas {
            background-color: #e6e0d4;
            background-image: none;
            min-height: 100vh;
            width: 100%;
            position: relative;
            overflow-x: hidden;
            font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
        }

        /* 3D Elevated SaaS Cards with Rich Physical Depth & Extruded Base */
        .glass-panel {
            background: linear-gradient(180deg, #ffffff 0%, #fbfdff 100%);
            border-radius: 1.5rem;
            border: 1.5px solid rgba(255, 255, 255, 0.95);
            border-bottom: 5.5px solid #cbd5e1;
            box-shadow:
                0 18px 36px -8px rgba(100, 116, 139, 0.18),
                0 6px 14px -3px rgba(100, 116, 139, 0.08),
                inset 0 2px 3px rgba(255, 255, 255, 0.98);
            transition: all 0.25s cubic-bezier(0.34, 1.56, 0.64, 1);
            position: relative;
        }

        .glass-panel:hover {
            transform: translateY(-4px);
            border-bottom: 6.5px solid #94a3b8;
            box-shadow:
                0 26px 46px -10px rgba(100, 116, 139, 0.25),
                0 10px 20px -4px rgba(100, 116, 139, 0.12),
                inset 0 2px 3px rgba(255, 255, 255, 0.98);
        }

        /* ------------------------------------------------------------- */
        /* ROW 2 & ROW 3 COLORFUL THEMED 3D GLASS CARDS                  */
        /* ------------------------------------------------------------- */
        .card-theme-blue {
            background: linear-gradient(180deg, #f0f9ff 0%, #ffffff 42%, #ffffff 100%);
            border-radius: 1.5rem;
            border: 1.5px solid rgba(56, 189, 248, 0.45);
            border-bottom: 6px solid #0284c7;
            box-shadow:
                0 18px 38px -8px rgba(2, 132, 199, 0.28),
                0 6px 14px -3px rgba(2, 132, 199, 0.12),
                inset 0 2px 3px rgba(255, 255, 255, 0.98);
            transition: all 0.25s cubic-bezier(0.34, 1.56, 0.64, 1);
            position: relative;
        }
        .card-theme-blue:hover {
            transform: translateY(-4px);
            border-bottom: 7px solid #0369a1;
            box-shadow: 0 26px 48px -10px rgba(2, 132, 199, 0.42);
        }

        .card-theme-maroon {
            background: linear-gradient(180deg, #fff1f2 0%, #ffffff 42%, #ffffff 100%);
            border-radius: 1.5rem;
            border: 1.5px solid rgba(244, 63, 94, 0.45);
            border-bottom: 6px solid #881337;
            box-shadow:
                0 18px 38px -8px rgba(136, 19, 55, 0.28),
                0 6px 14px -3px rgba(136, 19, 55, 0.12),
                inset 0 2px 3px rgba(255, 255, 255, 0.98);
            transition: all 0.25s cubic-bezier(0.34, 1.56, 0.64, 1);
            position: relative;
        }
        .card-theme-maroon:hover {
            transform: translateY(-4px);
            border-bottom: 7px solid #4c0519;
            box-shadow: 0 26px 48px -10px rgba(136, 19, 55, 0.42);
        }

        .card-theme-orange {
            background: linear-gradient(180deg, #fff7ed 0%, #ffffff 42%, #ffffff 100%);
            border-radius: 1.5rem;
            border: 1.5px solid rgba(205, 56, 5, 0.45);
            border-bottom: 6px solid #cd3805;
            box-shadow:
                0 18px 38px -8px rgba(205, 56, 5, 0.28),
                0 6px 14px -3px rgba(205, 56, 5, 0.12),
                inset 0 2px 3px rgba(255, 255, 255, 0.98);
            transition: all 0.25s cubic-bezier(0.34, 1.56, 0.64, 1);
            position: relative;
        }
        .card-theme-orange:hover {
            transform: translateY(-4px);
            border-bottom: 7px solid #a62800;
            box-shadow: 0 26px 48px -10px rgba(205, 56, 5, 0.42);
        }

        .card-theme-amber {
            background: linear-gradient(180deg, #fefce8 0%, #ffffff 42%, #ffffff 100%);
            border-radius: 1.5rem;
            border: 1.5px solid rgba(250, 204, 21, 0.5);
            border-bottom: 6px solid #ca8a04;
            box-shadow:
                0 16px 34px -8px rgba(202, 138, 4, 0.25),
                0 4px 12px -2px rgba(202, 138, 4, 0.12),
                inset 0 2px 3px rgba(255, 255, 255, 0.98);
            transition: all 0.25s cubic-bezier(0.34, 1.56, 0.64, 1);
            position: relative;
        }
        .card-theme-amber:hover {
            transform: translateY(-4px);
            border-bottom: 7px solid #a16207;
            box-shadow: 0 24px 44px -8px rgba(202, 138, 4, 0.38);
        }

        .card-theme-green {
            background: linear-gradient(180deg, #ecfdf5 0%, #ffffff 42%, #ffffff 100%);
            border-radius: 1.5rem;
            border: 1.5px solid rgba(52, 211, 153, 0.5);
            border-bottom: 6px solid #059669;
            box-shadow:
                0 16px 34px -8px rgba(5, 150, 105, 0.25),
                0 4px 12px -2px rgba(5, 150, 105, 0.12),
                inset 0 2px 3px rgba(255, 255, 255, 0.98);
            transition: all 0.25s cubic-bezier(0.34, 1.56, 0.64, 1);
            position: relative;
        }
        .card-theme-green:hover {
            transform: translateY(-4px);
            border-bottom: 7px solid #047857;
            box-shadow: 0 24px 44px -8px rgba(5, 150, 105, 0.38);
        }

        .card-theme-tasks {
            background: linear-gradient(180deg, #eff6ff 0%, #ffffff 42%, #ffffff 100%);
            border-radius: 1.5rem;
            border: 1.5px solid rgba(147, 197, 253, 0.6);
            border-bottom: 6px solid #0056d2;
            box-shadow:
                0 16px 34px -8px rgba(0, 86, 210, 0.22),
                0 4px 12px -2px rgba(0, 86, 210, 0.1),
                inset 0 2px 3px rgba(255, 255, 255, 0.98);
            transition: all 0.25s cubic-bezier(0.34, 1.56, 0.64, 1);
            position: relative;
        }
        .card-theme-tasks:hover {
            transform: translateY(-4px);
            border-bottom: 7px solid #0b327b;
            box-shadow: 0 24px 44px -8px rgba(0, 86, 210, 0.35);
        }

        /* Explicit Grid Definitions that NEVER break or collapse */
        .row2-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1.25rem;
            align-items: stretch;
            width: 100%;
        }
        @media (min-width: 1024px) {
            .row2-grid {
                grid-template-columns: 1.55fr 1.25fr 1.2fr;
            }
        }
        @media (min-width: 1400px) {
            .row2-grid {
                grid-template-columns: 1.8fr 1.1fr 1.1fr;
            }
        }

        .row3-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1.25rem;
            align-items: stretch;
            width: 100%;
        }
        @media (min-width: 640px) {
            .row3-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        @media (min-width: 1024px) {
            .row3-grid {
                grid-template-columns: repeat(4, 1fr);
            }
        }

        /* Solid Row 4 Grid: Department Performance (45%) / Invoices (30%) / AI Smart Insight (25%) */
        .row4-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1.25rem;
            align-items: stretch;
            width: 100%;
        }
        @media (min-width: 1024px) {
            .row4-grid {
                grid-template-columns: 1.65fr 1.15fr 1.2fr;
            }
        }
        @media (min-width: 1400px) {
            .row4-grid {
                grid-template-columns: 1.7fr 1.1fr 1.2fr;
            }
        }

        /* 3D Cyberpunk AI Smart Insight Widget matching Electric Blue theme */
        .card-theme-ai {
            background: linear-gradient(135deg, #07255e 0%, #0056d2 40%, #0284c7 80%, #0ea5e9 100%);
            border-radius: 1.5rem;
            border: 1.5px solid rgba(56, 189, 248, 0.45);
            border-bottom: 6px solid #061e47;
            box-shadow:
                0 18px 40px -6px rgba(0, 86, 210, 0.45),
                0 6px 14px -2px rgba(0, 86, 210, 0.25),
                inset 0 1.5px 2px rgba(255, 255, 255, 0.35);
            color: #ffffff;
            position: relative;
            overflow: hidden;
            transition: all 0.25s cubic-bezier(0.34, 1.56, 0.64, 1);
        }
        .card-theme-ai:hover {
            transform: translateY(-4px);
            box-shadow: 0 26px 52px -8px rgba(0, 86, 210, 0.65);
            border-bottom: 7px solid #031535;
        }

        /* 3D Elevated Tender Micro-Card */
        .tender-card-3d {
            background: linear-gradient(180deg, #ffffff 0%, #fbfdff 100%);
            border-radius: 1.25rem;
            border: 1.5px solid #e2e8f0;
            border-bottom: 4.5px solid #cbd5e1;
            box-shadow: 0 10px 24px -4px rgba(100, 116, 139, 0.10), inset 0 1.5px 2px rgba(255, 255, 255, 0.95);
            transition: all 0.25s cubic-bezier(0.34, 1.56, 0.64, 1);
            padding: 1.15rem;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .tender-card-3d:hover {
            transform: translateY(-4px);
            border-color: #94a3b8;
            border-bottom: 5.5px solid #64748b;
            box-shadow: 0 18px 32px -6px rgba(100, 116, 139, 0.20);
        }

        /* ------------------------------------------------------------- */
        /* TOP 4 VIBRANT 3D STAT CARDS (EXACT MATCH TO media_1794960037908_15a2248a) */
        /* ------------------------------------------------------------- */
        .stat-card-3d-wrap {
            position: relative;
            transition: all 0.28s cubic-bezier(0.34, 1.56, 0.64, 1);
            cursor: pointer;
        }
        .stat-card-3d-wrap:hover {
            transform: translateY(-5px);
        }

        .stat-card-3d {
            position: relative;
            background: #ffffff;
            border-radius: 1.45rem;
            padding: 1.15rem 1.25rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.85rem;
            transition: all 0.25s ease;
        }

        /* 1. Electric Blue Card (TOTAL BILL AMOUNT) */
        .stat-card-blue {
            background: linear-gradient(135deg, #ffffff 0%, #ffffff 80%, #f0f7ff 100%);
            border: 3px solid #0056d2;
            border-bottom: 6.5px solid #0056d2;
            box-shadow:
                0 16px 32px -6px rgba(0, 86, 210, 0.32),
                0 4px 12px rgba(0, 0, 0, 0.05),
                inset 0 2px 2px rgba(255, 255, 255, 0.95);
        }
        .stat-card-3d-wrap:hover .stat-card-blue {
            border-color: #0056d2;
            border-bottom-width: 8px;
            box-shadow:
                0 22px 42px -6px rgba(0, 86, 210, 0.45),
                0 8px 18px rgba(0, 0, 0, 0.08),
                inset 0 2px 2px rgba(255, 255, 255, 0.95);
        }

        /* 2. Emerald Green Card (TOTAL CHEQUE AMOUNT) */
        .stat-card-green {
            background: linear-gradient(135deg, #ffffff 0%, #ffffff 80%, #f0fdf4 100%);
            border: 3px solid #0d9467;
            border-bottom: 6.5px solid #059669;
            box-shadow:
                0 16px 32px -6px rgba(16, 185, 129, 0.32),
                0 4px 12px rgba(0, 0, 0, 0.05),
                inset 0 2px 2px rgba(255, 255, 255, 0.95);
        }
        .stat-card-3d-wrap:hover .stat-card-green {
            border-bottom-width: 8px;
            box-shadow:
                0 22px 42px -6px rgba(16, 185, 129, 0.45),
                0 8px 18px rgba(0, 0, 0, 0.08),
                inset 0 2px 2px rgba(255, 255, 255, 0.95);
        }

        /* 3. Sunset Orange Card (TOTAL OUTSTANDING) - Exact Match to #cd3805 */
        .stat-card-orange {
            background: linear-gradient(135deg, #ffffff 0%, #ffffff 80%, #fff7ed 100%);
            border: 3px solid #cd3805;
            border-bottom: 6.5px solid #9a3412;
            box-shadow:
                0 16px 32px -6px rgba(205, 56, 5, 0.35),
                0 4px 12px rgba(0, 0, 0, 0.05),
                inset 0 2px 2px rgba(255, 255, 255, 0.95);
        }
        .stat-card-3d-wrap:hover .stat-card-orange {
            border-color: #cd3805;
            border-bottom-width: 8px;
            box-shadow:
                0 22px 42px -6px rgba(205, 56, 5, 0.48),
                0 8px 18px rgba(0, 0, 0, 0.08),
                inset 0 2px 2px rgba(255, 255, 255, 0.95);
        }

        /* 4. Deep Maroon / Dark Red Card (Available Surplus) */
        .stat-card-maroon {
            background: linear-gradient(135deg, #ffffff 0%, #ffffff 80%, #fff1f2 100%);
            border: 3px solid #881337;
            border-bottom: 6.5px solid #4c0519;
            box-shadow:
                0 16px 32px -6px rgba(136, 19, 55, 0.32),
                0 4px 12px rgba(0, 0, 0, 0.05),
                inset 0 2px 2px rgba(255, 255, 255, 0.95);
        }
        .stat-card-3d-wrap:hover .stat-card-maroon {
            border-color: #881337;
            border-bottom-width: 8px;
            box-shadow:
                0 22px 42px -6px rgba(136, 19, 55, 0.45),
                0 8px 18px rgba(0, 0, 0, 0.08),
                inset 0 2px 2px rgba(255, 255, 255, 0.95);
        }

        /* 3D Extruded Candy Icon Tile on Left */
        .stat-candy-icon {
            width: 3.35rem;
            height: 3.35rem;
            border-radius: 1.15rem;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            color: #ffffff;
            transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        }
        .stat-card-3d-wrap:hover .stat-candy-icon {
            transform: scale(1.1) rotate(-4deg);
        }

        .candy-icon-blue {
            background: linear-gradient(135deg, #1d72fe 0%, #0056d2 60%, #003ea1 100%);
            border-top: 1.5px solid rgba(255, 255, 255, 0.7);
            border-bottom: 4px solid #00358a;
            box-shadow: 0 8px 18px rgba(0, 86, 210, 0.45), inset 0 1px 2px rgba(255, 255, 255, 0.6);
        }
        .candy-icon-green {
            background: linear-gradient(135deg, #34d399 0%, #10b981 60%, #059669 100%);
            border-top: 1.5px solid rgba(255, 255, 255, 0.7);
            border-bottom: 4px solid #064e3b;
            box-shadow: 0 8px 18px rgba(16, 185, 129, 0.45), inset 0 1px 2px rgba(255, 255, 255, 0.6);
        }
        .candy-icon-orange {
            background: linear-gradient(135deg, #fa8228 0%, #fa7a1f 60%, #f77015 100%);
            border-top: 1.5px solid rgba(255, 255, 255, 0.75);
            border-bottom: 4px solid #9a3412;
            box-shadow: 0 8px 18px rgba(250, 122, 31, 0.45), inset 0 1px 2px rgba(255, 255, 255, 0.6);
        }
        .candy-icon-maroon {
            background: linear-gradient(135deg, #e11d48 0%, #9f1239 60%, #4c0519 100%);
            border-top: 1.5px solid rgba(255, 255, 255, 0.65);
            border-bottom: 4px solid #360210;
            box-shadow: 0 8px 18px rgba(136, 19, 55, 0.45), inset 0 1px 2px rgba(255, 255, 255, 0.5);
        }

        /* High-Contrast Card Typography */
        .stat-card-label {
            font-family: 'Plus Jakarta Sans', 'Inter', system-ui, sans-serif;
            font-size: 0.9rem !important;
            font-weight: 800;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            display: block;
            margin-bottom: 0.15rem;
        }

        .stat-card-blue .stat-card-label {
            color: #0056d2 !important; /* Exact 0056d2 theme */
        }
        .stat-card-green .stat-card-label {
            color: #059669 !important; /* Elegant medium emerald */
        }
        .stat-card-orange .stat-card-label {
            color: #ea580c !important; /* Vibrant orange */
        }
        .stat-card-maroon .stat-card-label {
            color: #9f1239 !important; /* Elegant deep maroon */
        }

        .stat-card-value {
            font-family: 'Plus Jakarta Sans', 'Inter', system-ui, -apple-system, sans-serif;
            font-weight: 900;
            color: #0f172a;
            letter-spacing: -0.02em;
            font-feature-settings: "tnum";
            font-variant-numeric: tabular-nums;
            display: inline-flex;
            align-items: baseline;
            text-shadow: 0 1px 1px rgba(0, 0, 0, 0.04);
            -webkit-font-smoothing: antialiased;
        }

        .stat-card-currency {
            font-size: 0.8em;
            font-weight: 900;
            color: #0f172a;
            margin-right: 0.35rem;
            letter-spacing: 0.01em;
        }

        /* 1. Deep Electric Blue Amount & Currency (#0056d2) */
        .stat-card-blue .stat-card-value,
        .stat-card-blue .stat-card-currency {
            color: #0056d2 !important;
        }

        /* 2. Deep Dark Green Amount & Currency (#064e3b) */
        .stat-card-green .stat-card-value,
        .stat-card-green .stat-card-currency {
            color: #064e3b !important;
        }

        /* 3. Deep Dark Orange Amount & Currency (#cd3805) */
        .stat-card-orange .stat-card-value,
        .stat-card-orange .stat-card-currency {
            color: #cd3805 !important;
        }

        /* 4. Deep Dark Red / Maroon Amount & Currency (#4c0519) */
        .stat-card-maroon .stat-card-value,
        .stat-card-maroon .stat-card-currency {
            color: #4c0519 !important;
        }

        /* 3D Elevated Filter Pill Dropdowns */
        .filter-pill {
            background: #ffffff;
            border: 1.5px solid #e2e8f0;
            border-bottom: 3px solid #cbd5e1;
            border-radius: 9999px;
            padding: 0.28rem 0.8rem;
            font-size: 0.72rem;
            font-weight: 800;
            color: #334155;
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            cursor: pointer;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
            transition: all 0.15s ease;
        }
        .filter-pill:hover {
            border-color: #94a3b8;
            border-bottom-color: #64748b;
            color: #0f172a;
            background: #f8fafc;
            transform: translateY(-1px);
        }
        .filter-pill:active {
            transform: translateY(1.5px);
            border-bottom-width: 1.5px;
        }

        /* 3D Candy Status Pill Tags with Extrusion */
        .tag-pill-sale {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: #ffffff;
            border-radius: 9999px;
            font-size: 0.65rem;
            font-weight: 900;
            padding: 0.22rem 0.7rem;
            border-bottom: 2.5px solid #047857;
            box-shadow: 0 4px 10px rgba(16, 185, 129, 0.35);
        }
        .tag-pill-purchase {
            background: linear-gradient(135deg, #38bdf8 0%, #2563eb 100%);
            color: #ffffff;
            border-radius: 9999px;
            font-size: 0.65rem;
            font-weight: 900;
            padding: 0.22rem 0.7rem;
            border-bottom: 2.5px solid #1d4ed8;
            box-shadow: 0 4px 10px rgba(37, 99, 235, 0.35);
        }
        .tag-pill-payment {
            background: linear-gradient(135deg, #fbbf24 0%, #ea580c 100%);
            color: #ffffff;
            border-radius: 9999px;
            font-size: 0.65rem;
            font-weight: 900;
            padding: 0.22rem 0.7rem;
            border-bottom: 2.5px solid #9a3412;
            box-shadow: 0 4px 10px rgba(234, 88, 12, 0.35);
        }
        .tag-pill-inventory {
            background: linear-gradient(135deg, #be123c 0%, #881337 100%);
            color: #ffffff;
            border-radius: 9999px;
            font-size: 0.65rem;
            font-weight: 900;
            padding: 0.22rem 0.7rem;
            border-bottom: 2.5px solid #4c0519;
            box-shadow: 0 4px 10px rgba(136, 19, 55, 0.35);
        }

        /* 3D Action Buttons with Keycap Push */
        .btn-funky-voucher {
            background: linear-gradient(135deg, #fb923c 0%, #fa7a1f 50%, #cd3805 100%);
            border: 1px solid rgba(255, 255, 255, 0.45);
            border-bottom: 4.5px solid #9a3412;
            box-shadow: 0 10px 24px -2px rgba(250, 122, 31, 0.45), inset 0 1.5px 2px rgba(255, 255, 255, 0.5);
            border-radius: 1rem;
            color: #ffffff;
            font-weight: 900;
            transition: all 0.2s cubic-bezier(0.34, 1.56, 0.64, 1);
        }
        .btn-funky-voucher:hover {
            transform: translateY(-2px);
            box-shadow: 0 14px 28px -2px rgba(205, 56, 5, 0.55);
        }
        .btn-funky-voucher:active {
            transform: translateY(2px);
            border-bottom-width: 1.5px;
        }

        .btn-funky-bill {
            background: linear-gradient(135deg, #38bdf8 0%, #0056d2 50%, #0b327b 100%);
            border: 1px solid rgba(255, 255, 255, 0.45);
            border-bottom: 4.5px solid #07255e;
            box-shadow: 0 10px 24px -2px rgba(0, 86, 210, 0.45), inset 0 1.5px 2px rgba(255, 255, 255, 0.5);
            border-radius: 1rem;
            color: #ffffff;
            font-weight: 900;
            transition: all 0.2s cubic-bezier(0.34, 1.56, 0.64, 1);
        }
        .btn-funky-bill:hover {
            transform: translateY(-2px);
            box-shadow: 0 14px 28px -2px rgba(11, 50, 123, 0.55);
        }
        .btn-funky-bill:active {
            transform: translateY(2px);
            border-bottom-width: 1.5px;
        }

        .btn-funky-tender {
            background: linear-gradient(135deg, #34d399 0%, #0d9467 50%, #064e3b 100%);
            border: 1px solid rgba(255, 255, 255, 0.45);
            border-bottom: 4.5px solid #064e3b;
            box-shadow: 0 10px 24px -2px rgba(13, 148, 103, 0.45), inset 0 1.5px 2px rgba(255, 255, 255, 0.5);
            border-radius: 1rem;
            color: #ffffff;
            font-weight: 900;
            transition: all 0.2s cubic-bezier(0.34, 1.56, 0.64, 1);
        }
        .btn-funky-tender:hover {
            transform: translateY(-2px);
            box-shadow: 0 14px 28px -2px rgba(6, 78, 59, 0.55);
        }
        .btn-funky-tender:active {
            transform: translateY(2px);
            border-bottom-width: 1.5px;
        }

        /* Dynamic Hand Wave Animation */
        @keyframes wave-hand {
            0% { transform: rotate(0deg); }
            15% { transform: rotate(14deg); }
            30% { transform: rotate(-10deg); }
            45% { transform: rotate(14deg); }
            60% { transform: rotate(-6deg); }
            75% { transform: rotate(10deg); }
            100% { transform: rotate(0deg); }
        }
        .animate-wave {
            display: inline-block;
            transform-origin: 70% 70%;
            animation: wave-hand 2.2s infinite ease-in-out;
            cursor: pointer;
        }
    </style>

    @php
        // Dynamic time-of-day calculations based on local time
        $currentHour = (int) now()->format('H');
        if ($currentHour >= 5 && $currentHour < 12) {
            $timeGreeting = 'Good Morning';
            $timeEmoji = '☀️';
            $timeBadge = 'Morning Shift';
            $timeGradient = 'linear-gradient(135deg, #f59e0b 0%, #f97316 50%, #f43f5e 100%)';
            $timeSubtitle = "Rise & shine! Here's what's happening with your tenders, bills & treasury today.";
        } elseif ($currentHour >= 12 && $currentHour < 17) {
            $timeGreeting = 'Good Afternoon';
            $timeEmoji = '🌤️';
            $timeBadge = 'Afternoon Session';
            $timeGradient = 'linear-gradient(135deg, #38bdf8 0%, #2563eb 50%, #4f46e5 100%)';
            $timeSubtitle = "Mid-day pulse check on departmental procurement, budget releases & clearing vouchers.";
        } elseif ($currentHour >= 17 && $currentHour < 21) {
            $timeGreeting = 'Good Evening';
            $timeEmoji = '🌆';
            $timeBadge = 'Evening Review';
            $timeGradient = 'linear-gradient(135deg, #fa7a1f 0%, #ea580c 50%, #cd3805 100%)';
            $timeSubtitle = "Wrapping up today's fiscal clearance, pending tender sanctions & audit logs.";
        } else {
            $timeGreeting = 'Good Night';
            $timeEmoji = '🌙';
            $timeBadge = 'Night Monitoring';
            $timeGradient = 'linear-gradient(135deg, #07255e 0%, #0056d2 50%, #0284c7 100%)';
            $timeSubtitle = "Late-night automated settlement pipeline & treasury balance monitoring.";
        }

        $userName = auth()->check() ? auth()->user()->name : 'Finance Admin';
        $userRole = auth()->check() ? (auth()->user()->role ?? 'Administrator') : 'Admin';
    @endphp

    <div class="dashboard-canvas py-6 px-4 sm:px-6 lg:px-8 xl:px-10">
        <div class="w-full space-y-6">

            <!-- 1. TOP HEADER BAR WITH DYNAMIC TIME-OF-DAY GREETING -->
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 glass-panel p-4 sm:p-5">
                <!-- Top 4-Color Accent Line Matching the 4 Stat Cards -->
                <div class="h-1.5 w-full bg-gradient-to-r from-[#0056d2] via-[#0d9467] via-[#fa7a1f] to-[#881337] absolute top-0 inset-x-0 rounded-t-2xl"></div>

                <!-- Left: Dynamic Time Greeting, 3D Icon & Live Info -->
                <div class="flex items-center gap-3.5 pt-0.5">
                    <div id="dynamicGreetingIconWrap"
                         class="w-12 h-12 rounded-2xl flex items-center justify-center shrink-0 shadow-md transition-all duration-500 relative"
                         style="background: {{ $timeGradient }}; box-shadow: 0 8px 20px -2px rgba(0, 86, 210, 0.35);">
                        <span id="dynamicGreetingIcon" class="text-2xl select-none filter drop-shadow">{{ $timeEmoji }}</span>
                        <!-- Pulsing live indicator dot -->
                        <span class="absolute -top-1 -right-1 w-3.5 h-3.5 rounded-full bg-emerald-400 border-2 border-white animate-pulse"></span>
                    </div>

                    <div>
                        <div class="flex items-center flex-wrap gap-2">
                            <h1 class="text-lg sm:text-2xl font-black text-slate-900 tracking-tight flex items-center gap-1.5">
                                <span id="dynamicGreetingText">{{ $timeGreeting }}, {{ $userName }}</span>
                                <span class="animate-wave">👋</span>
                            </h1>
                            <span id="dynamicGreetingBadge" class="px-2.5 py-0.5 rounded-full text-[10px] font-black text-white inline-flex items-center gap-1.5 shadow-xs"
                                  style="background: linear-gradient(135deg, #10b981 0%, #06b6d4 100%);">
                                <span class="w-1.5 h-1.5 rounded-full bg-white animate-pulse"></span>
                                <span id="dynamicBadgeLabel">{{ $timeBadge }}</span>
                            </span>
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold text-slate-500 bg-slate-100 border border-slate-200 hidden sm:inline-flex items-center gap-1.5 shadow-xs">
                                <svg class="w-3 h-3 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <span id="dynamicLiveDate">{{ now()->format('l, d M Y') }}</span>
                                <span class="text-slate-300">•</span>
                                <span id="dynamicLiveClock" class="font-mono font-bold text-[#0056d2]">{{ now()->format('h:i A') }}</span>
                            </span>
                        </div>
                        <p id="dynamicGreetingSubtitle" class="text-xs font-semibold text-slate-400 mt-0.5 flex items-center gap-1.5 flex-wrap">
                            <span class="text-[#0056d2] font-extrabold uppercase text-[10px] tracking-wider bg-blue-50 px-1.5 py-0.5 rounded border border-blue-100">{{ ucfirst($userRole) }}</span>
                            <span class="text-slate-300">•</span>
                            <span id="dynamicSubtitleText">{{ $timeSubtitle }}</span>
                        </p>
                    </div>
                </div>

                <!-- Center: Clean Neumorphic Search Bar -->
                <div class="w-full lg:max-w-sm relative">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </span>
                    <input type="text"
                           placeholder="Search bills, vouchers, inventory..."
                           class="w-full pl-10 pr-12 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all shadow-inner">
                    <span class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none">
                        <kbd class="px-1.5 py-0.5 text-[9px] font-black text-slate-400 bg-white rounded border border-slate-200 shadow-xs">⌘ K</kbd>
                    </span>
                </div>

                <!-- Right: Action Buttons (Sunset Orange & Electric Blue 3D Keys) -->
                <div class="flex items-center flex-wrap gap-2.5 justify-end">
                    <a href="{{ route('bills.create') }}" class="btn-funky-voucher px-3.5 py-2 text-xs flex items-center gap-1.5 shadow-sm">
                        <svg class="w-4 h-4 text-orange-100" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <rect x="2" y="4" width="20" height="16" rx="2"></rect>
                            <line x1="2" y1="10" x2="22" y2="10"></line>
                        </svg>
                        <span>+ Issue Voucher</span>
                    </a>

                    <a href="{{ route('bills.create') }}" class="btn-funky-bill px-3.5 py-2 text-xs flex items-center gap-1.5 shadow-sm">
                        <svg class="w-4 h-4 text-sky-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                        </svg>
                        <span>Generate Bill</span>
                    </a>
                </div>
            </div>

            <!-- 2. TOP 4 VIBRANT 3D STAT CARDS (EXACT MATCH TO media_1794960037908_15a2248a) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">

                <!-- CARD 1: TOTAL BILL AMOUNT (Electric Blue 3D Theme) -->
                <div class="stat-card-3d-wrap group">
                    <div class="stat-card-3d stat-card-blue">
                        <!-- Left: 3D Candy Icon Button -->
                        <div class="stat-candy-icon candy-icon-blue">
                            <svg viewBox="0 0 24 24" class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                                <line x1="3" y1="6" x2="21" y2="6"></line>
                                <path d="M16 10a4 4 0 0 1-8 0"></path>
                            </svg>
                        </div>

                        <!-- Center: Content -->
                        <div class="min-w-0 flex-1 px-1">
                            <span class="stat-card-label">TOTAL BUDGET AMOUNT</span>
                            <div class="text-lg xl:text-xl 2xl:text-2xl stat-card-value my-0.5 whitespace-nowrap">
                                <span class="stat-card-currency">Rs.</span>
                                <span data-counter-target="{{ (int)($stats['total_budget'] > 0 ? $stats['total_budget'] : 910000000) }}">
                                    {{ number_format($stats['total_budget'] > 0 ? $stats['total_budget'] : 910000000) }}
                                </span>
                            </div>
                            <div class="mt-1 flex items-center">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-blue-50/90 text-blue-900 border border-blue-100">
                                    <span class="text-emerald-600 font-bold flex items-center">
                                        <svg class="w-3 h-3 inline mr-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                                        +12.5%
                                    </span>
                                    <span class="text-slate-600 font-medium">vs last month</span>
                                </span>
                            </div>
                        </div>

                        <!-- Right: Mini 3-bar indicator -->
                        <div class="flex items-end gap-1 h-7 opacity-80 self-center pl-0.5 shrink-0">
                            <span class="w-1.5 rounded-full bg-blue-300" style="height: 45%;"></span>
                            <span class="w-1.5 rounded-full bg-blue-400" style="height: 70%;"></span>
                            <span class="w-1.5 rounded-full" style="background-color: #0056d2; height: 100%;"></span>
                        </div>
                    </div>
                </div>

                <!-- CARD 2: TOTAL CHEQUE AMOUNT (Emerald Green 3D Theme) -->
                <div class="stat-card-3d-wrap group">
                    <div class="stat-card-3d stat-card-green">
                        <!-- Left: 3D Candy Icon Button -->
                        <div class="stat-candy-icon candy-icon-green">
                            <svg viewBox="0 0 24 24" class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                            </svg>
                        </div>

                        <!-- Center: Content -->
                        <div class="min-w-0 flex-1 px-1">
                            <span class="stat-card-label">TOTAL CHEQUE AMOUNT</span>
                            <div class="text-lg xl:text-xl 2xl:text-2xl stat-card-value my-0.5 whitespace-nowrap">
                                <span class="stat-card-currency">Rs.</span>
                                <span data-counter-target="{{ (int)($stats['total_released'] > 0 ? $stats['total_released'] : 265000000) }}">
                                    {{ number_format($stats['total_released'] > 0 ? $stats['total_released'] : 265000000) }}
                                </span>
                            </div>
                            <div class="mt-1 flex items-center">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-50/90 text-emerald-900 border border-emerald-100">
                                    <span class="text-emerald-600 font-bold flex items-center">
                                        <svg class="w-3 h-3 inline mr-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                                        +8.2%
                                    </span>
                                    <span class="text-slate-600 font-medium">vs last month</span>
                                </span>
                            </div>
                        </div>

                        <!-- Right: Mini 3-bar indicator -->
                        <div class="flex items-end gap-1 h-7 opacity-80 self-center pl-0.5 shrink-0">
                            <span class="w-1.5 rounded-full bg-emerald-300" style="height: 50%;"></span>
                            <span class="w-1.5 rounded-full bg-emerald-400" style="height: 75%;"></span>
                            <span class="w-1.5 rounded-full bg-emerald-600" style="height: 100%;"></span>
                        </div>
                    </div>
                </div>

                <!-- CARD 3: TOTAL BILL AMOUNT (Sunset Orange 3D Theme) -->
                <div class="stat-card-3d-wrap group">
                    <div class="stat-card-3d stat-card-orange">
                        <!-- Left: 3D Candy Icon Button (Exact to media_1795215304621_e107bf6f) -->
                        <div class="stat-candy-icon candy-icon-orange">
                            <svg viewBox="0 0 24 24" class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8"/>
                                <path d="M21 3v5h-5"/>
                                <path d="M21 12a9 9 0 0 1-9 9 9.75 9.75 0 0 1-6.74-2.74L3 16"/>
                                <path d="M8 16H3v5"/>
                            </svg>
                        </div>

                        <!-- Center: Content -->
                        <div class="min-w-0 flex-1 px-1">
                            <span class="stat-card-label">TOTAL OUTSTANDING</span>
                            @php
                                $billedAmount = $stats['total_billed'] > 0 ? (float)$stats['total_billed'] : 8030837.90;
                            @endphp
                            <div class="text-lg xl:text-xl 2xl:text-2xl stat-card-value my-0.5 whitespace-nowrap">
                                <span class="stat-card-currency">Rs.</span>
                                <span data-counter-target="{{ $billedAmount }}" data-counter-decimals="2">
                                    {{ number_format($billedAmount, 2) }}
                                </span>
                            </div>
                            <div class="mt-1 flex items-center">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-orange-50/90 text-orange-950 border border-orange-200">
                                    <span class="text-rose-600 font-bold flex items-center">
                                        <svg class="w-3 h-3 inline mr-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
                                        -2.1%
                                    </span>
                                    <span class="text-slate-600 font-medium">vs last month</span>
                                </span>
                            </div>
                        </div>

                        <!-- Right: Mini 3-bar indicator -->
                        <div class="flex items-end gap-1 h-7 opacity-80 self-center pl-0.5 shrink-0">
                            <span class="w-1.5 rounded-full bg-orange-300" style="height: 75%;"></span>
                            <span class="w-1.5 rounded-full bg-orange-400" style="height: 45%;"></span>
                            <span class="w-1.5 rounded-full" style="background-color: #cd3805; height: 60%;"></span>
                        </div>
                    </div>
                </div>

                <!-- CARD 4: Available Surplus (Deep Maroon / Dark Red 3D Theme) -->
                <div class="stat-card-3d-wrap group">
                    <div class="stat-card-3d stat-card-maroon">
                        <!-- Left: 3D Candy Icon Button -->
                        <div class="stat-candy-icon candy-icon-maroon">
                            <svg viewBox="0 0 24 24" class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <ellipse cx="12" cy="5" rx="9" ry="3"></ellipse>
                                <path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"></path>
                                <path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"></path>
                            </svg>
                        </div>

                        <!-- Center: Content -->
                        <div class="min-w-0 flex-1 px-1">
                            <span class="stat-card-label">VENDOR PROFIT</span>
                            <div class="text-lg xl:text-xl 2xl:text-2xl stat-card-value my-0.5 whitespace-nowrap">
                                <span class="stat-card-currency">Rs.</span>
                                <span data-counter-target="{{ (int)($stats['available_balance'] > 0 ? $stats['available_balance'] : 246861588) }}">
                                    {{ number_format($stats['available_balance'] > 0 ? $stats['available_balance'] : 246861588) }}
                                </span>
                            </div>
                            <div class="mt-1 flex items-center">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-rose-50/90 text-rose-900 border border-rose-100">
                                    <span class="text-emerald-600 font-bold flex items-center">
                                        <svg class="w-3 h-3 inline mr-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                                        +15.6%
                                    </span>
                                    <span class="text-slate-600 font-medium">vs last month</span>
                                </span>
                            </div>
                        </div>

                        <!-- Right: Mini 3-bar indicator -->
                        <div class="flex items-end gap-1 h-7 opacity-80 self-center pl-0.5 shrink-0">
                            <span class="w-1.5 rounded-full bg-rose-200" style="height: 45%;"></span>
                            <span class="w-1.5 rounded-full bg-rose-400" style="height: 80%;"></span>
                            <span class="w-1.5 rounded-full" style="background-color: #881337; height: 100%;"></span>
                        </div>
                    </div>
                </div>

            </div>

            <!-- 3. ROW 2: 3 COLUMNS (Rock-Solid Row 2 Grid: 50% / 25% / 25% with Vibrant 3D Color Themes) -->
            <div class="row2-grid">

                <!-- COLUMN 1 (50%): Sales & Purchases Overview (Dual Wave Chart - Vibrant Blue / Cyan Theme) -->
                <div class="card-theme-blue p-5 flex flex-col justify-between">
                    <div class="h-1.5 w-full bg-gradient-to-r from-blue-500 via-sky-400 to-[#0056d2] absolute top-0 inset-x-0 rounded-t-2xl"></div>

                    <!-- Header -->
                    <div class="flex items-center justify-between gap-2 pb-3 border-b border-sky-100 min-w-0">
                        <div class="flex items-center gap-2 min-w-0 flex-1">
                            <span class="w-8 h-8 rounded-xl bg-blue-500 text-white flex items-center justify-center font-bold shadow-xs shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/></svg>
                            </span>
                            <h2 class="text-xs sm:text-sm font-black text-slate-800 whitespace-nowrap truncate">Sales & Purchases Overview</h2>
                        </div>

                        <div class="flex items-center gap-2 shrink-0">
                            <div class="hidden sm:flex items-center gap-2 text-[11px] font-bold">
                                <span class="flex items-center gap-1 text-sky-600"><span class="w-2 h-2 rounded-full bg-sky-500"></span> Sales</span>
                                <span class="flex items-center gap-1 text-[#cd3805]"><span class="w-2 h-2 rounded-full" style="background-color: #cd3805;"></span> Purchases</span>
                            </div>
                            <button class="filter-pill shrink-0 whitespace-nowrap text-[10px] px-2.5 py-1">
                                <span>This Month</span>
                                <svg class="w-3 h-3 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                        </div>
                    </div>

                    <!-- Dual Interactive Wave Chart Container -->
                    <div class="relative w-full h-56 pt-3">
                        <!-- Y-Axis Labels & Grid Lines -->
                        <div class="absolute inset-0 flex flex-col justify-between pointer-events-none text-[10px] font-mono font-bold text-slate-400 pb-6 pr-2">
                            <div class="flex items-center gap-2 w-full"><span class="w-6 shrink-0">4M</span><div class="flex-1 border-b border-dashed border-sky-100"></div></div>
                            <div class="flex items-center gap-2 w-full"><span class="w-6 shrink-0">3M</span><div class="flex-1 border-b border-dashed border-sky-100"></div></div>
                            <div class="flex items-center gap-2 w-full"><span class="w-6 shrink-0">2M</span><div class="flex-1 border-b border-dashed border-sky-100"></div></div>
                            <div class="flex items-center gap-2 w-full"><span class="w-6 shrink-0">1M</span><div class="flex-1 border-b border-dashed border-sky-100"></div></div>
                            <div class="flex items-center gap-2 w-full"><span class="w-6 shrink-0">0M</span><div class="flex-1 border-b border-sky-200"></div></div>
                        </div>

                        <!-- SVG Dual Wave Lines (Stretches edge-to-edge from Y-axis to right boundary) -->
                        <div style="position: absolute; left: 32px; right: 8px; top: 8px; bottom: 24px;">
                            <svg viewBox="0 0 600 160" class="w-full h-full overflow-visible" preserveAspectRatio="none">
                                <defs>
                                    <linearGradient id="fillBlueWave" x1="0" y1="0" x2="0" y2="1">
                                        <stop offset="0%" stop-color="#38bdf8" stop-opacity="0.48"/>
                                        <stop offset="100%" stop-color="#38bdf8" stop-opacity="0.0"/>
                                    </linearGradient>
                                    <linearGradient id="fillOrangeWave" x1="0" y1="0" x2="0" y2="1">
                                        <stop offset="0%" stop-color="#ea580c" stop-opacity="0.42"/>
                                        <stop offset="100%" stop-color="#cd3805" stop-opacity="0.0"/>
                                    </linearGradient>
                                    <filter id="waveGlowBlue" x="-10%" y="-10%" width="120%" height="120%">
                                        <feDropShadow dx="0" dy="3" stdDeviation="3" flood-color="#0284c7" flood-opacity="0.4"/>
                                    </filter>
                                </defs>

                                <path d="M 0 110 Q 75 120, 150 85 T 300 95 T 450 45 T 600 30 L 600 160 L 0 160 Z" fill="url(#fillBlueWave)"/>
                                <path d="M 0 135 Q 75 145, 150 110 T 300 120 T 450 80 T 600 65 L 600 160 L 0 160 Z" fill="url(#fillOrangeWave)"/>

                                <path d="M 0 135 Q 75 145, 150 110 T 300 120 T 450 80 T 600 65" fill="none" stroke="#cd3805" stroke-width="3" stroke-linecap="round"/>
                                <path d="M 0 110 Q 75 120, 150 85 T 300 95 T 450 45 T 600 30" fill="none" stroke="#0284c7" stroke-width="3.5" stroke-linecap="round" filter="url(#waveGlowBlue)"/>

                                <!-- Nodes on Blue Curve (Sales) -->
                                <circle cx="20" cy="112" r="4.5" fill="#ffffff" stroke="#0284c7" stroke-width="3"/>
                                <circle cx="150" cy="85" r="4.5" fill="#ffffff" stroke="#0284c7" stroke-width="3"/>
                                <circle cx="300" cy="95" r="4.5" fill="#ffffff" stroke="#0284c7" stroke-width="3"/>
                                <circle cx="450" cy="45" r="5.5" fill="#ffffff" stroke="#0284c7" stroke-width="3.5"/>
                                <circle cx="585" cy="32" r="4.5" fill="#ffffff" stroke="#0284c7" stroke-width="3"/>

                                <!-- Nodes on Orange Curve (Purchases) -->
                                <circle cx="20" cy="136" r="3.5" fill="#ffffff" stroke="#cd3805" stroke-width="2.5"/>
                                <circle cx="150" cy="110" r="3.5" fill="#ffffff" stroke="#cd3805" stroke-width="2.5"/>
                                <circle cx="300" cy="120" r="3.5" fill="#ffffff" stroke="#cd3805" stroke-width="2.5"/>
                                <circle cx="450" cy="80" r="4" fill="#ffffff" stroke="#cd3805" stroke-width="2.5"/>
                                <circle cx="585" cy="67" r="3.5" fill="#ffffff" stroke="#cd3805" stroke-width="2.5"/>
                            </svg>

                            <!-- Tooltip at 21 Nov (450/600 = 75%) -->
                            <div class="absolute left-[75%] top-[12%] -translate-x-1/2 -translate-y-full bg-slate-900 border border-sky-400 text-white px-2.5 py-1 rounded-lg shadow-xl flex flex-col items-center pointer-events-none z-10"
                                 style="box-shadow: 0 8px 18px rgba(2, 132, 199, 0.45);">
                                <span class="text-[11px] font-black font-mono">Rs. 3.64M</span>
                                <span class="text-[8px] font-bold text-sky-400">Sales</span>
                            </div>
                        </div>

                        <!-- X-Axis Labels (Stretches edge-to-edge from Y-axis to right boundary) -->
                        <div style="position: absolute; left: 32px; right: 8px; bottom: 0;" class="flex justify-between text-[10px] font-bold text-slate-400 font-mono">
                            <span>1 Nov</span>
                            <span>7 Nov</span>
                            <span>14 Nov</span>
                            <span>21 Nov</span>
                            <span>30 Nov</span>
                        </div>
                    </div>
                </div>

                <!-- COLUMN 2: Business Analytics (Donut + Totals - Deep Maroon Theme) -->
                <div class="card-theme-maroon p-4 sm:p-5 flex flex-col justify-between min-w-0">
                    <div class="h-1.5 w-full bg-gradient-to-r from-rose-500 via-rose-600 to-rose-900 absolute top-0 inset-x-0 rounded-t-2xl"></div>

                    <!-- Header -->
                    <div class="flex items-center justify-between gap-1 pb-3 border-b border-rose-100 min-w-0">
                        <div class="flex items-center gap-2 min-w-0 flex-1">
                            <span class="w-8 h-8 rounded-xl bg-[#881337] text-white flex items-center justify-center font-bold shadow-xs shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </span>
                            <h2 class="text-xs sm:text-sm font-black text-slate-800 whitespace-nowrap truncate">Business Analytics</h2>
                        </div>
                        <button class="filter-pill shrink-0 whitespace-nowrap text-[10px] px-2.5 py-1">
                            <span>This Year</span>
                            <svg class="w-3 h-3 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                    </div>

                    <!-- Middle: Donut Chart with fixed explicit sizing + Legend -->
                    <div class="flex items-center justify-center gap-3 sm:gap-4 py-2">
                        <!-- Donut SVG with strict bounded dimensions -->
                        <div class="relative shrink-0 flex items-center justify-center" style="width: 105px; height: 105px;">
                            <svg width="105" height="105" viewBox="0 0 100 100" class="-rotate-90">
                                <defs>
                                    <linearGradient id="donutSalesGrad" x1="0" y1="0" x2="1" y2="1">
                                        <stop offset="0%" stop-color="#38bdf8"/>
                                        <stop offset="50%" stop-color="#0056d2"/>
                                        <stop offset="100%" stop-color="#0b327b"/>
                                    </linearGradient>
                                    <linearGradient id="donutPurchGrad" x1="0" y1="0" x2="1" y2="1">
                                        <stop offset="0%" stop-color="#fb923c"/>
                                        <stop offset="100%" stop-color="#cd3805"/>
                                    </linearGradient>
                                </defs>
                                <circle cx="50" cy="50" r="38" fill="none" stroke="#f1f5f9" stroke-width="13"/>
                                <circle cx="50" cy="50" r="38" fill="none" stroke="url(#donutPurchGrad)" stroke-width="13"
                                        stroke-dasharray="238.7" stroke-dashoffset="162" stroke-linecap="round"/>
                                <circle cx="50" cy="50" r="38" fill="none" stroke="url(#donutSalesGrad)" stroke-width="13"
                                        stroke-dasharray="238.7" stroke-dashoffset="76" stroke-linecap="round"/>
                            </svg>

                            <!-- Center Text -->
                            <div class="absolute inset-0 flex flex-col items-center justify-center text-center pointer-events-none">
                                <span class="text-xl font-black font-mono text-slate-900 leading-none" data-counter-target="68" data-counter-suffix="%">68%</span>
                                <span class="text-[9px] font-bold text-slate-400 mt-0.5">Growth</span>
                            </div>
                        </div>

                        <!-- Right Legend -->
                        <div class="space-y-2 text-xs font-bold shrink-0">
                            <div class="flex items-center gap-1.5">
                                <span class="w-3 h-3 rounded-full shrink-0" style="background-color: #0056d2;"></span>
                                <span class="text-slate-600">Sales</span>
                                <span class="font-mono text-slate-900 ml-1.5 font-black">68%</span>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <span class="w-3 h-3 rounded-full shrink-0" style="background-color: #cd3805;"></span>
                                <span class="text-slate-600">Purchases</span>
                                <span class="font-mono text-slate-900 ml-1.5 font-black">32%</span>
                            </div>
                        </div>
                    </div>

                    <!-- Bottom 2 Cards (Revenue & Expenses) -->
                    <div class="grid grid-cols-2 gap-2 pt-1">
                        <div class="bg-emerald-50/80 border border-emerald-200 rounded-xl p-2.5 flex items-center gap-2">
                            <div class="w-7 h-7 rounded-lg bg-emerald-500 text-white flex items-center justify-center shrink-0">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                            </div>
                            <div class="min-w-0">
                                <span class="text-[9px] font-bold text-emerald-800 uppercase block leading-none">Revenue</span>
                                <span class="text-[11px] font-black font-mono text-slate-900 truncate block mt-0.5" data-counter-target="45.2" data-counter-float data-counter-suffix="M">45.2M</span>
                            </div>
                        </div>

                        <div class="bg-rose-50/80 border border-rose-200 rounded-xl p-2.5 flex items-center gap-2">
                            <div class="w-7 h-7 rounded-lg bg-[#881337] text-white flex items-center justify-center shrink-0">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                            </div>
                            <div class="min-w-0">
                                <span class="text-[9px] font-bold text-rose-900 uppercase block leading-none">Expenses</span>
                                <span class="text-[11px] font-black font-mono text-slate-900 truncate block mt-0.5" data-counter-target="18.1" data-counter-float data-counter-suffix="M">18.1M</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- COLUMN 3: Recent Activities (Timeline List - Dynamic Activities) -->
                <div class="card-theme-orange p-4 sm:p-5 flex flex-col justify-between min-w-0">
                    <div class="h-1.5 w-full bg-gradient-to-r from-orange-400 via-[#fa7a1f] to-[#cd3805] absolute top-0 inset-x-0 rounded-t-2xl"></div>

                    <!-- Header -->
                    <div class="flex items-center justify-between gap-1 pb-3 border-b border-orange-100 min-w-0">
                        <div class="flex items-center gap-2 min-w-0 flex-1">
                            <span class="w-8 h-8 rounded-xl bg-[#cd3805] text-white flex items-center justify-center font-bold shadow-xs shrink-0">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd"/></svg>
                            </span>
                            <h2 class="text-xs sm:text-sm font-black text-slate-800 whitespace-nowrap truncate">Recent Activities</h2>
                        </div>
                        <a href="{{ route('bills.index') }}" class="text-xs font-black text-[#cd3805] hover:text-[#9a3412] shrink-0 whitespace-nowrap">View All ›</a>
                    </div>

                    <!-- Activities List (Dynamic with Smooth Fallback) -->
                    <div class="space-y-3 py-1">
                        @if(isset($recentActivities) && $recentActivities->count() > 0)
                            @foreach($recentActivities->take(4) as $act)
                                <div class="flex items-center justify-between gap-2">
                                    <div class="flex items-center gap-2 min-w-0 flex-1">
                                        <div class="w-8 h-8 rounded-xl bg-[#cd3805] text-white flex items-center justify-center shrink-0 shadow-xs">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <div class="text-xs font-bold text-slate-800 truncate">{{ $act->description }}</div>
                                            <div class="text-[10px] text-slate-400 truncate">{{ $act->user_name ?: 'Admin' }} • {{ $act->created_at ? $act->created_at->diffForHumans(null, true) : 'just now' }}</div>
                                        </div>
                                    </div>
                                    <span class="tag-pill-payment shrink-0 text-[10px]">{{ ucfirst($act->action ?: 'Log') }}</span>
                                </div>
                            @endforeach
                        @else
                            <div class="flex items-center justify-between gap-2">
                                <div class="flex items-center gap-2 min-w-0">
                                    <div class="w-8 h-8 rounded-xl bg-emerald-500 text-white flex items-center justify-center shrink-0 shadow-xs">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                    </div>
                                    <div class="min-w-0">
                                        <div class="text-xs font-bold text-slate-800 truncate">New Sale #INV-118</div>
                                        <div class="text-[10px] text-slate-400 truncate">Ahmed Ali • 2m ago</div>
                                    </div>
                                </div>
                                <span class="tag-pill-sale shrink-0">Sale</span>
                            </div>

                            <div class="flex items-center justify-between gap-2">
                                <div class="flex items-center gap-2 min-w-0">
                                    <div class="w-8 h-8 rounded-xl bg-[#0056d2] text-white flex items-center justify-center shrink-0 shadow-xs">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                                    </div>
                                    <div class="min-w-0">
                                        <div class="text-xs font-bold text-slate-800 truncate">Purchase #PUR-067</div>
                                        <div class="text-[10px] text-slate-400 truncate">Sara Khan • 5m ago</div>
                                    </div>
                                </div>
                                <span class="tag-pill-purchase shrink-0">Purchase</span>
                            </div>

                            <div class="flex items-center justify-between gap-2">
                                <div class="flex items-center gap-2 min-w-0">
                                    <div class="w-8 h-8 rounded-xl bg-[#cd3805] text-white flex items-center justify-center shrink-0 shadow-xs">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    </div>
                                    <div class="min-w-0">
                                        <div class="text-xs font-bold text-slate-800 truncate">Paid: Rs. 250k</div>
                                        <div class="text-[10px] text-slate-400 truncate">ABC Traders • 8m ago</div>
                                    </div>
                                </div>
                                <span class="tag-pill-payment shrink-0">Payment</span>
                            </div>

                            <div class="flex items-center justify-between gap-2">
                                <div class="flex items-center gap-2 min-w-0">
                                    <div class="w-8 h-8 rounded-xl bg-[#881337] text-white flex items-center justify-center shrink-0 shadow-xs">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                    </div>
                                    <div class="min-w-0">
                                        <div class="text-xs font-bold text-slate-800 truncate">Stock: 500 units</div>
                                        <div class="text-[10px] text-slate-400 truncate">Rice 5kg • 12m ago</div>
                                    </div>
                                </div>
                                <span class="tag-pill-inventory shrink-0">Inventory</span>
                            </div>
                        @endif
                    </div>
                </div>

            </div>

            <!-- 3.5 ACTIVE TENDERS & PROCUREMENT CENTRAL BOARD -->
            <div class="glass-panel p-5 sm:p-6 min-w-0">
                <!-- Top holographic accent line -->
                <div class="h-1.5 w-full bg-gradient-to-r from-[#0056d2] via-[#0d9467] to-teal-400 absolute top-0 inset-x-0 rounded-t-2xl"></div>

                <!-- Section Header -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 border-b border-slate-100">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-2xl flex items-center justify-center shrink-0 shadow-sm"
                             style="background: linear-gradient(135deg, #0ea5e9 0%, #0056d2 100%);">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <h2 class="text-base sm:text-lg font-black text-slate-900 tracking-tight">Active Tenders & Procurement Board</h2>
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-black text-emerald-700 bg-emerald-50 border border-emerald-200">
                                    {{ $stats['total_tenders'] ?? 7 }} Total
                                </span>
                            </div>
                            <p class="text-xs font-semibold text-slate-400 mt-0.5">
                                Live tenders, evaluation pipelines, contractor bids & budget clearance
                            </p>
                        </div>
                    </div>

                    <!-- Right Quick Actions -->
                    <div class="flex items-center gap-2 self-start sm:self-auto flex-wrap">
                        <div class="hidden md:flex items-center gap-2 mr-1">
                            <span class="px-2.5 py-1 rounded-xl text-xs font-extrabold text-sky-700 bg-sky-50 border border-sky-200 flex items-center gap-1.5 shadow-2xs">
                                <span class="w-2 h-2 rounded-full bg-sky-500 animate-pulse"></span>
                                <span>{{ $stats['active_tenders'] ?? 2 }} Open Bidding</span>
                            </span>
                            <span class="px-2.5 py-1 rounded-xl text-xs font-extrabold text-amber-700 bg-amber-50 border border-amber-200 flex items-center gap-1.5 shadow-2xs">
                                <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                                <span>{{ $stats['total_bids'] ?? 3 }} Bids Filed</span>
                            </span>
                        </div>
                        <a href="{{ route('tenders.create') }}" class="btn-funky-tender px-3.5 py-2 text-xs flex items-center gap-1.5 shadow-sm">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                            <span>+ Float Tender</span>
                        </a>
                        <a href="{{ route('tenders.index') }}" class="px-3 py-1.5 rounded-xl text-xs font-black text-slate-700 bg-slate-100 hover:bg-slate-200 border border-slate-300 transition-all">
                            <span>View All ({{ $stats['total_tenders'] ?? 7 }}) →</span>
                        </a>
                    </div>
                </div>

                <!-- Dynamic Tenders Grid (3D Micro-Cards) -->
                @php
                    $displayTenders = isset($recentTenders) && $recentTenders->count() > 0
                        ? $recentTenders
                        : (isset($openTenders) && $openTenders->count() > 0 ? $openTenders : (isset($evaluationTenders) ? $evaluationTenders : collect()));
                @endphp

                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4 pt-4">
                    @forelse($displayTenders->take(6) as $tender)
                        <div class="tender-card-3d">
                            <!-- Card Header: Dept + Status -->
                            <div>
                                <div class="flex items-center justify-between gap-2 mb-2">
                                    <div class="flex items-center gap-1.5 min-w-0">
                                        <span class="w-2 h-2 rounded-full bg-[#0056d2] shrink-0"></span>
                                        <span class="text-[10px] font-black uppercase tracking-wider text-blue-700 bg-blue-50 px-2 py-0.5 rounded border border-blue-100 truncate">
                                            {{ $tender->department->name ?? 'General Department' }}
                                        </span>
                                    </div>
                                    <span class="shrink-0 text-[10px] font-black px-2 py-0.5 rounded-full border
                                        @if($tender->status == 'published') bg-emerald-50 text-emerald-700 border-emerald-200
                                        @elseif($tender->status == 'under_evaluation') bg-amber-50 text-amber-700 border-amber-200
                                        @elseif($tender->status == 'awarded') bg-blue-50 text-blue-700 border-blue-200
                                        @else bg-slate-50 text-slate-700 border-slate-200 @endif">
                                        {{ ucfirst(str_replace('_', ' ', $tender->status)) }}
                                    </span>
                                </div>

                                <!-- Tender Title -->
                                <a href="{{ route('tenders.show', $tender->id) }}" class="text-xs sm:text-sm font-extrabold text-slate-800 hover:text-[#0056d2] transition-colors line-clamp-2 block mb-1">
                                    {{ $tender->title }}
                                </a>

                                <!-- Reference & Category -->
                                <div class="flex items-center gap-2 text-[10px] text-slate-400 font-semibold mb-3">
                                    <span class="font-mono font-bold text-slate-600 bg-slate-100 px-1.5 py-0.5 rounded">{{ $tender->reference_no ?? 'TND-2026-00'.$tender->id }}</span>
                                    <span>•</span>
                                    <span>{{ $tender->category ?? 'Procurement' }}</span>
                                </div>
                            </div>

                            <!-- Card Footer: Budget + Deadline + Action -->
                            <div class="pt-3 border-t border-slate-100 flex items-center justify-between gap-2 mt-auto">
                                <div>
                                    <span class="text-[9px] uppercase font-bold text-slate-400 block">Est. Budget</span>
                                    <span class="text-xs font-black font-mono text-slate-900">
                                        {{ $tender->budget ? 'Rs. ' . number_format($tender->budget) : 'Rs. 25,000,000' }}
                                    </span>
                                </div>

                                <div class="text-right">
                                    <span class="text-[9px] uppercase font-bold text-slate-400 block">Deadline</span>
                                    <span class="text-[10px] font-bold text-rose-600 font-mono">
                                        {{ $tender->submission_deadline ? \Carbon\Carbon::parse($tender->submission_deadline)->format('d M Y') : '30 Nov 2026' }}
                                    </span>
                                </div>

                                <a href="{{ route('tenders.show', $tender->id) }}" class="p-1.5 rounded-lg bg-blue-50 hover:bg-blue-100 text-[#0056d2] transition-colors shadow-2xs" title="View Tender">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full py-8 text-center text-slate-400">
                            <span class="text-3xl block mb-2">📑</span>
                            <span class="text-xs font-bold">No active tenders found.</span>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- 4. ROW 3: 4 CARDS (Rock-Solid 4-Column Grid with Vibrant 3D Color Themes) -->
            <div class="row3-grid">

                <!-- CARD 1: Top Selling Products (Golden Amber Theme - Dynamic from BillItems) -->
                <div class="card-theme-amber p-4 sm:p-5 flex flex-col justify-between min-w-0">
                    <div class="h-1.5 w-full bg-gradient-to-r from-amber-400 to-yellow-500 absolute top-0 inset-x-0 rounded-t-2xl"></div>

                    <div class="flex items-center justify-between pb-3 border-b border-amber-100 min-w-0">
                        <div class="flex items-center gap-2 min-w-0">
                            <span class="text-base shrink-0">🏆</span>
                            <h2 class="text-xs sm:text-sm font-black text-slate-800 whitespace-nowrap truncate">Top Selling Products</h2>
                        </div>
                        <button class="filter-pill shrink-0 whitespace-nowrap text-[10px] px-2 py-1">
                            <span>This Month</span>
                            <svg class="w-3 h-3 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                    </div>

                    <div class="space-y-3 py-2">
                        @php
                            $maxQty = isset($topProducts) && $topProducts->count() > 0 ? $topProducts->max('total_qty') : 100;
                        @endphp
                        @if(isset($topProducts) && $topProducts->count() > 0)
                            @foreach($topProducts as $prod)
                                <div>
                                    <div class="flex items-center justify-between text-xs font-bold text-slate-800 mb-1">
                                        <div class="flex items-center gap-1.5 truncate flex-1 min-w-0 mr-2">
                                            <span class="w-5 h-5 rounded bg-amber-100 text-amber-800 flex items-center justify-center text-[10px] shrink-0">📦</span>
                                            <span class="truncate">{{ $prod->item_name }}</span>
                                        </div>
                                        <span class="font-mono text-slate-900 font-extrabold shrink-0" data-counter-target="{{ (int)$prod->total_qty }}" data-counter-suffix=" Pcs">{{ number_format($prod->total_qty) }} Pcs</span>
                                    </div>
                                    <div class="w-full bg-amber-100/60 h-2 rounded-full overflow-hidden">
                                        <div class="bg-gradient-to-r from-cyan-400 to-blue-600 h-full rounded-full transition-all duration-1000" style="width: {{ min(100, max(25, round(($prod->total_qty / $maxQty) * 100))) }}%;"></div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div>
                                <div class="flex items-center justify-between text-xs font-bold text-slate-800 mb-1">
                                    <div class="flex items-center gap-1.5 truncate">
                                        <span class="w-5 h-5 rounded bg-amber-100 text-amber-800 flex items-center justify-center text-[10px] shrink-0">🌾</span>
                                        <span class="truncate">Sunridge Flour 10kg</span>
                                    </div>
                                    <span class="font-mono text-slate-900 font-extrabold shrink-0 ml-2" data-counter-target="8200" data-counter-suffix=" Pcs">8,200 Pcs</span>
                                </div>
                                <div class="w-full bg-amber-100/60 h-2 rounded-full overflow-hidden">
                                    <div class="bg-gradient-to-r from-cyan-400 to-blue-600 h-full rounded-full" style="width: 82%;"></div>
                                </div>
                            </div>

                            <div>
                                <div class="flex items-center justify-between text-xs font-bold text-slate-800 mb-1">
                                    <div class="flex items-center gap-1.5 truncate">
                                        <span class="w-5 h-5 rounded bg-emerald-100 text-emerald-800 flex items-center justify-center text-[10px] shrink-0">🥛</span>
                                        <span class="truncate">Nestle Milk Pack</span>
                                    </div>
                                    <span class="font-mono text-slate-900 font-extrabold shrink-0 ml-2" data-counter-target="6400" data-counter-suffix=" Pcs">6,400 Pcs</span>
                                </div>
                                <div class="w-full bg-emerald-100/60 h-2 rounded-full overflow-hidden">
                                    <div class="bg-gradient-to-r from-lime-400 to-green-600 h-full rounded-full" style="width: 64%;"></div>
                                </div>
                            </div>

                            <div>
                                <div class="flex items-center justify-between text-xs font-bold text-slate-800 mb-1">
                                    <div class="flex items-center gap-1.5 truncate">
                                        <span class="w-5 h-5 rounded bg-orange-100 text-orange-800 flex items-center justify-center text-[10px] shrink-0">☕</span>
                                        <span class="truncate">Tapal Tea 500g</span>
                                    </div>
                                    <span class="font-mono text-slate-900 font-extrabold shrink-0 ml-2" data-counter-target="5200" data-counter-suffix=" Pcs">5,200 Pcs</span>
                                </div>
                                <div class="w-full bg-orange-100/60 h-2 rounded-full overflow-hidden">
                                    <div class="bg-gradient-to-r from-amber-400 to-orange-500 h-full rounded-full" style="width: 52%;"></div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- CARD 2: Stock Status (Lush Emerald Theme) -->
                <div class="card-theme-green p-4 sm:p-5 flex flex-col justify-between min-w-0">
                    <div class="h-1.5 w-full bg-gradient-to-r from-emerald-400 to-teal-500 absolute top-0 inset-x-0 rounded-t-2xl"></div>

                    <div class="flex items-center justify-between pb-3 border-b border-emerald-100 min-w-0">
                        <div class="flex items-center gap-2 min-w-0">
                            <span class="text-base shrink-0">🧊</span>
                            <h2 class="text-xs sm:text-sm font-black text-slate-800 whitespace-nowrap truncate">Stock Status</h2>
                        </div>
                        <button class="filter-pill shrink-0 whitespace-nowrap text-[10px] px-2 py-1">
                            <span>All</span>
                            <svg class="w-3 h-3 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                    </div>

                    <div class="flex items-center justify-center gap-3 py-1">
                        <div class="relative shrink-0 flex items-center justify-center" style="width: 100px; height: 100px;">
                            <svg width="100" height="100" viewBox="0 0 100 100" class="-rotate-90">
                                <circle cx="50" cy="50" r="38" fill="none" stroke="#f1f5f9" stroke-width="12"/>
                                <circle cx="50" cy="50" r="38" fill="none" stroke="#ef4444" stroke-width="12"
                                        stroke-dasharray="238.7" stroke-dashoffset="215"/>
                                <circle cx="50" cy="50" r="38" fill="none" stroke="#f59e0b" stroke-width="12"
                                        stroke-dasharray="238.7" stroke-dashoffset="195"/>
                                <circle cx="50" cy="50" r="38" fill="none" stroke="#10b981" stroke-width="12"
                                        stroke-dasharray="238.7" stroke-dashoffset="67" stroke-linecap="round"/>
                            </svg>
                            <div class="absolute inset-0 flex flex-col items-center justify-center text-center pointer-events-none">
                                <span class="text-lg font-black font-mono text-slate-900 leading-none" data-counter-target="72" data-counter-suffix="%">72%</span>
                                <span class="text-[8px] font-bold text-slate-400 mt-0.5">In Stock</span>
                            </div>
                        </div>

                        <div class="space-y-1.5 text-[11px] font-bold shrink-0">
                            <div class="flex items-center gap-1.5">
                                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 shrink-0"></span>
                                <span class="text-slate-600">In Stock</span>
                                <span class="font-mono text-slate-900 ml-auto font-black pl-2">72%</span>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <span class="w-2.5 h-2.5 rounded-full bg-amber-500 shrink-0"></span>
                                <span class="text-slate-600">Low</span>
                                <span class="font-mono text-slate-900 ml-auto font-black pl-2">18%</span>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <span class="w-2.5 h-2.5 rounded-full bg-rose-500 shrink-0"></span>
                                <span class="text-slate-600">Out</span>
                                <span class="font-mono text-slate-900 ml-auto font-black pl-2">10%</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- CARD 3: Financial Overview (Electric Sky Blue Theme) -->
                <div class="card-theme-blue p-4 sm:p-5 flex flex-col justify-between min-w-0">
                    <div class="h-1.5 w-full bg-gradient-to-r from-sky-400 to-[#0056d2] absolute top-0 inset-x-0 rounded-t-2xl"></div>

                    <div class="flex items-center justify-between pb-3 border-b border-sky-100 min-w-0">
                        <div class="flex items-center gap-2 min-w-0">
                            <span class="text-base shrink-0">🧮</span>
                            <h2 class="text-xs sm:text-sm font-black text-slate-800 whitespace-nowrap truncate">Financial Overview</h2>
                        </div>
                        <button class="filter-pill shrink-0 whitespace-nowrap text-[10px] px-2 py-1">
                            <span>This Month</span>
                            <svg class="w-3 h-3 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                    </div>

                    <!-- Mini Wave Line Chart -->
                    <div class="w-full h-16 py-1 relative">
                        <svg viewBox="0 0 240 60" class="w-full h-full overflow-visible" preserveAspectRatio="none">
                            <defs>
                                <linearGradient id="finWaveGrad" x1="0" y1="0" x2="0" y2="1">
                                    <stop offset="0%" stop-color="#38bdf8" stop-opacity="0.45"/>
                                    <stop offset="100%" stop-color="#38bdf8" stop-opacity="0.0"/>
                                </linearGradient>
                            </defs>
                            <path d="M 0 45 Q 30 50, 60 35 T 120 40 T 180 20 T 240 12 L 240 60 L 0 60 Z" fill="url(#finWaveGrad)"/>
                            <path d="M 0 45 Q 30 50, 60 35 T 120 40 T 180 20 T 240 12" fill="none" stroke="#0284c7" stroke-width="2.5" stroke-linecap="round"/>
                            <circle cx="15" cy="46" r="3" fill="#ffffff" stroke="#0284c7" stroke-width="2"/>
                            <circle cx="60" cy="35" r="3" fill="#ffffff" stroke="#0284c7" stroke-width="2"/>
                            <circle cx="120" cy="40" r="3" fill="#ffffff" stroke="#0284c7" stroke-width="2"/>
                            <circle cx="180" cy="20" r="3" fill="#ffffff" stroke="#0284c7" stroke-width="2"/>
                            <circle cx="230" cy="14" r="3.5" fill="#ffffff" stroke="#0284c7" stroke-width="2"/>
                        </svg>
                    </div>

                    <!-- 3 Summary Pills with Live Counters -->
                    <div class="grid grid-cols-3 gap-1.5 pt-1 text-center">
                        <div class="bg-emerald-50 rounded-lg p-1.5 border border-emerald-200">
                            <span class="text-[10px] font-black font-mono text-emerald-900 block truncate" data-counter-target="3.64" data-counter-float data-counter-suffix="M">3.64M</span>
                            <span class="text-[8px] font-bold text-emerald-700 uppercase">Income</span>
                        </div>
                        <div class="bg-rose-50 rounded-lg p-1.5 border border-rose-200">
                            <span class="text-[10px] font-black font-mono text-rose-900 block truncate" data-counter-target="2.00" data-counter-float data-counter-suffix="M">2.00M</span>
                            <span class="text-[8px] font-bold text-rose-700 uppercase">Expense</span>
                        </div>
                        <div class="bg-amber-50 rounded-lg p-1.5 border border-amber-200">
                            <span class="text-[10px] font-black font-mono text-amber-900 block truncate" data-counter-target="1.42" data-counter-float data-counter-suffix="M">1.42M</span>
                            <span class="text-[8px] font-bold text-amber-700 uppercase">Profit</span>
                        </div>
                    </div>
                </div>

                <!-- CARD 4: Upcoming Tasks (Electric Blue Theme - Dynamic from Todos) -->
                <div class="card-theme-tasks p-4 sm:p-5 flex flex-col justify-between min-w-0">
                    <div class="h-1.5 w-full bg-gradient-to-r from-sky-400 to-[#0056d2] absolute top-0 inset-x-0 rounded-t-2xl"></div>

                    <div class="flex items-center justify-between pb-3 border-b border-blue-100 min-w-0">
                        <div class="flex items-center gap-2 min-w-0">
                            <span class="w-5 h-5 rounded-full bg-[#0056d2] text-white flex items-center justify-center text-[10px] font-black shrink-0">✔</span>
                            <h2 class="text-xs sm:text-sm font-black text-slate-800 whitespace-nowrap truncate">Upcoming Tasks</h2>
                        </div>
                        <a href="{{ route('todos.index') }}" class="text-xs font-black text-[#0056d2] hover:text-blue-900 shrink-0 whitespace-nowrap">View All ›</a>
                    </div>

                    <div class="space-y-2.5 py-1">
                        @if(isset($upcomingTodos) && $upcomingTodos->count() > 0)
                            @foreach($upcomingTodos->take(3) as $todo)
                                <div class="flex items-start gap-2">
                                    <input type="checkbox" class="mt-0.5 rounded text-[#0056d2] focus:ring-blue-400 border-blue-300" {{ $todo->is_completed ? 'checked' : '' }}>
                                    <div class="min-w-0 flex-1">
                                        <span class="text-xs font-bold text-slate-800 block truncate">{{ $todo->title }}</span>
                                        <span class="text-[8px] font-black {{ $todo->is_overdue ? 'text-rose-600 bg-rose-50 border-rose-200' : 'text-[#0056d2] bg-blue-50 border-blue-200' }} px-1.5 py-0.5 rounded border inline-block mt-0.5">
                                            {{ $todo->due_date ? $todo->due_date->format('M d') : 'Active' }} • {{ ucfirst($todo->priority) }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="flex items-start gap-2">
                                <input type="checkbox" class="mt-0.5 rounded text-[#0056d2] focus:ring-blue-400 border-blue-300">
                                <div class="min-w-0 flex-1">
                                    <span class="text-xs font-bold text-slate-800 block truncate">Call supplier for pending order</span>
                                    <span class="text-[8px] font-black text-rose-600 bg-rose-50 px-1.5 py-0.5 rounded border border-rose-200 inline-block mt-0.5">Today • High</span>
                                </div>
                            </div>

                            <div class="flex items-start gap-2">
                                <input type="checkbox" class="mt-0.5 rounded text-[#0056d2] focus:ring-blue-400 border-blue-300">
                                <div class="min-w-0 flex-1">
                                    <span class="text-xs font-bold text-slate-800 block truncate">Verify sales tax format</span>
                                    <span class="text-[8px] font-black text-amber-600 bg-amber-50 px-1.5 py-0.5 rounded border border-amber-200 inline-block mt-0.5">Tomorrow • Med</span>
                                </div>
                            </div>

                            <div class="flex items-start gap-2">
                                <input type="checkbox" class="mt-0.5 rounded text-[#0056d2] focus:ring-blue-400 border-blue-300">
                                <div class="min-w-0 flex-1">
                                    <span class="text-xs font-bold text-slate-800 block truncate">Update tender documents</span>
                                    <span class="text-[8px] font-black text-[#0056d2] bg-blue-50 px-1.5 py-0.5 rounded border border-blue-200 inline-block mt-0.5">Nov 25 • Low</span>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

            </div>

            <!-- 5. ROW 4: 3 PANELS matching media_1794961202820_b9f1955f (Dept Performance, Recent Invoices, AI Smart Insight) -->
            <div class="row4-grid">

                <!-- PANEL 1: Department Performance (4 Circular Ring Gauges - 45% width) -->
                <div class="card-theme-blue p-4 sm:p-5 flex flex-col justify-between min-w-0">
                    <div class="h-1.5 w-full bg-gradient-to-r from-blue-500 via-sky-400 to-[#0056d2] absolute top-0 inset-x-0 rounded-t-2xl"></div>

                    <!-- Header -->
                    <div class="flex items-center justify-between gap-1 pb-3 border-b border-sky-100 min-w-0">
                        <div class="flex items-center gap-2 min-w-0">
                            <span class="w-8 h-8 rounded-xl bg-blue-500 text-white flex items-center justify-center font-bold shadow-xs shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            </span>
                            <h2 class="text-xs sm:text-sm font-black text-slate-800 whitespace-nowrap truncate">Department Performance</h2>
                        </div>
                        <button class="filter-pill shrink-0 whitespace-nowrap text-[10px] px-2.5 py-1">
                            <span>This Year</span>
                            <svg class="w-3 h-3 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                    </div>

                    <!-- 4 Circular Ring Gauges -->
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 py-3 items-center justify-around">
                        <svg class="hidden">
                            <defs>
                                <linearGradient id="ringGradBlue" x1="0" y1="0" x2="1" y2="1">
                                    <stop offset="0%" stop-color="#38bdf8"/>
                                    <stop offset="100%" stop-color="#2563eb"/>
                                </linearGradient>
                                <linearGradient id="ringGradCyan" x1="0" y1="0" x2="1" y2="1">
                                    <stop offset="0%" stop-color="#06b6d4"/>
                                    <stop offset="100%" stop-color="#10b981"/>
                                </linearGradient>
                                <linearGradient id="ringGradOrange" x1="0" y1="0" x2="1" y2="1">
                                    <stop offset="0%" stop-color="#fbbf24"/>
                                    <stop offset="100%" stop-color="#ea580c"/>
                                </linearGradient>
                                <linearGradient id="ringGradGreen" x1="0" y1="0" x2="1" y2="1">
                                    <stop offset="0%" stop-color="#34d399"/>
                                    <stop offset="100%" stop-color="#059669"/>
                                </linearGradient>
                            </defs>
                        </svg>

                        <!-- Ring 1: Sales Dept -->
                        <div class="flex flex-col items-center text-center">
                            <div class="relative w-16 h-16 sm:w-20 sm:h-20 flex items-center justify-center">
                                <svg class="w-full h-full -rotate-90" viewBox="0 0 36 36">
                                    <path class="text-slate-100" stroke-width="3.5" stroke="currentColor" fill="none"
                                          d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                                    <path stroke-width="3.5" stroke-dasharray="92, 100" stroke-linecap="round" stroke="url(#ringGradBlue)" fill="none"
                                          d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                                </svg>
                                <span class="absolute text-xs sm:text-sm font-black font-mono text-slate-900" data-counter-target="92" data-counter-suffix="%">92%</span>
                            </div>
                            <span class="text-xs font-black text-slate-800 mt-2 truncate max-w-[100px]">Sales Dept</span>
                            <span class="text-[10px] font-extrabold text-slate-400 font-mono mt-0.5">Rs. 18.2M</span>
                        </div>

                        <!-- Ring 2: Purchase Dept -->
                        <div class="flex flex-col items-center text-center">
                            <div class="relative w-16 h-16 sm:w-20 sm:h-20 flex items-center justify-center">
                                <svg class="w-full h-full -rotate-90" viewBox="0 0 36 36">
                                    <path class="text-slate-100" stroke-width="3.5" stroke="currentColor" fill="none"
                                          d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                                    <path stroke-width="3.5" stroke-dasharray="78, 100" stroke-linecap="round" stroke="url(#ringGradCyan)" fill="none"
                                          d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                                </svg>
                                <span class="absolute text-xs sm:text-sm font-black font-mono text-slate-900" data-counter-target="78" data-counter-suffix="%">78%</span>
                            </div>
                            <span class="text-xs font-black text-slate-800 mt-2 truncate max-w-[100px]">Purchase Dept</span>
                            <span class="text-[10px] font-extrabold text-slate-400 font-mono mt-0.5">Rs. 12.4M</span>
                        </div>

                        <!-- Ring 3: Inventory Dept -->
                        <div class="flex flex-col items-center text-center">
                            <div class="relative w-16 h-16 sm:w-20 sm:h-20 flex items-center justify-center">
                                <svg class="w-full h-full -rotate-90" viewBox="0 0 36 36">
                                    <path class="text-slate-100" stroke-width="3.5" stroke="currentColor" fill="none"
                                          d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                                    <path stroke-width="3.5" stroke-dasharray="65, 100" stroke-linecap="round" stroke="url(#ringGradOrange)" fill="none"
                                          d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                                </svg>
                                <span class="absolute text-xs sm:text-sm font-black font-mono text-slate-900" data-counter-target="65" data-counter-suffix="%">65%</span>
                            </div>
                            <span class="text-xs font-black text-slate-800 mt-2 truncate max-w-[100px]">Inventory Dept</span>
                            <span class="text-[10px] font-extrabold text-slate-400 font-mono mt-0.5">Rs. 8.2M</span>
                        </div>

                        <!-- Ring 4: Accounts Dept -->
                        <div class="flex flex-col items-center text-center">
                            <div class="relative w-16 h-16 sm:w-20 sm:h-20 flex items-center justify-center">
                                <svg class="w-full h-full -rotate-90" viewBox="0 0 36 36">
                                    <path class="text-slate-100" stroke-width="3.5" stroke="currentColor" fill="none"
                                          d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                                    <path stroke-width="3.5" stroke-dasharray="95, 100" stroke-linecap="round" stroke="url(#ringGradGreen)" fill="none"
                                          d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                                </svg>
                                <span class="absolute text-xs sm:text-sm font-black font-mono text-slate-900" data-counter-target="95" data-counter-suffix="%">95%</span>
                            </div>
                            <span class="text-xs font-black text-slate-800 mt-2 truncate max-w-[100px]">Accounts Dept</span>
                            <span class="text-[10px] font-extrabold text-slate-400 font-mono mt-0.5">Rs. 16.8M</span>
                        </div>
                    </div>
                </div>

                <!-- PANEL 2: Recent Invoices (30% width) -->
                <div class="card-theme-maroon p-4 sm:p-5 flex flex-col justify-between min-w-0">
                    <div class="h-1.5 w-full bg-gradient-to-r from-rose-500 via-rose-600 to-rose-900 absolute top-0 inset-x-0 rounded-t-2xl"></div>

                    <!-- Header -->
                    <div class="flex items-center justify-between gap-1 pb-3 border-b border-rose-100 min-w-0">
                        <div class="flex items-center gap-2 min-w-0">
                            <span class="w-8 h-8 rounded-xl bg-[#881337] text-white flex items-center justify-center font-bold shadow-xs shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            </span>
                            <h2 class="text-xs sm:text-sm font-black text-slate-800 whitespace-nowrap truncate">Recent Invoices</h2>
                        </div>
                        <a href="{{ route('bills.index') }}" class="text-xs font-black text-rose-700 hover:text-rose-900 shrink-0 whitespace-nowrap">View All →</a>
                    </div>

                    <!-- Invoices List -->
                    <div class="space-y-3 py-2">
                        @if(isset($recentBills) && $recentBills->count() > 0)
                            @foreach($recentBills->take(3) as $bill)
                                <div class="flex items-center justify-between gap-2.5">
                                    <div class="flex items-center gap-2.5 min-w-0 flex-1">
                                        <div class="w-8 h-8 rounded-xl {{ $bill->status == 'paid' ? 'bg-blue-500' : 'bg-emerald-500' }} text-white flex items-center justify-center shrink-0 shadow-xs">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <div class="text-xs font-extrabold text-slate-800 truncate font-mono">{{ $bill->bill_number }}</div>
                                            <div class="text-[10px] text-slate-400 truncate">{{ $bill->created_at ? $bill->created_at->format('d M Y') : '15 Nov 2026' }}</div>
                                        </div>
                                    </div>
                                    <div class="text-right shrink-0">
                                        <span class="text-xs font-black font-mono text-slate-900 block" data-counter-target="{{ (int)$bill->net_amount }}" data-counter-prefix="Rs. ">Rs. {{ number_format($bill->net_amount) }}</span>
                                    </div>
                                    <span class="{{ $bill->status == 'paid' ? 'tag-pill-sale' : 'tag-pill-purchase' }} shrink-0 text-[10px]">
                                        {{ ucfirst($bill->status) }}
                                    </span>
                                </div>
                            @endforeach
                        @else
                            <div class="flex items-center justify-between gap-2.5">
                                <div class="flex items-center gap-2.5 min-w-0 flex-1">
                                    <div class="w-8 h-8 rounded-xl bg-blue-500 text-white flex items-center justify-center shrink-0 shadow-xs">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <div class="text-xs font-extrabold text-slate-800 truncate font-mono">INV-2026-118</div>
                                        <div class="text-[10px] text-slate-400 truncate">15 Nov 2026</div>
                                    </div>
                                </div>
                                <span class="text-xs font-black font-mono text-slate-900 shrink-0" data-counter-target="250000" data-counter-prefix="Rs. ">Rs. 250,000</span>
                                <span class="tag-pill-sale shrink-0 text-[10px]">Paid</span>
                            </div>

                            <div class="flex items-center justify-between gap-2.5">
                                <div class="flex items-center gap-2.5 min-w-0 flex-1">
                                    <div class="w-8 h-8 rounded-xl bg-emerald-500 text-white flex items-center justify-center shrink-0 shadow-xs">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <div class="text-xs font-extrabold text-slate-800 truncate font-mono">PUR-2026-067</div>
                                        <div class="text-[10px] text-slate-400 truncate">14 Nov 2026</div>
                                    </div>
                                </div>
                                <span class="text-xs font-black font-mono text-slate-900 shrink-0" data-counter-target="180000" data-counter-prefix="Rs. ">Rs. 180,000</span>
                                <span class="tag-pill-purchase shrink-0 text-[10px]">Pending</span>
                            </div>

                            <div class="flex items-center justify-between gap-2.5">
                                <div class="flex items-center gap-2.5 min-w-0 flex-1">
                                    <div class="w-8 h-8 rounded-xl bg-blue-500 text-white flex items-center justify-center shrink-0 shadow-xs">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <div class="text-xs font-extrabold text-slate-800 truncate font-mono">INV-2026-117</div>
                                        <div class="text-[10px] text-slate-400 truncate">13 Nov 2026</div>
                                    </div>
                                </div>
                                <span class="text-xs font-black font-mono text-slate-900 shrink-0" data-counter-target="420000" data-counter-prefix="Rs. ">Rs. 420,000</span>
                                <span class="tag-pill-sale shrink-0 text-[10px]">Paid</span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- PANEL 3: Smart Business Insight (3D Cyberpunk AI Widget - 25% width) -->
                <div class="card-theme-ai p-4 sm:p-5 flex flex-col justify-between min-w-0">
                    <!-- Holographic Background Elements -->
                    <div class="absolute -top-12 -right-12 w-44 h-44 rounded-full bg-cyan-400/20 blur-2xl pointer-events-none"></div>
                    <div class="absolute -bottom-10 -left-10 w-44 h-44 rounded-full bg-pink-500/20 blur-2xl pointer-events-none"></div>

                    <!-- Header -->
                    <div class="flex items-center justify-between gap-2 pb-2 relative z-10">
                        <div class="flex items-center gap-2">
                            <span class="px-2.5 py-1 rounded-xl bg-white/20 border border-white/30 text-white text-xs font-black flex items-center gap-1.5 shadow-xs backdrop-blur-md">
                                <svg class="w-3.5 h-3.5 text-cyan-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="4" y="4" width="16" height="16" rx="2"></rect><rect x="9" y="9" width="6" height="6"></rect><line x1="9" y1="1" x2="9" y2="4"></line><line x1="15" y1="1" x2="15" y2="4"></line><line x1="9" y1="20" x2="9" y2="23"></line><line x1="15" y1="20" x2="15" y2="23"></line><line x1="20" y1="9" x2="23" y2="9"></line><line x1="20" y1="14" x2="23" y2="14"></line><line x1="1" y1="9" x2="4" y2="9"></line><line x1="1" y1="14" x2="4" y2="14"></line></svg>
                                <span>AI</span>
                            </span>
                            <h2 class="text-xs sm:text-sm font-black text-white tracking-tight">Smart Business Insight</h2>
                        </div>
                        <span class="text-[10px] text-emerald-300 font-extrabold flex items-center gap-1 shrink-0">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                            <span>Live</span>
                        </span>
                    </div>

                    <!-- Content & 3D Visual Mascot -->
                    <div class="flex items-center justify-between gap-2 py-1 relative z-10">
                        <!-- Left Content -->
                        <div class="flex-1 min-w-0 pr-1">
                            <p class="text-xs font-medium text-white/90 leading-relaxed">
                                Your sales have increased by <span class="text-cyan-300 font-black" data-counter-target="12.5" data-counter-float data-counter-suffix="%">+12.5%</span> this month compared to last month. Keep up the great work!
                            </p>
                            <a href="{{ route('bills.index') }}" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl text-xs font-black text-white bg-white/20 hover:bg-white/30 border border-white/40 shadow-md backdrop-blur-md transition-all mt-3">
                                <span>View Details</span>
                                <span class="text-cyan-300">›</span>
                            </a>
                        </div>

                        <!-- Right: Cute 3D Floating AI Robot Mascot -->
                        <div class="shrink-0 -mr-2">
                            <svg viewBox="0 0 140 140" class="w-24 h-24 sm:w-28 sm:h-28 drop-shadow-2xl overflow-visible">
                                <defs>
                                    <linearGradient id="botBody" x1="0" y1="0" x2="0" y2="1">
                                        <stop offset="0%" stop-color="#ffffff"/>
                                        <stop offset="100%" stop-color="#cbd5e1"/>
                                    </linearGradient>
                                    <linearGradient id="botCyan" x1="0" y1="0" x2="1" y2="1">
                                        <stop offset="0%" stop-color="#38bdf8"/>
                                        <stop offset="100%" stop-color="#0284c7"/>
                                    </linearGradient>
                                    <linearGradient id="barGrad" x1="0" y1="0" x2="0" y2="1">
                                        <stop offset="0%" stop-color="#38bdf8"/>
                                        <stop offset="100%" stop-color="#0056d2"/>
                                    </linearGradient>
                                </defs>
                                <!-- 3D Ascending Bars at the bottom -->
                                <rect x="75" y="110" width="12" height="25" rx="3" fill="url(#barGrad)" opacity="0.6"/>
                                <rect x="90" y="98" width="12" height="37" rx="3" fill="url(#barGrad)" opacity="0.8"/>
                                <rect x="105" y="85" width="12" height="50" rx="3" fill="url(#barGrad)"/>
                                <rect x="120" y="70" width="12" height="65" rx="3" fill="#38bdf8"/>
                                <!-- Floating Shadow -->
                                <ellipse cx="60" cy="125" rx="28" ry="7" fill="#1e1b4b" opacity="0.45"/>
                                <!-- Antenna -->
                                <line x1="60" y1="28" x2="60" y2="15" stroke="#cbd5e1" stroke-width="3" stroke-linecap="round"/>
                                <circle cx="60" cy="13" r="5" fill="#38bdf8" filter="drop-shadow(0 0 4px #38bdf8)"/>
                                <!-- Robot Head -->
                                <rect x="30" y="26" width="60" height="46" rx="22" fill="url(#botBody)" stroke="#f1f5f9" stroke-width="2"/>
                                <!-- Face Screen -->
                                <rect x="36" y="32" width="48" height="34" rx="14" fill="#0f172a"/>
                                <!-- Glowing Cyan Eyes -->
                                <circle cx="48" cy="48" r="5.5" fill="#38bdf8" filter="drop-shadow(0 0 5px #38bdf8)"/>
                                <circle cx="72" cy="48" r="5.5" fill="#38bdf8" filter="drop-shadow(0 0 5px #38bdf8)"/>
                                <circle cx="50" cy="46" r="1.5" fill="#ffffff"/>
                                <circle cx="74" cy="46" r="1.5" fill="#ffffff"/>
                                <!-- Smile -->
                                <path d="M 54 58 Q 60 62, 66 58" fill="none" stroke="#38bdf8" stroke-width="2" stroke-linecap="round"/>
                                <!-- Ear Pods -->
                                <rect x="23" y="38" width="8" height="22" rx="4" fill="#64748b"/>
                                <rect x="89" y="38" width="8" height="22" rx="4" fill="#64748b"/>
                                <!-- Body -->
                                <path d="M 40 76 Q 60 72, 80 76 L 84 105 Q 60 110, 36 105 Z" fill="url(#botBody)"/>
                                <!-- Chest Core -->
                                <circle cx="60" cy="90" r="6" fill="#38bdf8" filter="drop-shadow(0 0 6px #38bdf8)"/>
                                <!-- Waving Arm Left -->
                                <path d="M 38 82 Q 22 75, 18 64" fill="none" stroke="url(#botBody)" stroke-width="7" stroke-linecap="round"/>
                                <circle cx="17" cy="62" r="5" fill="#ffffff"/>
                                <!-- Arm Right -->
                                <path d="M 82 82 Q 95 90, 98 100" fill="none" stroke="url(#botBody)" stroke-width="7" stroke-linecap="round"/>
                            </svg>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>

    <!-- Live Scripts: Number Counters, Time-of-Day Greeting & Clock -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // 1. DYNAMIC TIME-OF-DAY GREETING & CLOCK
            function updateGreetingAndClock() {
                const now = new Date();
                const hour = now.getHours();
                let greeting = 'Good Morning';
                let emoji = '☀️';
                let badge = 'Morning Shift';
                let subtitle = "Rise & shine! Here's what's happening with your tenders, bills & treasury today.";
                let bgGradient = 'linear-gradient(135deg, #f59e0b 0%, #f97316 50%, #f43f5e 100%)';

                if (hour >= 5 && hour < 12) {
                    greeting = 'Good Morning';
                    emoji = '☀️';
                    badge = 'Morning Shift';
                    subtitle = "Rise & shine! Here's what's happening with your tenders, bills & treasury today.";
                    bgGradient = 'linear-gradient(135deg, #f59e0b 0%, #f97316 50%, #f43f5e 100%)';
                } else if (hour >= 12 && hour < 17) {
                    greeting = 'Good Afternoon';
                    emoji = '🌤️';
                    badge = 'Afternoon Session';
                    subtitle = "Mid-day pulse check on departmental procurement, budget releases & clearing vouchers.";
                    bgGradient = 'linear-gradient(135deg, #38bdf8 0%, #0056d2 50%, #0b327b 100%)';
                } else if (hour >= 17 && hour < 21) {
                    greeting = 'Good Evening';
                    emoji = '🌆';
                    badge = 'Evening Review';
                    subtitle = "Wrapping up today's fiscal clearance, pending tender sanctions & audit logs.";
                    bgGradient = 'linear-gradient(135deg, #fa7a1f 0%, #ea580c 50%, #cd3805 100%)';
                } else {
                    greeting = 'Good Night';
                    emoji = '🌙';
                    badge = 'Night Monitoring';
                    subtitle = "Late-night automated settlement pipeline & treasury balance monitoring.";
                    bgGradient = 'linear-gradient(135deg, #07255e 0%, #0056d2 50%, #0284c7 100%)';
                }

                const userName = @json($userName);
                const textEl = document.getElementById('dynamicGreetingText');
                if (textEl) textEl.textContent = `${greeting}, ${userName}`;

                const iconEl = document.getElementById('dynamicGreetingIcon');
                if (iconEl) iconEl.textContent = emoji;

                const iconWrap = document.getElementById('dynamicGreetingIconWrap');
                if (iconWrap) iconWrap.style.background = bgGradient;

                const badgeLabel = document.getElementById('dynamicBadgeLabel');
                if (badgeLabel) badgeLabel.textContent = badge;

                const subText = document.getElementById('dynamicSubtitleText');
                if (subText) subText.textContent = subtitle;

                const dateEl = document.getElementById('dynamicLiveDate');
                if (dateEl) {
                    const options = { weekday: 'long', day: '2-digit', month: 'short', year: 'numeric' };
                    dateEl.textContent = now.toLocaleDateString('en-GB', options);
                }

                const clockEl = document.getElementById('dynamicLiveClock');
                if (clockEl) {
                    clockEl.textContent = now.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', hour12: true });
                }
            }

            updateGreetingAndClock();
            setInterval(updateGreetingAndClock, 30000); // Live sync every 30 seconds

            // 2. LIVE ROLLING NUMBER COUNTERS
            const counters = document.querySelectorAll('[data-counter-target]');
            counters.forEach(counter => {
                const target = parseFloat(counter.getAttribute('data-counter-target')) || 0;
                const prefix = counter.getAttribute('data-counter-prefix') || '';
                const suffix = counter.getAttribute('data-counter-suffix') || '';
                const isFloat = counter.hasAttribute('data-counter-float');
                const hasDecimals = counter.hasAttribute('data-counter-decimals');
                const decimals = hasDecimals ? parseInt(counter.getAttribute('data-counter-decimals')) : (isFloat ? 1 : 0);
                const duration = 1600;
                const startTime = performance.now();

                function formatNum(val) {
                    if (decimals > 0) {
                        const parts = val.toFixed(decimals).split('.');
                        parts[0] = parseInt(parts[0]).toLocaleString('en-US');
                        return parts.join('.');
                    }
                    return Math.round(val).toLocaleString('en-US');
                }

                function updateCount(currentTime) {
                    const elapsed = currentTime - startTime;
                    const progress = Math.min(elapsed / duration, 1);
                    const easeProgress = 1 - Math.pow(1 - progress, 3);
                    const currentVal = easeProgress * target;

                    counter.textContent = prefix + formatNum(currentVal) + suffix;

                    if (progress < 1) {
                        requestAnimationFrame(updateCount);
                    } else {
                        counter.textContent = prefix + formatNum(target) + suffix;
                    }
                }
                requestAnimationFrame(updateCount);
            });
        });
    </script>
</x-app-layout>
