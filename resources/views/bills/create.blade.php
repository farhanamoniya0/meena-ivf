@extends('layouts.app')
@section('title','Create Bill')
@section('page-title','Create Bill')
@push('styles')
<style>
.bill-section-title{font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:#7c3aed;padding:6px 0 4px;}
.bill-table thead th{font-size:.75rem;background:#f3e8ff;color:#374151;padding:6px 8px;white-space:nowrap;border-color:#e9d5ff;}
.bill-table tbody td{padding:4px 6px;vertical-align:middle;border-color:#f3f4f6;}
.bill-table tfoot td{padding:5px 8px;font-size:.82rem;background:#faf5ff;border-color:#e9d5ff;}
.bill-table .form-control-sm{font-size:.8rem;padding:2px 6px;border-radius:4px;}
.bill-table .form-select-sm{font-size:.8rem;padding:2px 24px 2px 6px;border-radius:4px;}
.pay-btn{border:2px solid #dee2e6;border-radius:6px;padding:4px 10px;cursor:pointer;font-size:.78rem;background:#fff;transition:all .15s;}
.pay-btn.active,.pay-btn:hover{border-color:#7c3aed;background:#f3e8ff;color:#5b21b6;font-weight:600;}
#patientBox{display:none;background:#faf5ff;border:1px solid #e9d5ff;border-radius:8px;padding:10px 14px;font-size:.82rem;}
.bottom-bar{position:sticky;bottom:0;background:#fff;border-top:2px solid #e9d5ff;padding:10px 16px;z-index:100;margin:0 -12px;}
</style>
@endpush
@section('content')
<form method="POST" action="{{ route('bills.store') }}" id="billForm">
@csrf

{{-- ===== TOP ACTION BAR ===== --}}
<div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
  <div class="d-flex align-items-center gap-2">
    <i class="bi bi-receipt-cutoff fs-5" style="color:#7c3aed;"></i>
    <span class="fw-700 fs-6">New Bill</span>
  </div>
  <div class="d-flex gap-2">
    <a href="{{ route('bills.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-list-ul me-1"></i>All Bills</a>
  </div>
</div>

<div class="card mb-2">
  <div class="card-body p-3">

    {{-- ===== PATIENT SEARCH ROW ===== --}}
    <div class="bill-section-title"><i class="bi bi-search me-1"></i>Search Patient</div>
    <div class="row g-2 mb-2 align-items-end">
      <div class="col-md-3">
        <label class="form-label mb-1" style="font-size:.75rem;">Patient Code (UHID)</label>
        <input type="text" id="searchCode" class="form-control form-control-sm" placeholder="e.g. PAT-2024-0001">
      </div>
      <div class="col-md-3">
        <label class="form-label mb-1" style="font-size:.75rem;">Phone</label>
        <input type="text" id="searchPhone" class="form-control form-control-sm" placeholder="01XXXXXXXXX">
      </div>
      <div class="col-md-3">
        <label class="form-label mb-1" style="font-size:.75rem;">Name</label>
        <input type="text" id="searchName" class="form-control form-control-sm" placeholder="Patient name">
      </div>
      <div class="col-md-2">
        <button type="button" class="btn btn-sm btn-primary w-100" onclick="searchPatient()"><i class="bi bi-search me-1"></i>Search</button>
      </div>
      <div class="col-md-1">
        <button type="button" class="btn btn-sm btn-outline-secondary w-100" onclick="clearPatient()" title="Clear"><i class="bi bi-x-lg"></i></button>
      </div>
    </div>

    {{-- Autocomplete dropdown --}}
    <div id="searchResults" class="list-group shadow" style="position:absolute;z-index:999;max-width:480px;display:none;"></div>

    {{-- Patient info box (shown after selection) --}}
    <div id="patientBox">
      <div class="d-flex align-items-center gap-3 flex-wrap">
        <div><span style="color:#9ca3af;font-size:.7rem;">PATIENT</span><div class="fw-700" id="pbName">—</div></div>
        <div><span style="color:#9ca3af;font-size:.7rem;">CODE</span><div class="fw-600" id="pbCode">—</div></div>
        <div><span style="color:#9ca3af;font-size:.7rem;">PHONE</span><div id="pbPhone">—</div></div>
        <div><span style="color:#9ca3af;font-size:.7rem;">DOB</span><div id="pbDob">—</div></div>
        <div><span style="color:#9ca3af;font-size:.7rem;">GENDER</span><div id="pbGender">—</div></div>
        <div id="pbAdvanceWrap" style="display:none;">
          <span style="color:#9ca3af;font-size:.7rem;">ADVANCE CREDIT</span>
          <div class="fw-700" id="pbAdvance" style="color:#00695c;">৳0</div>
        </div>
        <div class="ms-auto">
          <span class="badge" style="background:#7c3aed;" id="pbBadge">Selected</span>
        </div>
      </div>
    </div>
    <input type="hidden" name="patient_id" id="patientId" value="{{ $patient?->id }}">

    @if($patient)
    <script>
    document.addEventListener('DOMContentLoaded', function(){
      showPatient({id:{{ $patient->id }}, name:'{{ addslashes($patient->name) }}', patient_code:'{{ $patient->patient_code }}', phone:'{{ $patient->phone }}', dob:'{{ $patient->date_of_birth ?? '' }}', gender:'{{ $patient->gender ?? '' }}', advance_balance:{{ $patient->advance_balance ?? 0 }});
    });
    </script>
    @endif

    <hr class="my-2">

    {{-- ===== VISIT DETAILS ROW ===== --}}
    <div class="bill-section-title"><i class="bi bi-calendar3 me-1"></i>Visit Details</div>
    <div class="row g-2 mb-2">
      <div class="col-md-3">
        <label class="form-label mb-1" style="font-size:.75rem;">Bill Date *</label>
        <input type="date" name="bill_date" class="form-control form-control-sm" value="{{ old('bill_date',today()->format('Y-m-d')) }}" required>
      </div>
      <div class="col-md-4">
        <label class="form-label mb-1" style="font-size:.75rem;">Doctor / Consultant</label>
        <div class="d-flex gap-1">
          <select name="consultant_id" id="consultantDd" class="form-select form-select-sm">
            <option value="">— Select —</option>
            @foreach($consultants as $c)
            <option value="{{ $c->id }}" data-fee="{{ $c->consultation_fee }}">{{ $c->name }} (৳{{ number_format($c->consultation_fee) }})</option>
            @endforeach
          </select>
          <button type="button" id="addConsultFeeBtn" class="btn btn-sm btn-outline-primary d-none text-nowrap" onclick="addConsultFeeRow()" title="Add consultation fee to bill">
            <i class="bi bi-plus-lg me-1"></i>Fee
          </button>
        </div>
      </div>
      <div class="col-md-3">
        <label class="form-label mb-1" style="font-size:.75rem;">Bill Remarks</label>
        <input type="text" name="notes" class="form-control form-control-sm" placeholder="Optional remarks">
      </div>
      <div class="col-md-2 d-flex align-items-end">
        <div class="form-check mb-1">
          <input class="form-check-input" type="checkbox" name="add_reg_fee" value="1" id="regFeeChk">
          <label class="form-check-label" for="regFeeChk" style="font-size:.78rem;">Add Reg. Fee (৳200)</label>
        </div>
      </div>
    </div>

    <hr class="my-2">

    {{-- ===== BILL CHARGES TABLE ===== --}}
    <div class="d-flex align-items-center justify-content-between mb-1">
      <div class="bill-section-title mb-0"><i class="bi bi-table me-1"></i>Bill Charges <small style="font-size:.65rem;color:#9ca3af;">All amounts in BDT</small></div>
      <div class="d-flex gap-2 align-items-center">
        <select id="svcCodeDd" class="form-select form-select-sm" style="width:280px;">
          <option value="">— Select Service Code to Add —</option>
          @foreach($services->groupBy('category') as $cat => $svcs)
            @if($cat)<optgroup label="{{ $cat }}">@endif
            @foreach($svcs as $svc)
            <option value="{{ $svc->id }}"
              data-code="{{ $svc->service_code }}"
              data-name="{{ $svc->name }}"
              data-rate="{{ $svc->charge }}">
              [{{ $svc->service_code }}] {{ $svc->name }} — ৳{{ number_format($svc->charge) }}
            </option>
            @endforeach
            @if($cat)</optgroup>@endif
          @endforeach
        </select>
        <button type="button" class="btn btn-sm btn-primary" onclick="addServiceRow()"><i class="bi bi-plus-lg me-1"></i>Add</button>
        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="addCustomRow()"><i class="bi bi-pencil me-1"></i>Custom</button>
      </div>
    </div>

    <div class="table-responsive">
      <table class="table table-bordered bill-table mb-1" id="billTable">
        <thead>
          <tr>
            <th style="width:32px;"></th>
            <th style="width:90px;">Code</th>
            <th>Description</th>
            <th style="width:70px;">Qty</th>
            <th style="width:100px;">Rate (৳)</th>
            <th style="width:100px;">Gross (৳)</th>
            <th style="width:90px;">Disc. (৳)</th>
            <th style="width:110px;">Patient Amt (৳)</th>
          </tr>
        </thead>
        <tbody id="billRows">
          {{-- Rows added dynamically --}}
        </tbody>
        <tfoot>
          <tr>
            <td colspan="5" class="text-end fw-600">Subtotal</td>
            <td class="fw-700" id="footGross">0.00</td>
            <td>
              <input type="number" name="discount" id="discountInp" class="form-control form-control-sm text-end" value="0" min="0" step="0.01" style="width:80px;" title="Total Discount">
            </td>
            <td class="fw-700 text-primary" id="footNet">0.00</td>
          </tr>
          <tr style="background:#f3e8ff;">
            <td colspan="6" class="text-end fw-700">NET TOTAL</td>
            <td colspan="2" class="fw-700 text-end" style="color:#5b21b6;font-size:1rem;" id="footTotal">৳ 0.00</td>
          </tr>
        </tfoot>
      </table>
    </div>

    {{-- ===== ADVANCE ADJUSTMENT STRIP (shown only when patient has advance balance) ===== --}}
    <div id="advanceSection" style="display:none;margin-top:8px;padding:8px 12px;background:#e0f7fa;border-top:1px solid #80deea;border-bottom:1px solid #80deea;">
      <div class="d-flex align-items-center gap-3 flex-wrap">
        <div>
          <i class="bi bi-piggy-bank me-1" style="color:#00695c;"></i>
          <strong>Advance Credit Available:</strong>
          <span id="advBal" class="fw-700" style="color:#00695c;">৳0</span>
        </div>
        <div class="form-check mb-0">
          <input class="form-check-input" type="checkbox" name="adjust_from_advance" id="advanceChk" value="1" onchange="toggleCreateAdvance(this)">
          <label class="form-check-label fw-600" for="advanceChk" style="font-size:.85rem;cursor:pointer;">
            Adjust Bill from Advance
          </label>
        </div>
        <div id="advanceNote" style="display:none;font-size:.78rem;color:#00695c;">
          <i class="bi bi-check-circle-fill me-1"></i>Bill will be settled against advance credit &mdash; no cash/card required.
        </div>
      </div>
    </div>

    <hr class="my-2">

    {{-- ===== PAYMENT DETAILS ===== --}}
    <div class="bill-section-title"><i class="bi bi-cash-coin me-1"></i>Payment Details</div>
    <div class="row g-2 align-items-start">

    {{-- Payment mode buttons --}}
      <div id="normalPayMethods" class="col-md-12 mb-1">
        <label class="form-label mb-1" style="font-size:.75rem;">Payment Mode</label>
        <div class="d-flex gap-1 flex-wrap">
          @foreach(['cash'=>'Cash','bank'=>'Bank','card'=>'Card','bkash'=>'bKash','nagad'=>'Nagad','rocket'=>'Rocket'] as $val=>$lbl)
          <label class="pay-btn {{ $val=='cash'?'active':'' }}" onclick="onPayMethod('{{ $val }}')">
            <input type="radio" name="payment_method" value="{{ $val }}" style="display:none;" {{ $val=='cash'?'checked':'' }}> {{ $lbl }}
          </label>
          @endforeach
        </div>
      </div>

      {{-- Paid Amount --}}
      <div class="col-md-2" id="paidAmtWrap">
        <label class="form-label mb-1" style="font-size:.75rem;">Paid Amount (৳)</label>
        <div class="input-group input-group-sm">
          <input type="number" name="paid_amount" id="paidAmt" class="form-control" placeholder="0.00" min="0" step="0.01">
          <button type="button" class="btn btn-outline-success" onclick="document.getElementById('paidAmt').value=document.getElementById('footNetVal').value" title="Pay Full Amount"><i class="bi bi-check-lg"></i></button>
        </div>
        <input type="hidden" id="footNetVal" value="0">
      </div>

      {{-- Bank fields --}}
      <div id="metaBank" class="col-md-10 d-none">
        <div class="row g-2">
          <div class="col-md-4">
            <label class="form-label mb-1" style="font-size:.75rem;">Bank Name *</label>
            <input type="text" name="meta_bank_name" class="form-control form-control-sm" placeholder="Type bank name..." list="bdBankList" autocomplete="off">
          </div>
          <div class="col-md-4">
            <label class="form-label mb-1" style="font-size:.75rem;">Account Holder Name</label>
            <input type="text" name="meta_account_holder" class="form-control form-control-sm" placeholder="Name on account">
          </div>
          <div class="col-md-4">
            <label class="form-label mb-1" style="font-size:.75rem;">Account / Cheque No.</label>
            <input type="text" name="meta_account_number" class="form-control form-control-sm" placeholder="Account or cheque number">
          </div>
        </div>
      </div>

      <datalist id="bdBankList">
        {{-- State-owned Banks --}}
        <option value="Sonali Bank PLC">
        <option value="Janata Bank PLC">
        <option value="Agrani Bank PLC">
        <option value="Rupali Bank PLC">
        <option value="Bangladesh Development Bank Limited (BDBL)">
        <option value="Basic Bank Limited">
        {{-- Private Commercial Banks --}}
        <option value="AB Bank PLC">
        <option value="Al-Arafah Islami Bank PLC">
        <option value="Bank Asia PLC">
        <option value="BRAC Bank PLC">
        <option value="City Bank PLC">
        <option value="Dhaka Bank PLC">
        <option value="Dutch-Bangla Bank PLC">
        <option value="Eastern Bank PLC">
        <option value="EXIM Bank PLC">
        <option value="First Security Islami Bank PLC">
        <option value="Global Islami Bank PLC">
        <option value="ICB Islamic Bank Limited">
        <option value="IFIC Bank PLC">
        <option value="Islami Bank Bangladesh PLC">
        <option value="Jamuna Bank PLC">
        <option value="Meghna Bank PLC">
        <option value="Mercantile Bank PLC">
        <option value="Midland Bank PLC">
        <option value="Modhumoti Bank PLC">
        <option value="Mutual Trust Bank PLC">
        <option value="National Bank Limited">
        <option value="NCC Bank PLC">
        <option value="One Bank PLC">
        <option value="Padma Bank PLC">
        <option value="Premier Bank PLC">
        <option value="Prime Bank PLC">
        <option value="Pubali Bank PLC">
        <option value="Shahjalal Islami Bank PLC">
        <option value="Social Islami Bank PLC">
        <option value="Southeast Bank PLC">
        <option value="Standard Bank PLC">
        <option value="Trust Bank PLC">
        <option value="Union Bank PLC">
        <option value="United Commercial Bank PLC (UCB)">
        <option value="Uttara Bank PLC">
        <option value="Community Bank Bangladesh PLC">
        <option value="Bengal Commercial Bank PLC">
        <option value="Citizens Bank PLC">
        {{-- Foreign Banks --}}
        <option value="Standard Chartered Bank Bangladesh">
        <option value="HSBC Bangladesh">
        <option value="Citibank N.A. Bangladesh">
        <option value="State Bank of India Bangladesh">
        <option value="Habib Bank Limited Bangladesh">
        <option value="Commercial Bank of Ceylon PLC Bangladesh">
        <option value="Woori Bank Bangladesh">
        <option value="National Bank of Pakistan Bangladesh">
        {{-- Specialized / Development Banks --}}
        <option value="Bangladesh Krishi Bank (BKB)">
        <option value="Rajshahi Krishi Unnayan Bank (RAKUB)">
        <option value="Bangladesh Samabay Bank Limited">
        <option value="Karma Sangsthan Bank">
        <option value="Palli Sanchay Bank">
        <option value="Probashi Kallyan Bank">
        <option value="Ansar VDP Unnayan Bank">
      </datalist>

      {{-- Card fields --}}
      <div id="metaCard" class="col-md-10 d-none">
        <div class="row g-2">
          <div class="col-md-3">
            <label class="form-label mb-1" style="font-size:.75rem;">Card Type</label>
            <select name="meta_card_type" class="form-select form-select-sm">
              <option value="">— Select —</option>
              <option value="visa">Visa</option>
              <option value="mastercard">Mastercard</option>
              <option value="amex">Amex</option>
              <option value="other">Other</option>
            </select>
          </div>
          <div class="col-md-4">
            <label class="form-label mb-1" style="font-size:.75rem;">Card Number (last 4 digits)</label>
            <input type="text" name="meta_card_number" class="form-control form-control-sm" placeholder="XXXX" maxlength="4">
          </div>
          <div class="col-md-4">
            <label class="form-label mb-1" style="font-size:.75rem;">Card Holder Name</label>
            <input type="text" name="meta_card_holder" class="form-control form-control-sm" placeholder="Name on card">
          </div>
        </div>
      </div>

      {{-- Mobile banking fields (bKash / Nagad / Rocket) --}}
      <div id="metaMobile" class="col-md-10 d-none">
        <div class="row g-2">
          <div class="col-md-4">
            <label class="form-label mb-1" style="font-size:.75rem;">Transaction ID</label>
            <input type="text" name="transaction_id" class="form-control form-control-sm" placeholder="e.g. 8N3D7GH2K1">
          </div>
          <div class="col-md-4">
            <label class="form-label mb-1" style="font-size:.75rem;">Mobile Number</label>
            <input type="text" name="meta_mobile_number" class="form-control form-control-sm" placeholder="01XXXXXXXXX">
          </div>
        </div>
      </div>

      {{-- Cash / default: no extra fields --}}
      <div id="metaCash" class="col-md-10">
        <div class="text-muted" style="font-size:.78rem;padding-top:24px;"><i class="bi bi-cash me-1"></i>Cash payment — no additional details required.</div>
      </div>

    </div>

    <div class="d-flex justify-content-end align-items-center gap-3 mt-3">
      <div class="form-check mb-0">
        <input class="form-check-input" type="checkbox" name="print_on_confirm" value="1" id="printChk" checked>
        <label class="form-check-label fw-600" for="printChk" style="font-size:.82rem;">Print Bill on confirm</label>
      </div>
      <button type="submit" class="btn fw-700 px-4" style="background:#5b21b6;color:#fff;border-radius:8px;">
        <i class="bi bi-check-lg me-2"></i>Confirm
      </button>
    </div>

  </div>
</div>
</form>
@endsection
@push('scripts')
<script>
// ---- Services data ----
const SERVICES = {
  @foreach($services as $svc)
  "{{ $svc->id }}": {id:{{ $svc->id }}, code:"{{ $svc->service_code }}", name:"{{ addslashes($svc->name) }}", rate:{{ $svc->charge }}},
  @endforeach
};

// ---- Patient search ----
let searchTimer;
['searchCode','searchPhone','searchName'].forEach(id=>{
  document.getElementById(id).addEventListener('input', function(){
    clearTimeout(searchTimer);
    const q = this.value.trim();
    if(q.length < 2){ document.getElementById('searchResults').style.display='none'; return; }
    searchTimer = setTimeout(()=>doSearch(q), 300);
  });
  document.getElementById(id).addEventListener('keydown', e=>{ if(e.key==='Enter'){ e.preventDefault(); searchPatient(); }});
});

function searchPatient(){
  const q = document.getElementById('searchCode').value.trim()
          || document.getElementById('searchPhone').value.trim()
          || document.getElementById('searchName').value.trim();
  if(q.length < 2) return;
  doSearch(q);
}

function doSearch(q){
  fetch('{{ route("patients.search") }}?q='+encodeURIComponent(q))
    .then(r=>r.json()).then(data=>{
      const sr = document.getElementById('searchResults');
      sr.innerHTML='';
      if(!data.length){ sr.style.display='none'; return; }
      data.forEach(p=>{
        const a = document.createElement('a');
        a.href='#'; a.className='list-group-item list-group-item-action py-2';
        a.style.fontSize='.83rem';
        a.innerHTML=`<strong>${p.name}</strong> &nbsp;<span class="badge bg-secondary">${p.patient_code}</span> &nbsp;<span class="text-muted">${p.phone}</span>`;
        a.addEventListener('click', e=>{ e.preventDefault(); showPatient(p); sr.style.display='none'; });
        sr.appendChild(a);
      });
      sr.style.display='block';
    });
}

function showPatient(p){
  document.getElementById('patientId').value = p.id;
  document.getElementById('pbName').textContent   = p.name;
  document.getElementById('pbCode').textContent   = p.patient_code;
  document.getElementById('pbPhone').textContent  = p.phone;
  document.getElementById('pbDob').textContent    = p.dob || '—';
  document.getElementById('pbGender').textContent = p.gender ? p.gender.charAt(0).toUpperCase()+p.gender.slice(1) : '—';
  document.getElementById('patientBox').style.display='block';
  document.getElementById('searchCode').value  = p.patient_code;
  document.getElementById('searchPhone').value = p.phone;
  document.getElementById('searchName').value  = p.name;

  // Advance balance
  const adv = parseFloat(p.advance_balance || 0);
  const advSec  = document.getElementById('advanceSection');
  const advWrap = document.getElementById('pbAdvanceWrap');
  if(adv > 0){
    document.getElementById('advBal').textContent = '৳' + adv.toLocaleString('en-BD', {minimumFractionDigits:2, maximumFractionDigits:2});
    document.getElementById('pbAdvance').textContent = '৳' + adv.toLocaleString('en-BD', {minimumFractionDigits:2, maximumFractionDigits:2});
    advSec.style.display  = 'block';
    advWrap.style.display = 'block';
  } else {
    advSec.style.display  = 'none';
    advWrap.style.display = 'none';
    document.getElementById('advanceChk').checked = false;
    document.getElementById('normalPayMethods').style.display = 'block';
    document.getElementById('advanceNote').style.display = 'none';
    const paidDiv = document.getElementById('paidAmtWrap');
    if(paidDiv) paidDiv.style.display = 'block';
  }
}

function toggleCreateAdvance(cb){
  const normal     = document.getElementById('normalPayMethods');
  const note       = document.getElementById('advanceNote');
  const paidWrap   = document.getElementById('paidAmtWrap');
  const metaBank   = document.getElementById('metaBank');
  const metaCard   = document.getElementById('metaCard');
  const metaMobile = document.getElementById('metaMobile');
  if(cb.checked){
    normal.style.display   = 'none';
    note.style.display     = 'block';
    if(paidWrap)   paidWrap.style.display   = 'none';
    if(metaBank)   metaBank.classList.add('d-none');
    if(metaCard)   metaCard.classList.add('d-none');
    if(metaMobile) metaMobile.classList.add('d-none');
    document.getElementById('paidAmt').value = '';
  } else {
    normal.style.display   = 'block';
    note.style.display     = 'none';
    if(paidWrap)   paidWrap.style.display   = 'block';
  }
}

function clearPatient(){
  document.getElementById('patientId').value='';
  document.getElementById('patientBox').style.display='none';
  ['searchCode','searchPhone','searchName'].forEach(id=>document.getElementById(id).value='');
  // reset advance
  document.getElementById('advanceSection').style.display = 'none';
  document.getElementById('pbAdvanceWrap').style.display  = 'none';
  document.getElementById('advanceChk').checked = false;
  document.getElementById('normalPayMethods').style.display = 'block';
  document.getElementById('paidAmtWrap').style.display = 'block';
  document.getElementById('advanceNote').style.display = 'none';
}

document.addEventListener('click', e=>{
  const sr = document.getElementById('searchResults');
  if(!sr.contains(e.target) && !['searchCode','searchPhone','searchName'].includes(e.target.id))
    sr.style.display='none';
});

// ---- Consultant change: show/hide "Add Fee" button ----
document.getElementById('consultantDd').addEventListener('change', function(){
  const hasFee = parseFloat(this.options[this.selectedIndex]?.dataset.fee||0) > 0;
  document.getElementById('addConsultFeeBtn').classList.toggle('d-none', !hasFee);
  recalc();
});

function addConsultFeeRow(){
  const dd  = document.getElementById('consultantDd');
  const fee = parseFloat(dd.options[dd.selectedIndex]?.dataset.fee)||0;
  if(!fee) return;
  // prevent duplicate
  if(document.querySelector('.consult-row')) return;
  const name = dd.options[dd.selectedIndex].text.split('(')[0].trim();
  addFixedRow('CONSULT', 'Consultation Fee — '+name, fee, true, 'consult-row');
  recalc();
}

document.getElementById('regFeeChk').addEventListener('change', function(){
  document.querySelectorAll('.reg-row').forEach(r=>r.remove());
  if(this.checked) addFixedRow('REG', 'Registration Fee (New Patient)', 200, false, 'reg-row');
  recalc();
});

let rowIdx = 0;

function addFixedRow(code, desc, rate, isConsult, cls='reg-row'){
  const tbody = document.getElementById('billRows');
  const tr = document.createElement('tr');
  tr.className = cls + ' table-purple-light';
  tr.innerHTML = `
    <td class="text-center">
      <button type="button" class="btn btn-sm btn-outline-danger py-0 px-1 remove-row" style="font-size:.65rem;" title="Remove"><i class="bi bi-x-lg"></i></button>
    </td>
    <td><span class="badge bg-primary-subtle text-primary" style="font-size:.7rem;">${code}</span></td>
    <td style="font-size:.82rem;">${desc}</td>
    <td class="text-center">1</td>
    <td class="text-end">${rate.toLocaleString('en-BD')}</td>
    <td class="text-end row-gross">${rate.toLocaleString('en-BD')}</td>
    <td class="text-end">—</td>
    <td class="text-end fw-600 text-primary row-net">${rate.toLocaleString('en-BD')}</td>`;
  tbody.appendChild(tr);
}

function addServiceRow(){
  const dd = document.getElementById('svcCodeDd');
  const opt = dd.options[dd.selectedIndex];
  if(!opt.value) return;
  const svc = SERVICES[opt.value];
  if(!svc) return;
  // check duplicate
  if(document.querySelector(`.svc-row[data-svc="${svc.id}"]`)){ dd.value=''; return; }
  const idx = rowIdx++;
  const tbody = document.getElementById('billRows');
  const tr = document.createElement('tr');
  tr.className = 'svc-row';
  tr.dataset.svc = svc.id;
  tr.innerHTML = `
    <td class="text-center"><button type="button" class="btn btn-sm btn-outline-danger py-0 px-1 remove-row" style="font-size:.65rem;"><i class="bi bi-x-lg"></i></button></td>
    <td><span class="badge bg-secondary" style="font-size:.7rem;">${svc.code}</span><input type="hidden" name="service_ids[]" value="${svc.id}"></td>
    <td style="font-size:.82rem;">${svc.name}</td>
    <td><input type="number" class="form-control form-control-sm text-center row-qty" value="1" min="1" style="width:55px;"></td>
    <td><input type="number" class="form-control form-control-sm text-end row-rate" value="${svc.rate}" min="0" step="0.01" style="width:85px;"></td>
    <td class="text-end fw-600 row-gross">${svc.rate.toLocaleString('en-BD')}</td>
    <td><input type="number" class="form-control form-control-sm text-end row-disc" value="0" min="0" step="0.01" style="width:70px;"></td>
    <td class="text-end fw-600 text-primary row-net">${svc.rate.toLocaleString('en-BD')}</td>`;
  tbody.appendChild(tr);
  dd.value='';
  recalc();
}

function addCustomRow(){
  const idx = rowIdx++;
  const tbody = document.getElementById('billRows');
  const tr = document.createElement('tr');
  tr.className = 'custom-row';
  tr.innerHTML = `
    <td class="text-center"><button type="button" class="btn btn-sm btn-outline-danger py-0 px-1 remove-row" style="font-size:.65rem;"><i class="bi bi-x-lg"></i></button></td>
    <td><span class="badge bg-secondary" style="font-size:.68rem;">CUSTOM</span></td>
    <td><input type="text" name="custom_desc[]" class="form-control form-control-sm" placeholder="Description" style="min-width:140px;"></td>
    <td><input type="number" name="custom_qty[]" class="form-control form-control-sm text-center row-qty" value="1" min="1" style="width:55px;"></td>
    <td><input type="number" name="custom_rate[]" class="form-control form-control-sm text-end row-rate" value="" min="0" step="0.01" style="width:85px;" placeholder="0.00"></td>
    <td class="text-end fw-600 row-gross">0.00</td>
    <td><input type="number" class="form-control form-control-sm text-end row-disc" value="0" min="0" step="0.01" style="width:70px;"></td>
    <td class="text-end fw-600 text-primary row-net">0.00</td>`;
  tbody.appendChild(tr);
}

// ---- Remove rows ----
document.addEventListener('click', e=>{
  if(e.target.closest('.remove-row')){
    const tr = e.target.closest('tr');
    // If removing reg-fee row, uncheck the checkbox too
    if(tr.classList.contains('reg-row')){
      const chk = document.getElementById('regFeeChk');
      if(chk) chk.checked = false;
    }
    tr.remove();
    recalc();
  }
});

// ---- Recalc ----
function recalc(){
  let gross=0, discTotal=0;
  // fixed rows (reg-row, consult-row)
  document.querySelectorAll('.reg-row, .consult-row').forEach(tr=>{
    gross += parseFloat(tr.querySelector('.row-gross')?.textContent.replace(/,/g,''))||0;
  });
  // service & custom rows
  document.querySelectorAll('.svc-row, .custom-row').forEach(tr=>{
    const qty  = parseFloat(tr.querySelector('.row-qty')?.value||1)||1;
    const rate = parseFloat(tr.querySelector('.row-rate')?.value||0)||0;
    const disc = parseFloat(tr.querySelector('.row-disc')?.value||0)||0;
    const g    = qty*rate;
    const net  = Math.max(0, g-disc);
    tr.querySelector('.row-gross').textContent = g.toLocaleString('en-BD',{minimumFractionDigits:2,maximumFractionDigits:2});
    tr.querySelector('.row-net').textContent   = net.toLocaleString('en-BD',{minimumFractionDigits:2,maximumFractionDigits:2});
    gross += g;
    discTotal += disc;
  });
  // overall discount
  const overallDisc = parseFloat(document.getElementById('discountInp').value||0)||0;
  const net = Math.max(0, gross - overallDisc);
  document.getElementById('footGross').textContent = gross.toLocaleString('en-BD',{minimumFractionDigits:2,maximumFractionDigits:2});
  document.getElementById('footNet').textContent   = net.toLocaleString('en-BD',{minimumFractionDigits:2,maximumFractionDigits:2});
  document.getElementById('footTotal').textContent = '৳ '+net.toLocaleString('en-BD',{minimumFractionDigits:2,maximumFractionDigits:2});
  document.getElementById('footNetVal').value = net.toFixed(2);
}

document.addEventListener('input', e=>{
  if(e.target.classList.contains('row-qty')||e.target.classList.contains('row-rate')||e.target.classList.contains('row-disc')) recalc();
});
document.getElementById('discountInp').addEventListener('input', recalc);

// ---- Payment method toggle ----
function onPayMethod(method){
  document.querySelectorAll('.pay-btn').forEach(b=>b.classList.remove('active'));
  event.currentTarget.classList.add('active');
  event.currentTarget.querySelector('input[type=radio]').checked=true;

  const all = ['metaBank','metaCard','metaMobile','metaCash'];
  all.forEach(id=>{ const el=document.getElementById(id); if(el) el.classList.add('d-none'); });

  if(method==='bank')                              document.getElementById('metaBank').classList.remove('d-none');
  else if(method==='card')                         document.getElementById('metaCard').classList.remove('d-none');
  else if(['bkash','nagad','rocket'].includes(method)) document.getElementById('metaMobile').classList.remove('d-none');
  else                                             document.getElementById('metaCash').classList.remove('d-none');
}

// ---- Print on confirm ----
document.getElementById('billForm').addEventListener('submit', function(){
  if(document.getElementById('printChk').checked){
    localStorage.setItem('printAfterBill','1');
  }
});
</script>
@endpush
