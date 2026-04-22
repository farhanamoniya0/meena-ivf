@extends('layouts.app')
@section('title','Bill — '.$bill->bill_no)
@section('page-title','Bill Detail')
@push('styles')
<style>
.bill-font { font-family: Arial, Helvetica, sans-serif; }
.bill-th   { background:#f0f0f0; font-weight:bold; padding:5px 6px; border:1px solid #333; font-size:11.5px; }
.bill-td   { padding:4px 6px; border:1px solid #bbb; font-size:11.5px; vertical-align:middle; }
.bill-label{ font-weight:bold; white-space:nowrap; }
.bill-sum-label { border:1px solid #555; font-weight:bold; padding:4px 8px; font-size:11.5px; background:#f8f8f8; }
.bill-sum-val   { border:1px solid #555; text-align:right; padding:4px 8px; font-size:11.5px; }
@media print {
  @page { size: A4 portrait; margin: 10mm; }
  body  { font-size: 11px !important; background:#fff !important; }
  #sidebar, #topbar, .no-print { display:none !important; }
  #main-content { margin:0 !important; padding:0 !important; }
  #billCard { box-shadow:none !important; border:none !important; max-width:100% !important; }
  .bill-wrap { padding:0 !important; }
  a { text-decoration:none !important; color:inherit !important; }
  .bill-th { -webkit-print-color-adjust:exact; print-color-adjust:exact; }
}
</style>
@endpush
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2 no-print">
  <div>
    <h5 class="fw-700 mb-0">{{ $bill->bill_no }}</h5>
    <small class="text-muted">{{ $bill->bill_date->format('d M Y') }}</small>
  </div>
  <div class="d-flex gap-2">
    @if($bill->status !== 'paid' && $bill->status !== 'cancelled')
    <button class="btn btn-sm btn-success no-print" data-bs-toggle="modal" data-bs-target="#payModal"><i class="bi bi-cash-coin me-1"></i>Collect Payment</button>
    @endif
    <button class="btn btn-sm btn-outline-secondary no-print" onclick="window.print()"><i class="bi bi-printer me-1"></i>Print</button>
    <button class="btn btn-sm btn-outline-danger no-print" onclick="downloadPDF()"><i class="bi bi-file-earmark-pdf me-1"></i>PDF</button>
    <a href="{{ route('bills.index') }}" class="btn btn-sm btn-light no-print">Back</a>
  </div>
</div>

@if(session('success'))
<div class="alert alert-success no-print">{{ session('success') }}</div>
@endif

{{-- Bill Card --}}
<div id="billCard" class="bill-font" style="max-width:800px;margin:0 auto;background:#fff;color:#000;border:1px solid #ccc;">

  {{-- ═══ CLINIC HEADER ═══ --}}
  <div style="border-top:3px solid #000;border-bottom:2px solid #000;padding:10px 14px;">
    <table width="100%" cellpadding="0" cellspacing="0">
      <tr>
        <td width="110" style="vertical-align:middle;">
          <img src="{{ asset('images/meena-logo.png') }}" alt="Meena IVF" style="height:70px;width:auto;">
        </td>
        <td style="text-align:center;vertical-align:middle;">
          <div style="font-size:16px;font-weight:bold;letter-spacing:.3px;">Meena IVF &amp; Fertility Care Limited</div>
          <div style="font-size:11.5px;margin-top:3px;">Block - K, Road - 22, House - 11, Banani</div>
          <div style="font-size:11.5px;">DHAKA, BANGLADESH - 1213</div>
          <div style="font-size:11.5px;">Mob No. : 9611678979 &nbsp;&nbsp; Phone No. : 131825507</div>
          <div style="font-size:11.5px;">Email : meenaivffertility@gmail.com &nbsp;&nbsp; BIN: 007356909-0101</div>
        </td>
        <td width="110" style="text-align:right;vertical-align:middle;">
          <span class="badge no-print {{ ['draft'=>'bg-secondary','partial'=>'bg-warning text-dark','paid'=>'bg-success','cancelled'=>'bg-danger'][$bill->status] ?? 'bg-secondary' }}" style="font-size:11px;">{{ strtoupper($bill->status) }}</span>
        </td>
      </tr>
    </table>
  </div>

  {{-- ═══ BILL TYPE TITLE ═══ --}}
  <div style="text-align:center;border-bottom:1px solid #555;padding:5px 0;font-weight:bold;font-size:13px;background:#f0f0f0;letter-spacing:1px;">OP BILL</div>

  {{-- ═══ PATIENT & BILL INFO ═══ --}}
  <div style="border-bottom:1px solid #555;padding:8px 14px;">
    <table width="100%" cellpadding="3" cellspacing="0">
      <tr>
        <td width="50%" style="vertical-align:top;">
          <table cellpadding="2" cellspacing="0">
            <tr>
              <td class="bill-label" style="width:115px;">UHID</td>
              <td>: <strong>{{ $bill->patient->patient_code }}</strong></td>
            </tr>
            <tr>
              <td class="bill-label">Patient Name</td>
              <td>: {{ $bill->patient->name }}</td>
            </tr>
            <tr>
              <td class="bill-label">Age / Gender</td>
              <td>: {{ $bill->patient->age ? $bill->patient->age.' Year(s)' : '—' }} / {{ ucfirst($bill->patient->gender ?? '—') }}</td>
            </tr>
            @if($bill->patient->phone)
            <tr>
              <td class="bill-label">Mobile No.</td>
              <td>: {{ $bill->patient->phone }}</td>
            </tr>
            @endif
            @if($bill->patient->address)
            <tr>
              <td class="bill-label">Address</td>
              <td style="font-size:11px;">: {{ $bill->patient->address }}{{ $bill->patient->thana ? ', '.$bill->patient->thana : '' }}{{ $bill->patient->district ? ', '.$bill->patient->district : '' }}</td>
            </tr>
            @endif
          </table>
        </td>
        <td width="50%" style="vertical-align:top;">
          <table cellpadding="2" cellspacing="0">
            <tr>
              <td class="bill-label" style="width:130px;">Bill No.</td>
              <td>: {{ $bill->bill_no }}</td>
            </tr>
            <tr>
              <td class="bill-label">Bill Date &amp; Time</td>
              <td>: {{ $bill->bill_date->format('d/m/Y') }} {{ $bill->created_at->format('g:i A') }}</td>
            </tr>
            <tr>
              <td class="bill-label">Referred By</td>
              <td>: {{ $bill->patient->referred_by ?: ($bill->patient->source_type ?: '—') }}</td>
            </tr>
            @if($bill->consultant)
            <tr>
              <td class="bill-label">Doctor</td>
              <td>: Dr. {{ $bill->consultant->name }} ({{ $bill->consultant->specialty }})</td>
            </tr>
            @endif
            @if($bill->notes)
            <tr>
              <td class="bill-label">Notes</td>
              <td style="font-size:11px;">: {{ $bill->notes }}</td>
            </tr>
            @endif
          </table>
        </td>
      </tr>
    </table>
  </div>

  {{-- ═══ SERVICE TABLE ═══ --}}
  @php $grossTotal = $bill->items->sum(fn($i)=>$i->unit_rate * $i->quantity); @endphp
  <table width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;">
    <thead>
      <tr>
        <th class="bill-th" style="text-align:center;width:42px;">Sl. No.</th>
        <th class="bill-th" style="text-align:center;width:80px;">Code</th>
        <th class="bill-th" style="text-align:left;">Service Name</th>
        <th class="bill-th" style="text-align:center;width:52px;">Qty.</th>
        <th class="bill-th" style="text-align:right;width:110px;">Gross Amt.</th>
        <th class="bill-th" style="text-align:right;width:95px;">Disc. Amt.</th>
        <th class="bill-th" style="text-align:right;width:110px;">Net Amt.</th>
      </tr>
    </thead>
    <tbody>
      @foreach($bill->items as $i => $item)
      <tr>
        <td class="bill-td" style="text-align:center;">{{ $i+1 }}</td>
        <td class="bill-td" style="text-align:center;">{{ $item->service?->service_code ?? 'MISC' }}</td>
        <td class="bill-td">{{ $item->description }}</td>
        <td class="bill-td" style="text-align:center;">{{ number_format($item->quantity, 2) }}</td>
        <td class="bill-td" style="text-align:right;">{{ number_format($item->unit_rate * $item->quantity, 2) }}</td>
        <td class="bill-td" style="text-align:right;">{{ number_format($item->discount ?? 0, 2) }}</td>
        <td class="bill-td" style="text-align:right;font-weight:bold;">{{ number_format($item->amount, 2) }}</td>
      </tr>
      @endforeach
      {{-- Blank filler rows for short bills --}}
      @for($r = $bill->items->count(); $r < 3; $r++)
      <tr>
        <td class="bill-td" style="height:22px;"></td>
        <td class="bill-td"></td><td class="bill-td"></td>
        <td class="bill-td"></td><td class="bill-td"></td>
        <td class="bill-td"></td><td class="bill-td"></td>
      </tr>
      @endfor
    </tbody>
    <tfoot>
      {{-- Subtotal row --}}
      <tr>
        <td colspan="4" style="border:none;"></td>
        <td style="border:1px solid #555;text-align:right;font-weight:bold;padding:4px 8px;font-size:11.5px;">{{ number_format($grossTotal, 2) }}</td>
        <td style="border:1px solid #555;padding:4px 8px;font-size:11.5px;"></td>
        <td style="border:1px solid #555;text-align:right;font-weight:bold;padding:4px 8px;font-size:11.5px;">{{ number_format($bill->net_total, 2) }}</td>
      </tr>
      {{-- Total Amount --}}
      <tr>
        <td colspan="5" style="border:none;"></td>
        <td class="bill-sum-label">Total Amount (BDT)</td>
        <td class="bill-sum-val"><strong>{{ number_format($bill->net_total, 2) }}</strong></td>
      </tr>
      {{-- Discount --}}
      <tr>
        <td colspan="5" style="border:none;"></td>
        <td class="bill-sum-label">Discount Amount (BDT)</td>
        <td class="bill-sum-val">{{ number_format($bill->discount, 2) }}</td>
      </tr>
      {{-- Paid --}}
      <tr>
        <td colspan="5" style="border:none;"></td>
        <td class="bill-sum-label" style="font-weight:bold;background:#e8f5e9;">Patient Paid Amount (BDT)</td>
        <td class="bill-sum-val" style="font-weight:bold;background:#e8f5e9;">{{ number_format($bill->paid_amount, 2) }}</td>
      </tr>
      @if($bill->balance > 0)
      <tr>
        <td colspan="5" style="border:none;"></td>
        <td class="bill-sum-label" style="background:#fef2f2;">Balance Due (BDT)</td>
        <td class="bill-sum-val" style="color:#c00;font-weight:bold;background:#fef2f2;">{{ number_format($bill->balance, 2) }}</td>
      </tr>
      @endif
    </tfoot>
  </table>

  {{-- ═══ ADVANCE PAYMENT NOTICE ═══ --}}
  @if($bill->payment_method === 'advance')
  <div style="border-top:1px solid #555;background:#e8f5e9;padding:6px 14px;font-size:11.5px;">
    <strong><i class="bi bi-check-circle-fill text-success me-1"></i>Paid via Advance Credit Balance</strong>
    — ৳{{ number_format($bill->paid_amount, 2) }} deducted from patient's advance balance.
  </div>
  @endif

  {{-- ═══ FOOTER ═══ --}}
  <div style="border-top:1px solid #555;padding:8px 14px;">
    <table width="100%" cellpadding="3" cellspacing="0">
      <tr>
        <td width="60%" style="vertical-align:top;font-size:11.5px;">
          <div><span class="bill-label">Receipt No.</span> : {{ $bill->transaction_id ?? '—' }}</div>
          <div style="margin-top:4px;"><span class="bill-label">Received Amount</span> : {{ \App\Models\Bill::amountInWords((float)$bill->paid_amount) }}</div>
          <div style="margin-top:4px;"><span class="bill-label">Print Date &amp; Time</span> : <span id="printDateTime">{{ now()->format('d/m/Y h:i A') }}</span></div>
        </td>
        <td width="40%" style="vertical-align:top;text-align:right;font-size:11.5px;">
          <div><span class="bill-label">Signature</span> : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
          <div style="margin-top:18px;"><span class="bill-label">Prepared By</span> : {{ $bill->createdBy?->name ?? 'System' }}</div>
        </td>
      </tr>
    </table>
  </div>

  {{-- Computer generated note --}}
  <div style="text-align:center;border-top:1px solid #ddd;padding:4px;font-size:10px;color:#666;">This is a computer-generated bill. No signature is required.</div>

</div>

{{-- Payment modal --}}
<div class="modal fade no-print" id="payModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header" style="background:#f3e8ff;">
        <h6 class="modal-title fw-700"><i class="bi bi-cash-coin text-success me-2"></i>Collect Payment — {{ $bill->bill_no }}</h6>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form method="POST" action="{{ route('bills.pay', $bill) }}">
      @csrf
      <div class="modal-body">
        <div class="d-flex justify-content-between mb-2">
          <span class="text-muted">Net Total</span><span class="fw-700">৳{{ number_format($bill->net_total) }}</span>
        </div>
        @if($bill->paid_amount > 0)
        <div class="d-flex justify-content-between mb-2">
          <span class="text-muted">Previously Paid</span><span class="text-success fw-700">৳{{ number_format($bill->paid_amount) }}</span>
        </div>
        @endif
        <div class="d-flex justify-content-between mb-2">
          <span class="fw-700">Balance Due</span><span class="text-danger fw-700">৳{{ number_format($bill->balance) }}</span>
        </div>

        @if($bill->patient->advance_balance > 0)
        <div class="alert alert-info py-2 mb-3" style="font-size:.83rem;">
          <i class="bi bi-piggy-bank me-1"></i>
          Patient has <strong>৳{{ number_format($bill->patient->advance_balance, 2) }}</strong> advance credit available.
        </div>
        <div class="form-check mb-3 p-3 rounded" style="background:#f0f4ff;border:1px solid #c7d7ff;">
          <input class="form-check-input" type="checkbox" name="adjust_from_advance" id="advanceCheck" value="1" onchange="toggleAdvanceMode(this)">
          <label class="form-check-label fw-600" for="advanceCheck">
            <i class="bi bi-arrows-angle-contract me-1 text-primary"></i>
            Adjust from Advance Balance (৳{{ number_format(min($bill->balance, $bill->patient->advance_balance), 2) }} will be deducted)
          </label>
        </div>
        @endif

        <hr>
        <div id="normalPaySection">
        <div class="mb-3">
          <label class="form-label">Amount to Collect (৳) *</label>
          <input type="number" name="paid_amount" class="form-control" value="{{ $bill->balance }}" min="0.01" step="0.01">
        </div>
        <div class="mb-2">
          <label class="form-label">Payment Method *</label>
          <div class="d-flex gap-1 flex-wrap">
            @foreach(['cash'=>'Cash','bank'=>'Bank','card'=>'Card','bkash'=>'bKash','nagad'=>'Nagad','rocket'=>'Rocket'] as $val=>$lbl)
            <label class="pay-btn {{ $val=='cash'?'active':'' }}" onclick="onModalPayMethod('{{ $val }}',event)">
              <input type="radio" name="payment_method" value="{{ $val }}" style="display:none;" class="pmeth" {{ $val=='cash'?'checked':'' }}> {{ $lbl }}
            </label>
            @endforeach
          </div>
        </div>
        </div>{{-- end normalPaySection --}}

        {{-- Bank fields --}}
        <div id="mBank" class="d-none mt-2">
          <div class="row g-2">
            <div class="col-md-12">
              <input type="text" name="meta_bank_name" class="form-control form-control-sm" placeholder="Type bank name..." list="bdBankListM" autocomplete="off">
              <datalist id="bdBankListM">
                <option value="Sonali Bank PLC"><option value="Janata Bank PLC"><option value="Agrani Bank PLC">
                <option value="Rupali Bank PLC"><option value="Bangladesh Development Bank Limited (BDBL)"><option value="Basic Bank Limited">
                <option value="AB Bank PLC"><option value="Al-Arafah Islami Bank PLC"><option value="Bank Asia PLC">
                <option value="BRAC Bank PLC"><option value="City Bank PLC"><option value="Dhaka Bank PLC">
                <option value="Dutch-Bangla Bank PLC"><option value="Eastern Bank PLC"><option value="EXIM Bank PLC">
                <option value="First Security Islami Bank PLC"><option value="Global Islami Bank PLC"><option value="ICB Islamic Bank Limited">
                <option value="IFIC Bank PLC"><option value="Islami Bank Bangladesh PLC"><option value="Jamuna Bank PLC">
                <option value="Meghna Bank PLC"><option value="Mercantile Bank PLC"><option value="Midland Bank PLC">
                <option value="Modhumoti Bank PLC"><option value="Mutual Trust Bank PLC"><option value="National Bank Limited">
                <option value="NCC Bank PLC"><option value="One Bank PLC"><option value="Padma Bank PLC">
                <option value="Premier Bank PLC"><option value="Prime Bank PLC"><option value="Pubali Bank PLC">
                <option value="Shahjalal Islami Bank PLC"><option value="Social Islami Bank PLC"><option value="Southeast Bank PLC">
                <option value="Standard Bank PLC"><option value="Trust Bank PLC"><option value="Union Bank PLC">
                <option value="United Commercial Bank PLC (UCB)"><option value="Uttara Bank PLC">
                <option value="Community Bank Bangladesh PLC"><option value="Bengal Commercial Bank PLC"><option value="Citizens Bank PLC">
                <option value="Standard Chartered Bank Bangladesh"><option value="HSBC Bangladesh"><option value="Citibank N.A. Bangladesh">
                <option value="State Bank of India Bangladesh"><option value="Habib Bank Limited Bangladesh">
                <option value="Commercial Bank of Ceylon PLC Bangladesh"><option value="Woori Bank Bangladesh">
                <option value="Bangladesh Krishi Bank (BKB)"><option value="Rajshahi Krishi Unnayan Bank (RAKUB)">
                <option value="Bangladesh Samabay Bank Limited"><option value="Karma Sangsthan Bank">
                <option value="Palli Sanchay Bank"><option value="Probashi Kallyan Bank"><option value="Ansar VDP Unnayan Bank">
              </datalist>
            </div>
            <div class="col-md-6"><input type="text" name="meta_account_holder" class="form-control form-control-sm" placeholder="Account Holder Name"></div>
            <div class="col-md-6"><input type="text" name="meta_account_number" class="form-control form-control-sm" placeholder="Account / Cheque No."></div>
          </div>
        </div>
        {{-- Card fields --}}
        <div id="mCard" class="d-none mt-2">
          <div class="row g-2">
            <div class="col-md-4">
              <select name="meta_card_type" class="form-select form-select-sm">
                <option value="">Card Type</option>
                <option value="visa">Visa</option>
                <option value="mastercard">Mastercard</option>
                <option value="amex">Amex</option>
                <option value="other">Other</option>
              </select>
            </div>
            <div class="col-md-4"><input type="text" name="meta_card_number" class="form-control form-control-sm" placeholder="Last 4 digits" maxlength="4"></div>
            <div class="col-md-4"><input type="text" name="meta_card_holder" class="form-control form-control-sm" placeholder="Card Holder Name"></div>
          </div>
        </div>
        {{-- Mobile banking fields --}}
        <div id="mMobile" class="d-none mt-2">
          <div class="row g-2">
            <div class="col-md-6"><input type="text" name="transaction_id" class="form-control form-control-sm" placeholder="Transaction ID"></div>
            <div class="col-md-6"><input type="text" name="meta_mobile_number" class="form-control form-control-sm" placeholder="Mobile Number"></div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-success fw-600"><i class="bi bi-check-lg me-2"></i>Record Payment</button>
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
      </div>
      </form>
    </div>
  </div>
</div>
@endsection
@push('styles')
<style>
.pay-btn{border:2px solid #dee2e6;border-radius:6px;padding:4px 10px;cursor:pointer;font-size:.78rem;background:#fff;transition:all .15s;display:inline-block;}
.pay-btn.active,.pay-btn:hover{border-color:#7c3aed;background:#f3e8ff;color:#5b21b6;font-weight:600;}
</style>
@endpush
@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js" defer></script>
<script>
// Auto-print: triggered via ?print=1 query param (from registration popup)
if(new URLSearchParams(window.location.search).get('print')==='1'){
  window.addEventListener('load', ()=>setTimeout(()=>window.print(), 900));
}

function downloadPDF(){
  const el  = document.getElementById('billCard');
  const run = () => html2pdf().set({
    margin: 5,
    filename: '{{ $bill->bill_no }}.pdf',
    image: {type:'jpeg', quality:0.98},
    html2canvas: {scale:2, useCORS:true},
    jsPDF: {unit:'mm', format:'a4', orientation:'portrait'}
  }).from(el).save();

  if(typeof html2pdf !== 'undefined'){ run(); return; }
  // wait up to 3s for deferred script
  let tries = 0;
  const t = setInterval(()=>{ if(typeof html2pdf !== 'undefined'){ clearInterval(t); run(); } else if(++tries>30){ clearInterval(t); alert('PDF library failed to load.'); } }, 100);
}

function toggleAdvanceMode(cb){
  const sec = document.getElementById('normalPaySection');
  if(!sec) return;
  if(cb.checked){
    sec.style.display = 'none';
    sec.querySelectorAll('input,select').forEach(el=>el.removeAttribute('required'));
  } else {
    sec.style.display = '';
    sec.querySelector('input[name=paid_amount]')?.setAttribute('required','required');
  }
}

function onModalPayMethod(method, e){
  document.querySelectorAll('#payModal .pay-btn').forEach(b=>b.classList.remove('active'));
  e.currentTarget.classList.add('active');
  e.currentTarget.querySelector('input[type=radio]').checked=true;
  ['mBank','mCard','mMobile'].forEach(id=>document.getElementById(id).classList.add('d-none'));
  if(method==='bank')                                  document.getElementById('mBank').classList.remove('d-none');
  else if(method==='card')                             document.getElementById('mCard').classList.remove('d-none');
  else if(['bkash','nagad','rocket'].includes(method)) document.getElementById('mMobile').classList.remove('d-none');
}
</script>
@endpush
