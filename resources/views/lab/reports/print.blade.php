<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Semen Analysis Report — {{ $report->sample_code }}</title>
  <style>
    *{box-sizing:border-box;margin:0;padding:0;}
    body{font-family:'Times New Roman',Times,serif;font-size:11pt;color:#111;background:#fff;padding:10mm 12mm;}
    .page{max-width:190mm;margin:0 auto;}

    /* Header */
    .header{text-align:center;border-bottom:3px double #0b6e4f;padding-bottom:8px;margin-bottom:10px;}
    .clinic-name{font-size:19pt;font-weight:bold;color:#0b6e4f;letter-spacing:.5px;}
    .clinic-sub{font-size:9.5pt;color:#374151;margin-top:2px;}
    .clinic-addr{font-size:8.5pt;color:#6b7280;margin-top:1px;}

    /* Report Title */
    .report-title{
      text-align:center;background:#0b6e4f;color:#fff;
      padding:5px;font-size:12pt;font-weight:bold;
      letter-spacing:1px;margin:10px 0 8px;
      text-transform:uppercase;
    }

    /* Info Row */
    .info-grid{display:grid;grid-template-columns:1fr 1fr;gap:0;border:1px solid #ccc;margin-bottom:10px;}
    .info-cell{padding:4px 8px;font-size:9.5pt;border-bottom:1px solid #e5e7eb;}
    .info-cell:nth-child(odd){border-right:1px solid #e5e7eb;}
    .info-label{color:#6b7280;font-size:8.5pt;}
    .info-value{font-weight:bold;}

    /* Table */
    table{width:100%;border-collapse:collapse;margin-bottom:8px;}
    th{background:#0b6e4f;color:#fff;padding:5px 8px;text-align:left;font-size:9pt;font-weight:bold;letter-spacing:.3px;}
    td{padding:4px 8px;font-size:9.5pt;border-bottom:1px solid #e5e7eb;}
    tr:last-child td{border-bottom:none;}
    .section-header td{background:#f0fdf6;font-weight:bold;font-size:8.5pt;color:#0b6e4f;padding:4px 8px;letter-spacing:.5px;text-transform:uppercase;border-top:1px solid #d1fae5;}
    .ref-range{color:#9ca3af;font-size:8.5pt;}
    .impression-row td{background:#f0fdf6;font-size:11pt;font-weight:bold;color:#0b6e4f;}
    .col-param{width:40%;}
    .col-result{width:30%;}
    .col-ref{width:30%;}

    /* Footer */
    .footer{margin-top:16px;border-top:1px solid #ccc;padding-top:8px;display:flex;justify-content:space-between;align-items:flex-end;}
    .sig-block{text-align:center;width:160px;}
    .sig-line{border-top:1px solid #333;margin-top:30px;padding-top:4px;font-size:8.5pt;color:#374151;}
    .footer-center{text-align:center;font-size:8pt;color:#9ca3af;}
    .barcode-area{font-size:8pt;color:#374151;}

    @media print{
      body{padding:6mm 8mm;}
      .no-print{display:none!important;}
      @page{size:A4;margin:10mm 8mm;}
    }
  </style>
</head>
<body>
<div class="page">

  {{-- Print Button (hidden on print) --}}
  <div class="no-print" style="text-align:right;margin-bottom:10px;">
    <button onclick="window.print()" style="background:#0b6e4f;color:#fff;border:none;padding:8px 20px;border-radius:6px;cursor:pointer;font-size:13px;">
      🖨 Print Report
    </button>
    <button onclick="window.close()" style="background:#f3f4f6;color:#374151;border:1px solid #e5e7eb;padding:8px 16px;border-radius:6px;cursor:pointer;font-size:13px;margin-left:8px;">
      ✕ Close
    </button>
  </div>

  {{-- Clinic Header --}}
  <div class="header">
    <div class="clinic-name">Meena IVF &amp; Fertility Care Limited</div>
    <div class="clinic-sub">Andrology Laboratory — Department of Reproductive Medicine</div>
    <div class="clinic-addr">Block-K, Road-22, House-11, Banani, Dhaka-1213 &nbsp;|&nbsp; Tel: 9611678979 &nbsp;|&nbsp; meenaivffertility@gmail.com</div>
  </div>

  <div class="report-title">Semen Analysis Report</div>

  {{-- Patient Info --}}
  @php $p = $report->patient; $d = $report->report_data ?? []; @endphp
  <div class="info-grid">
    <div class="info-cell">
      <div class="info-label">Patient Name</div>
      <div class="info-value">{{ $p->name }}</div>
    </div>
    <div class="info-cell">
      <div class="info-label">Sample Code</div>
      <div class="info-value" style="color:#0b6e4f;">{{ $report->sample_code }}</div>
    </div>
    <div class="info-cell">
      <div class="info-label">Patient ID (UHID)</div>
      <div class="info-value">{{ $p->patient_code }}</div>
    </div>
    <div class="info-cell">
      <div class="info-label">Report Date</div>
      <div class="info-value">{{ $report->reported_at ? $report->reported_at->format('d M Y') : now()->format('d M Y') }}</div>
    </div>
    <div class="info-cell">
      <div class="info-label">Age</div>
      <div class="info-value">{{ $p->age ?? ($p->dob ? \Carbon\Carbon::parse($p->dob)->age.' yrs' : '—') }}</div>
    </div>
    <div class="info-cell">
      <div class="info-label">Phone</div>
      <div class="info-value">{{ $p->phone }}</div>
    </div>
    <div class="info-cell">
      <div class="info-label">Collection Time</div>
      <div class="info-value">{{ $d['collection_time'] ?? '—' }}</div>
    </div>
    <div class="info-cell">
      <div class="info-label">Examination Time</div>
      <div class="info-value">{{ $d['examination_time'] ?? '—' }}</div>
    </div>
    <div class="info-cell">
      <div class="info-label">Abstinence Period</div>
      <div class="info-value">{{ isset($d['abstinence_days']) ? $d['abstinence_days'].' days' : '—' }}</div>
    </div>
    <div class="info-cell">
      <div class="info-label">Reported by</div>
      <div class="info-value">{{ $report->reporter?->name ?? '—' }}</div>
    </div>
  </div>

  {{-- Results Table --}}
  <table>
    <thead>
      <tr>
        <th class="col-param">Parameter</th>
        <th class="col-result">Result</th>
        <th class="col-ref">Reference Range (WHO 2021)</th>
      </tr>
    </thead>
    <tbody>
      <tr class="section-header"><td colspan="3">Macroscopic Examination</td></tr>
      <tr><td>Ejaculate Volume</td><td>{{ $d['volume'] ?? '—' }} mL</td><td class="ref-range">≥ 1.4 mL</td></tr>
      <tr><td>Colour</td><td>{{ $d['color'] ?? '—' }}</td><td class="ref-range">Whitish grey</td></tr>
      <tr><td>Viscosity</td><td>{{ $d['viscosity'] ?? '—' }}</td><td class="ref-range">Normal</td></tr>
      <tr><td>pH</td><td>{{ $d['ph'] ?? '—' }}</td><td class="ref-range">≥ 7.2</td></tr>
      <tr><td>Liquefaction Time</td><td>{{ $d['liquefaction'] ?? '—' }}</td><td class="ref-range">≤ 60 min</td></tr>
      <tr><td>Appearance</td><td>{{ $d['appearance'] ?? '—' }}</td><td class="ref-range">Homogeneous</td></tr>

      <tr class="section-header"><td colspan="3">Sperm Concentration &amp; Count</td></tr>
      <tr><td>Sperm Concentration</td><td>{{ isset($d['concentration']) ? $d['concentration'].' ×10⁶/mL' : '—' }}</td><td class="ref-range">≥ 16 ×10⁶/mL</td></tr>
      <tr><td>Total Sperm Count</td><td>{{ isset($d['total_count']) ? $d['total_count'].' ×10⁶' : '—' }}</td><td class="ref-range">≥ 39 ×10⁶</td></tr>

      <tr class="section-header"><td colspan="3">Motility</td></tr>
      <tr><td>Progressive Rapid (PR-a)</td><td>{{ isset($d['motility']['progressive_rapid']) ? $d['motility']['progressive_rapid'].'%' : '—' }}</td><td class="ref-range">—</td></tr>
      <tr><td>Progressive Slow (PR-b)</td><td>{{ isset($d['motility']['progressive_slow']) ? $d['motility']['progressive_slow'].'%' : '—' }}</td><td class="ref-range">—</td></tr>
      <tr><td>Non-Progressive (NP)</td><td>{{ isset($d['motility']['non_progressive']) ? $d['motility']['non_progressive'].'%' : '—' }}</td><td class="ref-range">—</td></tr>
      <tr><td>Immotile (IM)</td><td>{{ isset($d['motility']['immotile']) ? $d['motility']['immotile'].'%' : '—' }}</td><td class="ref-range">—</td></tr>
      <tr><td><strong>Total Motility (PR-a + PR-b)</strong></td><td><strong>{{ isset($d['total_motility']) ? $d['total_motility'].'%' : '—' }}</strong></td><td class="ref-range">≥ 42%</td></tr>
      <tr><td>Total Progressive Motility</td><td>{{ isset($d['total_progressive_motility']) ? $d['total_progressive_motility'].'%' : '—' }}</td><td class="ref-range">≥ 30%</td></tr>

      <tr class="section-header"><td colspan="3">Morphology (Strict Criteria)</td></tr>
      <tr><td><strong>Normal Forms</strong></td><td><strong>{{ isset($d['morphology']['normal']) ? $d['morphology']['normal'].'%' : '—' }}</strong></td><td class="ref-range">≥ 4%</td></tr>
      <tr><td>Head Defects</td><td>{{ isset($d['morphology']['head_defects']) ? $d['morphology']['head_defects'].'%' : '—' }}</td><td class="ref-range">—</td></tr>
      <tr><td>Neck / Mid-piece Defects</td><td>{{ isset($d['morphology']['neck_defects']) ? $d['morphology']['neck_defects'].'%' : '—' }}</td><td class="ref-range">—</td></tr>
      <tr><td>Tail Defects</td><td>{{ isset($d['morphology']['tail_defects']) ? $d['morphology']['tail_defects'].'%' : '—' }}</td><td class="ref-range">—</td></tr>

      <tr class="section-header"><td colspan="3">Other Findings</td></tr>
      <tr><td>WBC / HPF</td><td>{{ $d['wbc'] ?? '—' }}</td><td class="ref-range">&lt; 1×10⁶/mL</td></tr>
      <tr><td>RBC / HPF</td><td>{{ $d['rbc'] ?? '—' }}</td><td class="ref-range">Nil</td></tr>
      <tr><td>Epithelial Cells</td><td>{{ $d['epithelial_cells'] ?? '—' }}</td><td class="ref-range">Occasional</td></tr>
      <tr><td>Agglutination</td><td>{{ $d['agglutination'] ?? '—' }}</td><td class="ref-range">Absent</td></tr>

      <tr class="impression-row">
        <td colspan="3">
          <strong>Impression:</strong>&nbsp;&nbsp;{{ $d['impression'] ?? '—' }}
          @if(!empty($d['remarks']))
          &nbsp;&nbsp;<span style="font-size:9.5pt;font-weight:normal;color:#374151;">— {{ $d['remarks'] }}</span>
          @endif
        </td>
      </tr>
    </tbody>
  </table>

  {{-- Footer --}}
  <div class="footer">
    <div class="barcode-area">
      <div style="font-size:8pt;color:#9ca3af;">Sample Code</div>
      <div style="font-size:11pt;font-weight:bold;letter-spacing:2px;color:#374151;">{{ $report->sample_code }}</div>
      <div style="font-size:7.5pt;color:#9ca3af;">Printed: {{ now()->format('d M Y, h:i A') }}</div>
    </div>
    <div class="footer-center">
      <div style="font-size:7.5pt;color:#9ca3af;">This report is generated by the Meena IVF Hospital Management System.</div>
      <div style="font-size:7.5pt;color:#9ca3af;">Results should be interpreted by a qualified clinician.</div>
    </div>
    <div class="sig-block">
      <div class="sig-line">{{ $report->reporter?->name ?? 'Lab Technician' }}<br>Andrology Lab, Meena IVF</div>
    </div>
  </div>

</div>
<script>
  // Auto print on page load
  window.onload = function() { window.print(); };
</script>
</body>
</html>
