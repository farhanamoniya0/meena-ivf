@php $d = $data ?? []; @endphp
<div class="row g-2" style="font-size:.82rem;">
  <div class="col-12 mb-1">
    <div class="fw-700" style="font-size:.72rem;text-transform:uppercase;letter-spacing:.8px;color:#6b7280;">Sample Information</div>
  </div>
  <div class="col-md-4">
    <label class="form-label mb-1">Abstinence (days)</label>
    <input type="number" name="report_data[abstinence_days]" class="form-control form-control-sm" value="{{ $d['abstinence_days'] ?? '' }}" placeholder="e.g. 3">
  </div>
  <div class="col-md-4">
    <label class="form-label mb-1">Collection Time</label>
    <input type="text" name="report_data[collection_time]" class="form-control form-control-sm" value="{{ $d['collection_time'] ?? '' }}" placeholder="e.g. 10:30 AM">
  </div>
  <div class="col-md-4">
    <label class="form-label mb-1">Examination Time</label>
    <input type="text" name="report_data[examination_time]" class="form-control form-control-sm" value="{{ $d['examination_time'] ?? '' }}" placeholder="e.g. 11:00 AM">
  </div>

  <div class="col-12 mt-2 mb-1">
    <div class="fw-700" style="font-size:.72rem;text-transform:uppercase;letter-spacing:.8px;color:#6b7280;">Macroscopic Examination</div>
  </div>
  <div class="col-md-3">
    <label class="form-label mb-1">Volume (mL)</label>
    <input type="number" step="0.1" name="report_data[volume]" class="form-control form-control-sm" value="{{ $d['volume'] ?? '' }}" placeholder="e.g. 2.5">
  </div>
  <div class="col-md-3">
    <label class="form-label mb-1">Colour</label>
    <input type="text" name="report_data[color]" class="form-control form-control-sm" value="{{ $d['color'] ?? 'Whitish grey' }}" placeholder="Whitish grey">
  </div>
  <div class="col-md-3">
    <label class="form-label mb-1">Viscosity</label>
    <select name="report_data[viscosity]" class="form-select form-select-sm">
      <option value="Normal" {{ ($d['viscosity']??'Normal')==='Normal'?'selected':'' }}>Normal</option>
      <option value="High" {{ ($d['viscosity']??'')==='High'?'selected':'' }}>High</option>
      <option value="Low" {{ ($d['viscosity']??'')==='Low'?'selected':'' }}>Low</option>
    </select>
  </div>
  <div class="col-md-3">
    <label class="form-label mb-1">pH</label>
    <input type="number" step="0.1" name="report_data[ph]" class="form-control form-control-sm" value="{{ $d['ph'] ?? '' }}" placeholder="e.g. 7.4">
  </div>
  <div class="col-md-4">
    <label class="form-label mb-1">Liquefaction Time</label>
    <input type="text" name="report_data[liquefaction]" class="form-control form-control-sm" value="{{ $d['liquefaction'] ?? '30 min' }}" placeholder="30 min">
  </div>
  <div class="col-md-4">
    <label class="form-label mb-1">Appearance</label>
    <input type="text" name="report_data[appearance]" class="form-control form-control-sm" value="{{ $d['appearance'] ?? 'Homogeneous' }}" placeholder="Homogeneous">
  </div>

  <div class="col-12 mt-2 mb-1">
    <div class="fw-700" style="font-size:.72rem;text-transform:uppercase;letter-spacing:.8px;color:#6b7280;">Microscopic Examination — Sperm Concentration</div>
  </div>
  <div class="col-md-4">
    <label class="form-label mb-1">Concentration (×10⁶/mL)</label>
    <input type="number" step="0.1" name="report_data[concentration]" class="form-control form-control-sm" value="{{ $d['concentration'] ?? '' }}" placeholder="e.g. 45">
  </div>
  <div class="col-md-4">
    <label class="form-label mb-1">Total Count (×10⁶)</label>
    <input type="number" step="0.1" name="report_data[total_count]" class="form-control form-control-sm" value="{{ $d['total_count'] ?? '' }}" placeholder="Auto or manual">
  </div>

  <div class="col-12 mt-2 mb-1">
    <div class="fw-700" style="font-size:.72rem;text-transform:uppercase;letter-spacing:.8px;color:#6b7280;">Motility (%)</div>
  </div>
  <div class="col-md-3">
    <label class="form-label mb-1">Progressive Rapid (PR-a)</label>
    <input type="number" step="0.1" name="report_data[motility][progressive_rapid]" class="form-control form-control-sm" value="{{ ($d['motility']['progressive_rapid'] ?? '') }}" placeholder="%">
  </div>
  <div class="col-md-3">
    <label class="form-label mb-1">Progressive Slow (PR-b)</label>
    <input type="number" step="0.1" name="report_data[motility][progressive_slow]" class="form-control form-control-sm" value="{{ ($d['motility']['progressive_slow'] ?? '') }}" placeholder="%">
  </div>
  <div class="col-md-3">
    <label class="form-label mb-1">Non-Progressive (NP)</label>
    <input type="number" step="0.1" name="report_data[motility][non_progressive]" class="form-control form-control-sm" value="{{ ($d['motility']['non_progressive'] ?? '') }}" placeholder="%">
  </div>
  <div class="col-md-3">
    <label class="form-label mb-1">Immotile (IM)</label>
    <input type="number" step="0.1" name="report_data[motility][immotile]" class="form-control form-control-sm" value="{{ ($d['motility']['immotile'] ?? '') }}" placeholder="%">
  </div>
  <div class="col-md-4">
    <label class="form-label mb-1">Total Motility (PR-a + PR-b) %</label>
    <input type="number" step="0.1" name="report_data[total_motility]" class="form-control form-control-sm" value="{{ $d['total_motility'] ?? '' }}" placeholder="%">
  </div>
  <div class="col-md-4">
    <label class="form-label mb-1">Total Progressive Motility %</label>
    <input type="number" step="0.1" name="report_data[total_progressive_motility]" class="form-control form-control-sm" value="{{ $d['total_progressive_motility'] ?? '' }}" placeholder="%">
  </div>

  <div class="col-12 mt-2 mb-1">
    <div class="fw-700" style="font-size:.72rem;text-transform:uppercase;letter-spacing:.8px;color:#6b7280;">Morphology — Strict Criteria (%)</div>
  </div>
  <div class="col-md-3">
    <label class="form-label mb-1">Normal Forms</label>
    <input type="number" step="0.1" name="report_data[morphology][normal]" class="form-control form-control-sm" value="{{ ($d['morphology']['normal'] ?? '') }}" placeholder="%">
  </div>
  <div class="col-md-3">
    <label class="form-label mb-1">Head Defects</label>
    <input type="number" step="0.1" name="report_data[morphology][head_defects]" class="form-control form-control-sm" value="{{ ($d['morphology']['head_defects'] ?? '') }}" placeholder="%">
  </div>
  <div class="col-md-3">
    <label class="form-label mb-1">Neck/Mid Defects</label>
    <input type="number" step="0.1" name="report_data[morphology][neck_defects]" class="form-control form-control-sm" value="{{ ($d['morphology']['neck_defects'] ?? '') }}" placeholder="%">
  </div>
  <div class="col-md-3">
    <label class="form-label mb-1">Tail Defects</label>
    <input type="number" step="0.1" name="report_data[morphology][tail_defects]" class="form-control form-control-sm" value="{{ ($d['morphology']['tail_defects'] ?? '') }}" placeholder="%">
  </div>

  <div class="col-12 mt-2 mb-1">
    <div class="fw-700" style="font-size:.72rem;text-transform:uppercase;letter-spacing:.8px;color:#6b7280;">Other Findings</div>
  </div>
  <div class="col-md-3">
    <label class="form-label mb-1">WBC/HPF</label>
    <input type="text" name="report_data[wbc]" class="form-control form-control-sm" value="{{ $d['wbc'] ?? '1-2' }}" placeholder="e.g. 1-2">
  </div>
  <div class="col-md-3">
    <label class="form-label mb-1">RBC/HPF</label>
    <input type="text" name="report_data[rbc]" class="form-control form-control-sm" value="{{ $d['rbc'] ?? 'Nil' }}" placeholder="Nil">
  </div>
  <div class="col-md-3">
    <label class="form-label mb-1">Epithelial Cells</label>
    <input type="text" name="report_data[epithelial_cells]" class="form-control form-control-sm" value="{{ $d['epithelial_cells'] ?? 'Occasional' }}" placeholder="Occasional">
  </div>
  <div class="col-md-3">
    <label class="form-label mb-1">Agglutination</label>
    <select name="report_data[agglutination]" class="form-select form-select-sm">
      <option value="Absent" {{ ($d['agglutination']??'Absent')==='Absent'?'selected':'' }}>Absent</option>
      <option value="Present" {{ ($d['agglutination']??'')==='Present'?'selected':'' }}>Present</option>
    </select>
  </div>

  <div class="col-12 mt-2 mb-1">
    <div class="fw-700" style="font-size:.72rem;text-transform:uppercase;letter-spacing:.8px;color:#6b7280;">Conclusion</div>
  </div>
  <div class="col-md-6">
    <label class="form-label mb-1">Impression / Diagnosis</label>
    <select name="report_data[impression]" class="form-select form-select-sm">
      @foreach(['Normozoospermia','Oligozoospermia','Asthenozoospermia','Teratozoospermia','Oligoasthenoteratozoospermia (OAT)','Azoospermia','Cryptozoospermia','Hypospermia','Hyperspermia','Leukocytospermia','Necrozoospermia'] as $imp)
      <option value="{{ $imp }}" {{ ($d['impression']??'')===$imp?'selected':'' }}>{{ $imp }}</option>
      @endforeach
    </select>
  </div>
  <div class="col-md-6">
    <label class="form-label mb-1">Remarks</label>
    <input type="text" name="report_data[remarks]" class="form-control form-control-sm" value="{{ $d['remarks'] ?? '' }}" placeholder="Additional remarks…">
  </div>
</div>
