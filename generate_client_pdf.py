# -*- coding: utf-8 -*-
import os, base64, subprocess

asset_dir = r'C:\Users\Abdullah\tendermanagement\pdf_assets'

def get_b64(fname):
    p = os.path.join(asset_dir, fname)
    if os.path.exists(p):
        with open(p, 'rb') as fp:
            ext = 'jpeg' if p.lower().endswith(('.jpg', '.jpeg')) else 'png'
            return f'data:image/{ext};base64,' + base64.b64encode(fp.read()).decode('utf-8')
    return ''

img_create = get_b64('create_tender_reference.png')
img_tags = get_b64('tender_detail_tags_reference.png')
img_cards = get_b64('dashboard_cards_reference.png')
img_table = get_b64('dashboard_table_reference.png')

html_content = f'''<!DOCTYPE html>
<html lang="ur">
<head>
<meta charset="UTF-8">
<title>Client Feedback &amp; Proposed Updates — Tender Management System</title>
<style>
  @page {{
    size: A4 portrait;
    margin: 0;
  }}
  * {{
    box-sizing: border-box;
    margin: 0;
    padding: 0;
    -webkit-print-color-adjust: exact !important;
    print-color-adjust: exact !important;
  }}
  body {{
    font-family: 'Segoe UI', -apple-system, BlinkMacSystemFont, Roboto, 'Helvetica Neue', Arial, sans-serif;
    color: #1e293b;
    background: #ffffff;
    font-size: 13.5px;
    line-height: 1.55;
  }}
  .page {{
    width: 210mm;
    height: 297mm;
    max-height: 297mm;
    padding: 13mm 17mm 13mm 17mm;
    page-break-after: always;
    break-after: page;
    position: relative;
    background: #ffffff;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    overflow: hidden;
  }}
  
  /* Running Header & Footer */
  .header-bar {{
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1.5px solid #e2e8f0;
    padding-bottom: 6px;
    margin-bottom: 10px;
  }}
  .header-bar .logo-text {{
    font-size: 11px;
    font-weight: 800;
    color: #0369a1;
    letter-spacing: 0.5px;
    text-transform: uppercase;
  }}
  .header-bar .doc-tag {{
    font-size: 10px;
    font-weight: 700;
    color: #64748b;
    background: #f1f5f9;
    padding: 2.5px 8px;
    border-radius: 6px;
    border: 1px solid #e2e8f0;
  }}
  
  .footer-bar {{
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-top: 1.5px solid #e2e8f0;
    padding-top: 6px;
    margin-top: 8px;
    font-size: 10.5px;
    color: #64748b;
    font-weight: 600;
  }}
  .footer-bar .page-badge {{
    background: #0284c7;
    color: #ffffff;
    padding: 2px 9px;
    border-radius: 12px;
    font-size: 10px;
    font-weight: 800;
  }}

  /* Page Content Container */
  .page-body {{
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: flex-start;
  }}

  /* Titles & Headings */
  .section-tag {{
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 10.5px;
    font-weight: 800;
    text-transform: uppercase;
    color: #0284c7;
    background: #e0f2fe;
    padding: 3px 10px;
    border-radius: 6px;
    border: 1px solid #bae6fd;
    margin-bottom: 5px;
    width: fit-content;
  }}
  .page-title {{
    font-size: 23px;
    font-weight: 800;
    color: #0f172a;
    line-height: 1.25;
    margin-bottom: 3px;
  }}
  .page-subtitle {{
    font-size: 13px;
    font-weight: 700;
    color: #0369a1;
    margin-bottom: 10px;
    display: flex;
    align-items: center;
    gap: 8px;
  }}
  .page-subtitle span.target {{
    background: #f8fafc;
    color: #334155;
    border: 1px solid #cbd5e1;
    padding: 2px 8px;
    border-radius: 6px;
    font-size: 11px;
    font-weight: 800;
  }}

  /* Overview Card */
  .intro-card {{
    background: #f8fafc;
    border-left: 4px solid #0284c7;
    border-radius: 0 8px 8px 0;
    padding: 9px 13px;
    margin-bottom: 11px;
    border-top: 1px solid #e2e8f0;
    border-right: 1px solid #e2e8f0;
    border-bottom: 1px solid #e2e8f0;
  }}
  .intro-card p {{
    font-size: 13.5px;
    color: #334155;
    font-weight: 600;
    line-height: 1.48;
  }}

  /* Points Grid & Items */
  .points-list {{
    display: flex;
    flex-direction: column;
    gap: 7px;
    margin-bottom: 10px;
  }}
  .point-item {{
    display: flex;
    align-items: flex-start;
    gap: 9px;
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 8px 11px;
    box-shadow: 0 1px 2px rgba(0,0,0,0.02);
  }}
  .point-bullet {{
    width: 20px;
    height: 20px;
    border-radius: 50%;
    background: #e0f2fe;
    color: #0284c7;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 11px;
    font-weight: 900;
    flex-shrink: 0;
    margin-top: 1px;
  }}
  .point-content {{
    font-size: 13.5px;
    color: #1e293b;
    font-weight: 600;
    line-height: 1.45;
  }}
  .point-content strong {{
    color: #0f172a;
    font-weight: 800;
  }}

  /* Image Container */
  .screenshot-frame {{
    background: #f8fafc;
    border: 1.5px solid #cbd5e1;
    border-radius: 10px;
    padding: 6px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.03);
    margin-top: 4px;
    text-align: center;
  }}
  .screenshot-frame img {{
    max-width: 100%;
    height: auto;
    border-radius: 6px;
    display: block;
    margin: 0 auto;
    border: 1px solid #e2e8f0;
  }}
  .screenshot-caption {{
    font-size: 10.5px;
    font-weight: 700;
    color: #64748b;
    margin-top: 5px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 5px;
  }}

  /* 2-Column Cards Grid */
  .grid-2 {{
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
    margin-bottom: 10px;
  }}
  .card-box {{
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 9px 12px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.02);
  }}
  .card-box-header {{
    font-size: 11.5px;
    font-weight: 800;
    text-transform: uppercase;
    color: #0369a1;
    margin-bottom: 6px;
    display: flex;
    align-items: center;
    gap: 6px;
    border-bottom: 1px solid #f1f5f9;
    padding-bottom: 4px;
  }}
  .card-box-item {{
    font-size: 12.5px;
    color: #334155;
    font-weight: 600;
    padding: 3px 0;
    display: flex;
    align-items: center;
    gap: 6px;
  }}
  .check-icon {{
    color: #059669;
    font-weight: 900;
    font-size: 13px;
  }}

  /* Cover Page Styles */
  .cover-accent-bar {{
    height: 6px;
    background: linear-gradient(90deg, #0284c7, #38bdf8, #0369a1);
    border-radius: 4px;
    margin-bottom: 20px;
  }}
  .cover-header-badge {{
    display: inline-block;
    background: #e0f2fe;
    color: #0369a1;
    font-size: 11px;
    font-weight: 800;
    padding: 4px 12px;
    border-radius: 8px;
    border: 1px solid #bae6fd;
    text-transform: uppercase;
    letter-spacing: 0.8px;
    margin-bottom: 14px;
  }}
  .cover-title {{
    font-size: 32px;
    font-weight: 900;
    color: #0f172a;
    line-height: 1.2;
    margin-bottom: 6px;
    letter-spacing: -0.5px;
  }}
  .cover-subtitle {{
    font-size: 20px;
    font-weight: 700;
    color: #0284c7;
    margin-bottom: 14px;
  }}
  .cover-status-box {{
    background: #fffbeb;
    border: 1.5px solid #fde68a;
    border-radius: 10px;
    padding: 12px 16px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin: 14px 0 20px 0;
  }}
  .cover-status-badge {{
    background: #d97706;
    color: #ffffff;
    font-size: 11.5px;
    font-weight: 900;
    padding: 4px 12px;
    border-radius: 6px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
  }}
  .meta-table {{
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 20px;
    background: #ffffff;
    border-radius: 8px;
    overflow: hidden;
    border: 1px solid #e2e8f0;
  }}
  .meta-table td {{
    padding: 8px 12px;
    font-size: 13px;
    border-bottom: 1px solid #f1f5f9;
  }}
  .meta-table tr:last-child td {{
    border-bottom: none;
  }}
  .meta-label {{
    font-weight: 700;
    color: #64748b;
    width: 32%;
    background: #f8fafc;
    border-right: 1px solid #e2e8f0;
  }}
  .meta-value {{
    font-weight: 700;
    color: #0f172a;
  }}
  
  .toc-box {{
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    padding: 14px 16px;
  }}
  .toc-title {{
    font-size: 12px;
    font-weight: 800;
    color: #0f172a;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 10px;
    display: flex;
    align-items: center;
    gap: 8px;
  }}
  .toc-grid {{
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 8px 14px;
  }}
  .toc-item {{
    font-size: 12.5px;
    font-weight: 700;
    color: #334155;
    display: flex;
    align-items: center;
    gap: 8px;
  }}
  .toc-num {{
    background: #0284c7;
    color: #ffffff;
    width: 22px;
    height: 22px;
    border-radius: 5px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 11px;
    font-weight: 900;
  }}

  /* Sign-Off Sheet on Final Page */
  .sign-off-box {{
    background: #ffffff;
    border: 1.5px solid #0284c7;
    border-radius: 10px;
    padding: 14px 18px;
    margin-top: 12px;
    box-shadow: 0 3px 8px rgba(2, 132, 199, 0.05);
  }}
  .sign-grid {{
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 14px;
    margin-top: 10px;
  }}
  .sign-field {{
    border-bottom: 1.5px dashed #94a3b8;
    padding-bottom: 5px;
    margin-top: 12px;
  }}
  .sign-label {{
    font-size: 10.5px;
    font-weight: 800;
    color: #475569;
    text-transform: uppercase;
  }}
</style>
</head>
<body>

<!-- ======================================================== -->
<!-- PAGE 1: COVER PAGE                                       -->
<!-- ======================================================== -->
<div class="page">
  <div>
    <div class="cover-accent-bar"></div>
    <div class="cover-header-badge">Client Review &amp; Approval Document</div>
    <h1 class="cover-title">Client Feedback &amp; Proposed Updates</h1>
    <h2 class="cover-subtitle">Tender Management System</h2>
    
    <div class="intro-card" style="margin-top: 12px; border-left-color: #0284c7;">
      <p style="font-size: 14.5px; color: #1e293b; font-weight: 600;">
        <strong>Document Purpose:</strong> Is document mein Tender Management System ke different pages par required changes ko simple Roman Urdu mein explain kiya gaya hai, taake tamam updates asani se samajh aa saken aur client review ke baad development work start kiya ja sake.
      </p>
    </div>

    <div class="cover-status-box">
      <div>
        <div style="font-size: 11.5px; font-weight: 700; color: #92400e; text-transform: uppercase;">Approval Phase</div>
        <div style="font-size: 15px; font-weight: 900; color: #78350f; margin-top: 2px;">Client Review &amp; Formal Authorization</div>
      </div>
      <div class="cover-status-badge">Status: Awaiting Client Approval</div>
    </div>

    <table class="meta-table">
      <tr>
        <td class="meta-label">Application / Project</td>
        <td class="meta-value">Tender Management System (Web Application)</td>
      </tr>
      <tr>
        <td class="meta-label">Document Type</td>
        <td class="meta-value">Client Feedback &amp; Scope of Proposed Updates</td>
      </tr>
      <tr>
        <td class="meta-label">Language Format</td>
        <td class="meta-value">Simple &amp; Clear Roman Urdu (Client-Friendly)</td>
      </tr>
      <tr>
        <td class="meta-label">Date of Issue</td>
        <td class="meta-value">26 November 2026</td>
      </tr>
      <tr>
        <td class="meta-label">Current Workflow Stage</td>
        <td class="meta-value" style="color: #0284c7;">Ready for Development Work Post-Approval</td>
      </tr>
    </table>

    <div class="toc-box">
      <div class="toc-title">
        <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/></svg>
        <span>Table of Contents — Covered Modules</span>
      </div>
      <div class="toc-grid">
        <div class="toc-item"><span class="toc-num">01</span> Create New Tender Page</div>
        <div class="toc-item"><span class="toc-num">02</span> Tender Detail Page</div>
        <div class="toc-item"><span class="toc-num">03</span> Update Tender Page</div>
        <div class="toc-item"><span class="toc-num">04</span> Tender Management Dashboard</div>
        <div class="toc-item"><span class="toc-num">05</span> View Tender – Fee &amp; Document Details</div>
        <div class="toc-item"><span class="toc-num">06</span> Bidding Preparation Form</div>
      </div>
    </div>
  </div>

  <div class="footer-bar">
    <div>Tender Management System • Client Feedback &amp; Proposed Updates</div>
    <div class="page-badge">Page 1 of 8</div>
  </div>
</div>

<!-- ======================================================== -->
<!-- PAGE 2: 01. CREATE NEW TENDER PAGE                      -->
<!-- ======================================================== -->
<div class="page">
  <div>
    <div class="header-bar">
      <div class="logo-text">Tender Management System • Module 01</div>
      <div class="doc-tag">Form Re-Structuring</div>
    </div>

    <div class="section-tag">Module 01 • Create Workflow</div>
    <h2 class="page-title">01. Create New Tender Page</h2>
    <div class="page-subtitle">
      <span>Page Reference:</span>
      <span class="target">Create New Tender</span>
    </div>

    <div class="intro-card">
      <p>Is page ke form ko zyada <strong>clean, well-structured aur organized</strong> banana hai taake tender entry ka amal asan aur professional ban sakay.</p>
    </div>

    <div class="points-list">
      <div class="point-item">
        <div class="point-bullet">✓</div>
        <div class="point-content"><strong>4 Clear Sections:</strong> Form ko distinct sections mein divide karna hai: <strong>General Information</strong>, <strong>Schedule &amp; Timeline</strong>, <strong>Eligibility &amp; Documentation</strong>, aur <strong>Bidding Details</strong>.</div>
      </div>
      <div class="point-item">
        <div class="point-bullet">✓</div>
        <div class="point-content"><strong>Titles &amp; Icons:</strong> Har section ka apna clear title aur relevant intuitive icon show hoga.</div>
      </div>
      <div class="point-item">
        <div class="point-bullet">✓</div>
        <div class="point-content"><strong>Same Color Theme:</strong> Form ka existing color theme aur light background visual identity ke sath bilkul same rahega.</div>
      </div>
      <div class="point-item">
        <div class="point-bullet">✓</div>
        <div class="point-content"><strong>Single Stage 1E:</strong> Single Stage One Envelope mein Bid Opening ki sirf <strong>ek single date</strong> show hogi.</div>
      </div>
      <div class="point-item">
        <div class="point-bullet">✓</div>
        <div class="point-content"><strong>Single Stage 2E:</strong> Single Stage Two Envelope procedure mein <strong>Technical</strong> aur <strong>Financial</strong> opening ki alag alag dates select karne ki facility hogi.</div>
      </div>
      <div class="point-item">
        <div class="point-bullet">✓</div>
        <div class="point-content"><strong>Tender Document Upload:</strong> PDF, DOC, aur ZIP files upload karne ki dedicated clean facility di jayegi.</div>
      </div>
      <div class="point-item">
        <div class="point-bullet">✓</div>
        <div class="point-content"><strong>Instant File Preview:</strong> Upload ki hui file ko submit karne se pehle direct click karke preview kiya ja sakega.</div>
      </div>
      <div class="point-item">
        <div class="point-bullet">✓</div>
        <div class="point-content"><strong>Save as Draft:</strong> "Create Tender" button ke sath <strong>"Save as Draft"</strong> ka option bhi available hoga taake incomplete tenders mehfooz kiye ja saken.</div>
      </div>
    </div>

    <div class="screenshot-frame">
      <img src="{img_create}" style="max-height: 70mm;" alt="Create New Tender Reference Layout">
      <div class="screenshot-caption">
        <svg width="12" height="12" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/></svg>
        <span>Reference Design: 4-Section Organized Form Layout with Icons, Single/Two Envelope Dates &amp; Draft Option</span>
      </div>
    </div>
  </div>

  <div class="footer-bar">
    <div>Tender Management System • Client Feedback &amp; Proposed Updates</div>
    <div class="page-badge">Page 2 of 8</div>
  </div>
</div>

<!-- ======================================================== -->
<!-- PAGE 3: 02. TENDER DETAIL PAGE                          -->
<!-- ======================================================== -->
<div class="page">
  <div>
    <div class="header-bar">
      <div class="logo-text">Tender Management System • Module 02</div>
      <div class="doc-tag">Display Optimization</div>
    </div>

    <div class="section-tag">Module 02 • View Tender</div>
    <h2 class="page-title">02. Tender Detail Page</h2>
    <div class="page-subtitle">
      <span>Page Reference:</span>
      <span class="target">View Tender</span>
    </div>

    <div class="intro-card">
      <p>Tender ki details display hone ka tareeqa behtar aur clean banana hai, taake unnecessary tags khatam hon aur sirf authentic official information nazar aaye.</p>
    </div>

    <div class="points-list">
      <div class="point-item">
        <div class="point-bullet">✓</div>
        <div class="point-content"><strong>Department Short Tag Removal:</strong> Department ke naam ke neeche <strong>CWD</strong>, <strong>ITD</strong>, <strong>HMS</strong> ya koi doosra unnecessary short code tag bilkul show nahi hona chahiye.</div>
      </div>
      <div class="point-item">
        <div class="point-bullet">✓</div>
        <div class="point-content"><strong>Tender Tag Removal:</strong> Tender number ke sath extra <strong>"Tender"</strong> tag / badge bhi nahi aana chahiye.</div>
      </div>
      <div class="point-item">
        <div class="point-bullet">✓</div>
        <div class="point-content"><strong>Clean Header Presentation:</strong> Sirf <strong>Department ka mukammal naam</strong> aur <strong>Tender Reference Number</strong> clearly show hona chahiye.</div>
      </div>
      <div class="point-item">
        <div class="point-bullet">✓</div>
        <div class="point-content"><strong>Procedure-Specific Opening Dates:</strong> 
          Single Stage 1E mein sirf <strong>ek opening date</strong> aur Single Stage 2E mein <strong>Technical Opening</strong> aur <strong>Financial Opening</strong> dono dates alag alag display honi chahiye.
        </div>
      </div>
    </div>

    <!-- Before vs After Box -->
    <div class="grid-2" style="margin-top: 10px;">
      <div class="card-box" style="border-left: 3px solid #ef4444; background: #fff5f5;">
        <div class="card-box-header" style="color: #b91c1c;">
          <span style="font-size: 13px;">✗</span> Current Display (Needs Removal)
        </div>
        <div style="font-size: 12.5px; line-height: 1.5; color: #7f1d1d;">
          • Department ke neeche chota code tag: <code>CWD</code> / <code>HMS</code> / <code>ITD</code><br>
          • Tender reference no ke neeche redundant <code>TENDER</code> label.<br>
          • Unnecessary badges jo layout ko cluttered banate hain.
        </div>
      </div>
      <div class="card-box" style="border-left: 3px solid #10b981; background: #f0fdf4;">
        <div class="card-box-header" style="color: #047857;">
          <span style="font-size: 13px;">✓</span> Proposed Clean Display (Approved)
        </div>
        <div style="font-size: 12.5px; line-height: 1.5; color: #064e3b;">
          • Sirf authentic Department Name: <strong>Civil Works &amp; Infrastructure Development</strong><br>
          • Clean official reference: <strong>TND-2026-001</strong><br>
          • Single Stage 1E: 1 Opening Date | Single Stage 2E: Tech + Fin Dates.
        </div>
      </div>
    </div>

    <div class="screenshot-frame" style="margin-top: 12px;">
      <img src="{img_tags}" style="max-height: 75mm;" alt="Reference of tags to be removed">
      <div class="screenshot-caption">
        <span>Reference: Redundant tags (CWD, ITD, TENDER badge) highlighted for complete removal from View &amp; Listing</span>
      </div>
    </div>
  </div>

  <div class="footer-bar">
    <div>Tender Management System • Client Feedback &amp; Proposed Updates</div>
    <div class="page-badge">Page 3 of 8</div>
  </div>
</div>

<!-- ======================================================== -->
<!-- PAGE 4: 03. UPDATE TENDER PAGE                          -->
<!-- ======================================================== -->
<div class="page">
  <div>
    <div class="header-bar">
      <div class="logo-text">Tender Management System • Module 03</div>
      <div class="doc-tag">Edit Form Consistency</div>
    </div>

    <div class="section-tag">Module 03 • Update Workflow</div>
    <h2 class="page-title">03. Update Tender Page</h2>
    <div class="page-subtitle">
      <span>Page Reference:</span>
      <span class="target">Update Tender / Edit Tender</span>
    </div>

    <div class="intro-card">
      <p>Jab kisi existing tender ko update ya edit kiya jaye, to user ko bilkul <strong>Create New Tender jaisa seamless aur consistent experience</strong> milna chahiye.</p>
    </div>

    <div class="points-list">
      <div class="point-item">
        <div class="point-bullet">✓</div>
        <div class="point-content"><strong>Identical 4-Section Design:</strong> Update form ka design bilkul Create New Tender jaisa hoga (General Information, Schedule &amp; Timeline, Eligibility &amp; Documentation, Bidding Details).</div>
      </div>
      <div class="point-item">
        <div class="point-bullet">✓</div>
        <div class="point-content"><strong>Consistent Input Fields:</strong> Tamam fields, dropdowns, mode selectors, date pickers aur upload facilities exactly same format mein honge.</div>
      </div>
      <div class="point-item">
        <div class="point-bullet">✓</div>
        <div class="point-content"><strong>Pre-filled Existing Information:</strong> Pehle se create kiye gaye tender ki tamam information form fields mein pehle se automatically filled show hogi.</div>
      </div>
      <div class="point-item">
        <div class="point-bullet">✓</div>
        <div class="point-content"><strong>Easy Editing &amp; Updating:</strong> User kisi bhi information ko asani se edit, replace ya update kar sakega, aur existing uploaded document ka direct link aur replace option mojood hoga.</div>
      </div>
    </div>

    <!-- Parity Grid -->
    <div class="card-box" style="margin-top: 10px; border-left: 3px solid #0284c7;">
      <div class="card-box-header">Create Tender vs Update Tender — 100% Feature Parity</div>
      <table style="width: 100%; font-size: 12.5px; border-collapse: collapse; margin-top: 4px;">
        <thead>
          <tr style="background: #f8fafc; border-bottom: 1.5px solid #e2e8f0; text-align: left;">
            <th style="padding: 7px 10px;">Feature / Field</th>
            <th style="padding: 7px 10px;">Create New Tender</th>
            <th style="padding: 7px 10px;">Update Existing Tender</th>
          </tr>
        </thead>
        <tbody>
          <tr style="border-bottom: 1px solid #f1f5f9;">
            <td style="padding: 7px 10px; font-weight: 700;">Section Hierarchy</td>
            <td style="padding: 7px 10px;">4 Clean Structured Sections</td>
            <td style="padding: 7px 10px; color: #0284c7; font-weight: 700;">Exact Same 4 Sections</td>
          </tr>
          <tr style="border-bottom: 1px solid #f1f5f9;">
            <td style="padding: 7px 10px; font-weight: 700;">Data State</td>
            <td style="padding: 7px 10px;">Blank Entry Form</td>
            <td style="padding: 7px 10px; color: #0284c7; font-weight: 700;">Pre-Filled with Current DB Values</td>
          </tr>
          <tr style="border-bottom: 1px solid #f1f5f9;">
            <td style="padding: 7px 10px; font-weight: 700;">1E / 2E Dates Toggle</td>
            <td style="padding: 7px 10px;">Dynamic Single / Dual Dates</td>
            <td style="padding: 7px 10px; color: #0284c7; font-weight: 700;">Dynamic Single / Dual Dates</td>
          </tr>
          <tr style="border-bottom: 1px solid #f1f5f9;">
            <td style="padding: 7px 10px; font-weight: 700;">Tender Dossier Attachment</td>
            <td style="padding: 7px 10px;">Upload PDF / DOC / ZIP</td>
            <td style="padding: 7px 10px; color: #0284c7; font-weight: 700;">Preview Current File + Replace Option</td>
          </tr>
          <tr>
            <td style="padding: 7px 10px; font-weight: 700;">Action Buttons</td>
            <td style="padding: 7px 10px;">Save as Draft | Create Tender</td>
            <td style="padding: 7px 10px; color: #0284c7; font-weight: 700;">Update Changes | Cancel</td>
          </tr>
        </tbody>
      </table>
    </div>

    <div class="intro-card" style="margin-top: 14px; background: #f0fdf4; border-left-color: #10b981;">
      <p style="color: #065f46;">
        <strong>Implementation Benefit:</strong> Is consistency ki wajah se operators aur procurement staff ko tender edit karte waqt koi uljhan nahi hogi aur system ka standard flow barqarar rahega.
      </p>
    </div>
  </div>

  <div class="footer-bar">
    <div>Tender Management System • Client Feedback &amp; Proposed Updates</div>
    <div class="page-badge">Page 4 of 8</div>
  </div>
</div>

<!-- ======================================================== -->
<!-- PAGE 5: 04. TENDER MANAGEMENT DASHBOARD                 -->
<!-- ======================================================== -->
<div class="page">
  <div>
    <div class="header-bar">
      <div class="logo-text">Tender Management System • Module 04</div>
      <div class="doc-tag">Dashboard &amp; Table Alignment</div>
    </div>

    <div class="section-tag">Module 04 • Dashboard &amp; Analytics</div>
    <h2 class="page-title">04. Tender Management Dashboard</h2>
    <div class="page-subtitle">
      <span>Page Reference:</span>
      <span class="target">Tender Management</span>
    </div>

    <div class="intro-card">
      <p>Dashboard ke cards aur table ko behtar, organized aur accurate reference design ke mutabiq display karna hai.</p>
    </div>

    <!-- 12 Cards Breakdown -->
    <div class="grid-2">
      <div class="card-box" style="border-top: 3px solid #0284c7;">
        <div class="card-box-header">Dashboard Status Cards (6 Cards)</div>
        <div class="card-box-item"><span class="check-icon">•</span> Total Tender</div>
        <div class="card-box-item"><span class="check-icon">•</span> Cancelled Tender</div>
        <div class="card-box-item"><span class="check-icon">•</span> Pending Tender</div>
        <div class="card-box-item"><span class="check-icon">•</span> Awarded Tender</div>
        <div class="card-box-item"><span class="check-icon">•</span> Not Awarded Tender</div>
        <div class="card-box-item"><span class="check-icon">•</span> Pending Award Docs</div>
      </div>

      <div class="card-box" style="border-top: 3px solid #10b981;">
        <div class="card-box-header">Bidding &amp; Security Financials (6 Cards)</div>
        <div class="card-box-item"><span class="check-icon">•</span> Awarded Value</div>
        <div class="card-box-item"><span class="check-icon">•</span> Tender Fees</div>
        <div class="card-box-item"><span class="check-icon">•</span> Bid Security Paid</div>
        <div class="card-box-item"><span class="check-icon">•</span> Bid Security Refunded</div>
        <div class="card-box-item"><span class="check-icon">•</span> Perf. Security Paid</div>
        <div class="card-box-item"><span class="check-icon">•</span> Perf. Security Released</div>
      </div>
    </div>
    <div style="font-size: 11.5px; color: #475569; font-style: italic; margin-top: -6px; margin-bottom: 8px;">
      * Tamam 12 cards ko provided reference design ke mutabiq colors, icons aur titles ke saath display kiya jayega.
    </div>

    <!-- Dashboard Options -->
    <div class="card-box" style="margin-bottom: 8px; background: #f8fafc;">
      <div class="card-box-header" style="color: #0f172a;">Dashboard Options (Sirf ye 4 options available honge)</div>
      <div class="grid-2" style="margin-bottom: 0;">
        <div class="card-box-item"><strong>1.</strong> All Categories</div>
        <div class="card-box-item"><strong>2.</strong> Filter by Fiscal Year</div>
        <div class="card-box-item"><strong>3.</strong> Export Status</div>
        <div class="card-box-item"><strong>4.</strong> Export Report</div>
      </div>
    </div>

    <!-- Table Alignment Rules -->
    <div class="card-box" style="margin-bottom: 6px;">
      <div class="card-box-header">Tender Management Table Alignment Rules</div>
      <div style="font-size: 12.5px; line-height: 1.5; color: #334155;">
        • <strong>Vendor Name:</strong> Department Name ke saath Vendor Name ki information bhi clearly show hogi.<br>
        • <strong>Center-Aligned Columns:</strong> <code>Status</code> aur <code>Received Award Documents</code> properly <strong>center-aligned</strong> honge.<br>
        • <strong>Left-Aligned Columns:</strong> Status aur Received Award Documents ke ilawa baqi تمام information properly <strong>left-aligned</strong> hogi.
      </div>
    </div>

    <div class="screenshot-frame">
      <img src="{img_cards}" style="max-height: 44mm;" alt="Dashboard Cards & Table Reference">
      <div class="screenshot-caption">
        <span>Reference: 12-Card Dashboard Financial &amp; Operational Analytics Layout</span>
      </div>
    </div>
  </div>

  <div class="footer-bar">
    <div>Tender Management System • Client Feedback &amp; Proposed Updates</div>
    <div class="page-badge">Page 5 of 8</div>
  </div>
</div>

<!-- ======================================================== -->
<!-- PAGE 6: 05. VIEW TENDER – FEE & DOCUMENT DETAILS        -->
<!-- ======================================================== -->
<div class="page">
  <div>
    <div class="header-bar">
      <div class="logo-text">Tender Management System • Module 05</div>
      <div class="doc-tag">Fee &amp; Document Simplification</div>
    </div>

    <div class="section-tag">Module 05 • Financial Accuracy</div>
    <h2 class="page-title">05. View Tender – Fee &amp; Document Details</h2>
    <div class="page-subtitle">
      <span>Page Reference:</span>
      <span class="target">View Tender</span>
    </div>

    <div class="intro-card">
      <p>Tender Fee ki payment information sahi aur accurate show honi chahiye, aur extra unneeded sections ko khatam karke document details ko direct aur clear banana hai.</p>
    </div>

    <div class="points-list">
      <div class="point-item">
        <div class="point-bullet">✓</div>
        <div class="point-content"><strong>Paid Status Display:</strong> Agar tender fee paid ho chuki hai to green badge ke sath <strong>"Paid"</strong> show hona chahiye.</div>
      </div>
      <div class="point-item">
        <div class="point-bullet">✓</div>
        <div class="point-content"><strong>Unpaid / Pending Status Display:</strong> Agar tender fee pending hai to clearly <strong>"Unpaid / Pending"</strong> show hona chahiye.</div>
      </div>
      <div class="point-item">
        <div class="point-bullet">✓</div>
        <div class="point-content"><strong>100% Dual-Screen Synchronization:</strong> Payment status Tender Detail view aur Tender Management Table dono jagah exactly <strong>same aur correct</strong> reflect hona chahiye.</div>
      </div>
      <div class="point-item">
        <div class="point-bullet">✓</div>
        <div class="point-content"><strong>Simplified Document Label:</strong> "Official Tender Dossier / RFP Document" ki lambi heading ki jagah sirf clean label <strong>"Tender Document"</strong> show hona chahiye.</div>
      </div>
      <div class="point-item">
        <div class="point-bullet">✓</div>
        <div class="point-content"><strong>Remove Bidding Intent Box:</strong> <strong>"Bidding Intent / Interested"</strong> ka extra unneeded section bilkul show nahi hona chahiye (completely removed).</div>
      </div>
    </div>

    <!-- Visual Comparison Cards -->
    <div class="grid-2" style="margin-top: 14px;">
      <div class="card-box" style="border-left: 3px solid #f59e0b; background: #fffdf5;">
        <div class="card-box-header" style="color: #b45309;">Pehle Ka Display (Old)</div>
        <div style="font-size: 12.5px; line-height: 1.6; color: #78350f;">
          • Title: <em>"Official Tender Dossier / RFP Document"</em><br>
          • Extra Box: <em>"Bidding Intent / Interested" (Interested / Not Interested radio)</em><br>
          • Unsynced fee payment labels in table vs detail.
        </div>
      </div>
      <div class="card-box" style="border-left: 3px solid #10b981; background: #f0fdf4;">
        <div class="card-box-header" style="color: #047857;">Proposed Approved Display (New)</div>
        <div style="font-size: 12.5px; line-height: 1.6; color: #064e3b;">
          • Title: <strong>"Tender Document"</strong> (Simple &amp; Clean)<br>
          • Extra Bidding Intent Box: <strong>Completely Removed ✗</strong><br>
          • Accurate synchronized status: <strong>Paid</strong> ya <strong>Unpaid / Pending</strong>.
        </div>
      </div>
    </div>

    <div class="intro-card" style="margin-top: 16px; background: #f0f9ff; border-left-color: #0284c7;">
      <p style="color: #0369a1;">
        <strong>Clarity Benefit:</strong> Is update ke baad tender view page par kisi qism ki confusion nahi rahegi aur management ko aik nazar mein pata chal sakega ke tender fee ada hui hai ya pending hai.
      </p>
    </div>
  </div>

  <div class="footer-bar">
    <div>Tender Management System • Client Feedback &amp; Proposed Updates</div>
    <div class="page-badge">Page 6 of 8</div>
  </div>
</div>

<!-- ======================================================== -->
<!-- PAGE 7: 06. BIDDING PREPARATION FORM                    -->
<!-- ======================================================== -->
<div class="page">
  <div>
    <div class="header-bar">
      <div class="logo-text">Tender Management System • Module 06</div>
      <div class="doc-tag">Bidding Prep Enhancements</div>
    </div>

    <div class="section-tag">Module 06 • Bidding Preparation</div>
    <h2 class="page-title">06. Bidding Preparation Form</h2>
    <div class="page-subtitle">
      <span>Page Reference:</span>
      <span class="target">Bidding Preparation</span>
    </div>

    <div class="intro-card">
      <p>Is form mein submission deadline, bank account titles aur receipt upload preview ke hawalay se important improvements required hain.</p>
    </div>

    <div class="points-list">
      <div class="point-item">
        <div class="point-bullet">✓</div>
        <div class="point-content"><strong>Submission Deadline Highlight:</strong> Submission Deadline ko different aur prominent background color ke saath highlight karna hai taake time aur tareekh foran identify ho.</div>
      </div>
      <div class="point-item">
        <div class="point-bullet">✓</div>
        <div class="point-content"><strong>Estimated Budget Placement:</strong> Estimated Budget ko form mein neeche display karna hai taake user calculations aur reference asani se dekh sakay.</div>
      </div>
      <div class="point-item">
        <div class="point-bullet">✓</div>
        <div class="point-content"><strong>Field Title Change 1:</strong> <em>"Paid From Which Bank Account?"</em> ki jagah official label <strong>"Issuing Bank Name"</strong> show hona chahiye.</div>
      </div>
      <div class="point-item">
        <div class="point-bullet">✓</div>
        <div class="point-content"><strong>Field Title Change 2:</strong> <em>"Upload Receipt Scan / Slip"</em> ki jagah clear title <strong>"Upload Tender Fee Receipt"</strong> show hona chahiye.</div>
      </div>
      <div class="point-item">
        <div class="point-bullet">✓</div>
        <div class="point-content"><strong>Uploaded File Display:</strong> Upload ki hui file ka naam aur visual attachment form mein clearly nazar aana chahiye.</div>
      </div>
      <div class="point-item">
        <div class="point-bullet">✓</div>
        <div class="point-content"><strong>Instant File Preview:</strong> Uploaded file par click karne se uska preview modal / popup mein asani se open hona chahiye.</div>
      </div>
      <div class="point-item">
        <div class="point-bullet">✓</div>
        <div class="point-content"><strong>Dual Preview Facility:</strong> Form ke <strong>dono file upload fields</strong> (Fee Receipt &amp; Bid Security CDR Slip) mein preview ki facility honi chahiye.</div>
      </div>
      <div class="point-item">
        <div class="point-bullet">✓</div>
        <div class="point-content"><strong>Field Title Change 3:</strong> <em>"Issued From Bank Account"</em> ki jagah bhi official label <strong>"Issuing Bank Name"</strong> show hona chahiye.</div>
      </div>
    </div>

    <div class="card-box" style="background: #f8fafc; border-top: 3px solid #0284c7; margin-top: 10px;">
      <div class="card-box-header">Summary of Field Label &amp; UI Transformations</div>
      <div class="grid-2" style="margin-bottom: 0;">
        <div style="font-size: 12px; line-height: 1.6; color: #475569;">
          • <s>Paid From Which Bank Account?</s> &rarr; <strong style="color: #0284c7;">Issuing Bank Name</strong><br>
          • <s>Upload Receipt Scan / Slip</s> &rarr; <strong style="color: #0284c7;">Upload Tender Fee Receipt</strong><br>
          • <s>Issued From Bank Account</s> &rarr; <strong style="color: #0284c7;">Issuing Bank Name</strong>
        </div>
        <div style="font-size: 12px; line-height: 1.6; color: #475569;">
          • <span style="background: #fef3c7; color: #92400e; padding: 2px 6px; border-radius: 4px; font-weight: 700;">Submission Deadline</span> (Highlighted Background)<br>
          • <strong>Estimated Budget:</strong> Form ke bottom section par relocate.<br>
          • <strong>Click-to-Preview:</strong> Both attachments (Fee + CDR).
        </div>
      </div>
    </div>
  </div>

  <div class="footer-bar">
    <div>Tender Management System • Client Feedback &amp; Proposed Updates</div>
    <div class="page-badge">Page 7 of 8</div>
  </div>
</div>

<!-- ======================================================== -->
<!-- PAGE 8: CLIENT APPROVAL & SIGN-OFF                      -->
<!-- ======================================================== -->
<div class="page">
  <div>
    <div class="header-bar">
      <div class="logo-text">Tender Management System • Final Review</div>
      <div class="doc-tag">Authorization &amp; Sign-Off</div>
    </div>

    <div class="section-tag" style="background: #fef3c7; color: #b45309; border-color: #fde68a;">Review &amp; Approval</div>
    <h2 class="page-title">Client Approval &amp; Authorization</h2>
    <div class="page-subtitle">
      <span>Status:</span>
      <span class="target" style="background: #fef3c7; color: #92400e; border-color: #fde68a; font-weight: 900;">Awaiting Client Approval</span>
    </div>

    <div class="intro-card" style="border-left-color: #d97706; background: #fffdf5;">
      <p style="font-size: 14.5px; color: #78350f; font-weight: 700; line-height: 1.55;">
        Baraye meherbani tamam proposed updates review kar lein.<br>
        Aapki approval ke baad in tamam changes par development work start kiya jayega.
      </p>
    </div>

    <!-- Development Next Steps -->
    <div class="card-box" style="margin-top: 10px; border-top: 3px solid #0284c7;">
      <div class="card-box-header">Development &amp; Execution Roadmap (Post-Approval)</div>
      <div class="grid-2" style="margin-bottom: 0;">
        <div class="card-box-item">
          <span class="check-icon">1.</span>
          <div><strong>Phase 1:</strong> Forms restructuring (Create, Update &amp; Bidding Prep).</div>
        </div>
        <div class="card-box-item">
          <span class="check-icon">2.</span>
          <div><strong>Phase 2:</strong> Detail page cleanup &amp; tag removals (CWD &amp; Tender tags).</div>
        </div>
        <div class="card-box-item">
          <span class="check-icon">3.</span>
          <div><strong>Phase 3:</strong> 12-Card Dashboard &amp; table center/left alignment.</div>
        </div>
        <div class="card-box-item">
          <span class="check-icon">4.</span>
          <div><strong>Phase 4:</strong> Final QA, verification &amp; staging walkthrough.</div>
        </div>
      </div>
    </div>

    <!-- Formal Sign-Off Container -->
    <div class="sign-off-box">
      <div style="font-size: 13.5px; font-weight: 800; color: #0f172a; border-bottom: 1px solid #e2e8f0; padding-bottom: 5px;">
        Formal Client Sign-Off &amp; Approval Confirmation
      </div>

      <div style="margin-top: 8px; font-size: 12px; font-weight: 700; color: #334155;">
        Decision: &nbsp;&nbsp;
        <span style="display: inline-block; border: 1.5px solid #64748b; width: 13px; height: 13px; vertical-align: middle; margin-right: 4px; border-radius: 3px;"></span> Approved as Proposed (Ready for Development) &nbsp;&nbsp;&nbsp;&nbsp;
        <span style="display: inline-block; border: 1.5px solid #64748b; width: 13px; height: 13px; vertical-align: middle; margin-right: 4px; border-radius: 3px;"></span> Approved with Remarks
      </div>

      <div class="sign-grid">
        <div>
          <div class="sign-field">
            <div class="sign-label">Client Representative Name:</div>
          </div>
          <div class="sign-field">
            <div class="sign-label">Designation / Title:</div>
          </div>
          <div class="sign-field">
            <div class="sign-label">Company / Organization:</div>
          </div>
        </div>
        <div>
          <div class="sign-field">
            <div class="sign-label">Official Signature:</div>
          </div>
          <div class="sign-field">
            <div class="sign-label">Official Stamp:</div>
          </div>
          <div class="sign-field">
            <div class="sign-label">Date:</div>
          </div>
        </div>
      </div>

      <div style="margin-top: 12px;">
        <div class="sign-label" style="margin-bottom: 3px;">Client Comments / Specific Instructions:</div>
        <div style="border-bottom: 1px dashed #cbd5e1; height: 20px;"></div>
        <div style="border-bottom: 1px dashed #cbd5e1; height: 20px;"></div>
      </div>
    </div>
  </div>

  <div class="footer-bar">
    <div>Tender Management System • Client Feedback &amp; Proposed Updates</div>
    <div class="page-badge">Page 8 of 8</div>
  </div>
</div>

</body>
</html>
'''

html_path = r'C:\Users\Abdullah\tendermanagement\client_feedback_document.html'
with open(html_path, 'w', encoding='utf-8') as f:
    f.write(html_content)

print('HTML written to:', html_path)

pdf_path = r'C:\Users\Abdullah\tendermanagement\Client_Feedback_and_Proposed_Updates.pdf'
edge_path = r'C:\Program Files (x86)\Microsoft\Edge\Application\msedge.exe'

cmd = [
    edge_path,
    '--headless=new',
    '--disable-gpu',
    '--no-pdf-header-footer',
    f'--print-to-pdf={pdf_path}',
    html_path
]

print('Running Edge to generate PDF...')
res = subprocess.run(cmd, capture_output=True, text=True)
print('Edge returncode:', res.returncode)
if os.path.exists(pdf_path):
    print('PDF generated successfully! Size:', os.path.getsize(pdf_path), 'bytes')
else:
    print('PDF generation failed.')
