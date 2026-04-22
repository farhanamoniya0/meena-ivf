@php $d = $data ?? []; @endphp
<table class="table table-sm" style="font-size:.82rem;">
  <tbody>
    <tr class="table-light"><td colspan="2" class="fw-700 py-1" style="font-size:.72rem;letter-spacing:.5px;text-transform:uppercase;">Sample Information</td></tr>
    <tr><td class="text-muted" style="width:45%;">Abstinence Period</td><td>{{ $d['abstinence_days'] ?? '—' }} days</td></tr>
    <tr><td class="text-muted">Collection Time</td><td>{{ $d['collection_time'] ?? '—' }}</td></tr>
    <tr><td class="text-muted">Examination Time</td><td>{{ $d['examination_time'] ?? '—' }}</td></tr>

    <tr class="table-light"><td colspan="2" class="fw-700 py-1" style="font-size:.72rem;letter-spacing:.5px;text-transform:uppercase;">Macroscopic Examination</td></tr>
    <tr><td class="text-muted">Volume</td><td>{{ $d['volume'] ?? '—' }} mL</td></tr>
    <tr><td class="text-muted">Colour</td><td>{{ $d['color'] ?? '—' }}</td></tr>
    <tr><td class="text-muted">Viscosity</td><td>{{ $d['viscosity'] ?? '—' }}</td></tr>
    <tr><td class="text-muted">pH</td><td>{{ $d['ph'] ?? '—' }}</td></tr>
    <tr><td class="text-muted">Liquefaction</td><td>{{ $d['liquefaction'] ?? '—' }}</td></tr>
    <tr><td class="text-muted">Appearance</td><td>{{ $d['appearance'] ?? '—' }}</td></tr>

    <tr class="table-light"><td colspan="2" class="fw-700 py-1" style="font-size:.72rem;letter-spacing:.5px;text-transform:uppercase;">Sperm Concentration</td></tr>
    <tr><td class="text-muted">Concentration</td><td>{{ $d['concentration'] ?? '—' }} ×10⁶/mL</td></tr>
    <tr><td class="text-muted">Total Count</td><td>{{ $d['total_count'] ?? '—' }} ×10⁶</td></tr>

    <tr class="table-light"><td colspan="2" class="fw-700 py-1" style="font-size:.72rem;letter-spacing:.5px;text-transform:uppercase;">Motility</td></tr>
    <tr><td class="text-muted">Progressive Rapid (PR-a)</td><td>{{ $d['motility']['progressive_rapid'] ?? '—' }}%</td></tr>
    <tr><td class="text-muted">Progressive Slow (PR-b)</td><td>{{ $d['motility']['progressive_slow'] ?? '—' }}%</td></tr>
    <tr><td class="text-muted">Non-Progressive (NP)</td><td>{{ $d['motility']['non_progressive'] ?? '—' }}%</td></tr>
    <tr><td class="text-muted">Immotile (IM)</td><td>{{ $d['motility']['immotile'] ?? '—' }}%</td></tr>
    <tr><td class="text-muted">Total Motility</td><td class="fw-600">{{ $d['total_motility'] ?? '—' }}%</td></tr>
    <tr><td class="text-muted">Total Progressive Motility</td><td>{{ $d['total_progressive_motility'] ?? '—' }}%</td></tr>

    <tr class="table-light"><td colspan="2" class="fw-700 py-1" style="font-size:.72rem;letter-spacing:.5px;text-transform:uppercase;">Morphology (Strict Criteria)</td></tr>
    <tr><td class="text-muted">Normal Forms</td><td class="fw-600">{{ $d['morphology']['normal'] ?? '—' }}%</td></tr>
    <tr><td class="text-muted">Head Defects</td><td>{{ $d['morphology']['head_defects'] ?? '—' }}%</td></tr>
    <tr><td class="text-muted">Neck/Mid-piece Defects</td><td>{{ $d['morphology']['neck_defects'] ?? '—' }}%</td></tr>
    <tr><td class="text-muted">Tail Defects</td><td>{{ $d['morphology']['tail_defects'] ?? '—' }}%</td></tr>

    <tr class="table-light"><td colspan="2" class="fw-700 py-1" style="font-size:.72rem;letter-spacing:.5px;text-transform:uppercase;">Other Findings</td></tr>
    <tr><td class="text-muted">WBC/HPF</td><td>{{ $d['wbc'] ?? '—' }}</td></tr>
    <tr><td class="text-muted">RBC/HPF</td><td>{{ $d['rbc'] ?? '—' }}</td></tr>
    <tr><td class="text-muted">Epithelial Cells</td><td>{{ $d['epithelial_cells'] ?? '—' }}</td></tr>
    <tr><td class="text-muted">Agglutination</td><td>{{ $d['agglutination'] ?? '—' }}</td></tr>

    <tr class="table-light"><td colspan="2" class="fw-700 py-1" style="font-size:.72rem;letter-spacing:.5px;text-transform:uppercase;">Conclusion</td></tr>
    <tr><td class="text-muted">Impression</td>
      <td class="fw-700" style="color:#0b6e4f;font-size:.9rem;">{{ $d['impression'] ?? '—' }}</td></tr>
    <tr><td class="text-muted">Remarks</td><td>{{ $d['remarks'] ?: '—' }}</td></tr>
  </tbody>
</table>
