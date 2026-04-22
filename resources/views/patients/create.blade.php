@extends('layouts.app')
@section('title','New Patient Registration')
@section('page-title','New Patient Registration')
@push('styles')
<style>
.reg-card{border-radius:12px;box-shadow:0 2px 12px rgba(0,0,0,.07);margin-bottom:1.25rem;overflow:hidden;}
.reg-card-header{background:linear-gradient(135deg,#5b21b6,#7c3aed);color:#fff;padding:10px 18px;font-weight:700;font-size:.88rem;display:flex;align-items:center;gap:8px;}
.reg-card-header i{font-size:1rem;}
.reg-card-body{padding:16px 18px;background:#fff;}
.form-label{font-size:.76rem;font-weight:600;color:#374151;margin-bottom:3px;}
.form-control,.form-select{font-size:.83rem;border-radius:6px;}
.form-control:focus,.form-select:focus{border-color:#7c3aed;box-shadow:0 0 0 .2rem rgba(124,58,237,.15);}
.section-divider{border:none;border-top:2px dashed #e9d5ff;margin:12px 0;}
.bill-table thead th{background:#f3e8ff;font-size:.72rem;padding:6px 8px;color:#374151;}
.bill-table tbody td{padding:4px 6px;vertical-align:middle;font-size:.8rem;}
.bill-table tfoot td{padding:5px 8px;font-size:.82rem;background:#faf5ff;}
.pay-btn{border:2px solid #dee2e6;border-radius:6px;padding:4px 10px;cursor:pointer;font-size:.78rem;background:#fff;transition:all .15s;display:inline-block;}
.pay-btn.active,.pay-btn:hover{border-color:#7c3aed;background:#f3e8ff;color:#5b21b6;font-weight:600;}
.age-badge{background:#f3e8ff;color:#5b21b6;border-radius:6px;padding:4px 10px;font-size:.78rem;font-weight:600;}
</style>
@endpush
@section('content')
<form method="POST" action="{{ route('patients.store') }}" id="regForm">
@csrf

{{-- ═══════════════════════════════════════ PATIENT DETAILS ═══════════════════════════════ --}}
<div class="reg-card">
  <div class="reg-card-header"><i class="bi bi-person-fill"></i> Patient Details</div>
  <div class="reg-card-body">
    <div class="row g-2">

      {{-- Photo --}}
      <div class="col-md-2 d-flex flex-column align-items-center justify-content-start">
        <div id="photoPreview" style="width:90px;height:110px;border:2px dashed #c4b5fd;border-radius:8px;background:#faf5ff;display:flex;align-items:center;justify-content:center;overflow:hidden;cursor:pointer;" onclick="document.getElementById('photoFile').click()">
          <i class="bi bi-person-bounding-box text-muted" style="font-size:2rem;"></i>
        </div>
        <input type="file" id="photoFile" accept="image/*" style="display:none;" onchange="previewFile(this)">
        <input type="hidden" name="photo_data" id="photoData">
        <button type="button" class="btn btn-sm btn-outline-primary mt-1 px-2 py-0" style="font-size:.72rem;" onclick="document.getElementById('photoFile').click()">
          <i class="bi bi-upload me-1"></i>Upload
        </button>
        @include('partials.camera-modal')
        <button type="button" class="btn btn-sm btn-outline-secondary mt-1 px-2 py-0" style="font-size:.72rem;" onclick="openCamera()">
          <i class="bi bi-camera me-1"></i>Camera
        </button>
      </div>

      {{-- Name + Core Info --}}
      <div class="col-md-10">
        <div class="row g-2">
          <div class="col-md-3">
            <label class="form-label">First Name <span class="text-danger">*</span></label>
            <input type="text" name="first_name" class="form-control form-control-sm" value="{{ old('first_name') }}" required placeholder="First name">
          </div>
          <div class="col-md-3">
            <label class="form-label">Last Name</label>
            <input type="text" name="last_name" class="form-control form-control-sm" value="{{ old('last_name') }}" placeholder="Last name">
          </div>
          <div class="col-md-2">
            <label class="form-label">Date of Birth</label>
            <input type="date" name="dob" id="patDob" class="form-control form-control-sm" value="{{ old('dob') }}" onchange="calcAge('patDob','patAgeY','patAgeM','patAgeD','patAge')">
          </div>
          <div class="col-md-3">
            <label class="form-label">Age</label>
            <div class="d-flex gap-1 align-items-center">
              <input type="number" name="age" id="patAge" class="form-control form-control-sm" value="{{ old('age') }}" placeholder="Yrs" style="width:60px;" min="0" max="120">
              <span id="patAgeBadge" class="age-badge d-none" style="white-space:nowrap;"></span>
            </div>
          </div>
          <div class="col-md-2">
            <label class="form-label">Gender <span class="text-danger">*</span></label>
            <select name="gender" class="form-select form-select-sm" required>
              <option value="female" {{ old('gender','female')=='female'?'selected':'' }}>Female</option>
              <option value="male"   {{ old('gender')=='male'?'selected':'' }}>Male</option>
              <option value="other"  {{ old('gender')=='other'?'selected':'' }}>Other</option>
            </select>
          </div>
          <div class="col-md-3">
            <label class="form-label">Marital Status</label>
            <select name="marital_status" class="form-select form-select-sm">
              <option value="">— Select —</option>
              @foreach(['Married','Single','Divorced','Widow','Widower'] as $ms)
              <option value="{{ $ms }}" {{ old('marital_status')==$ms?'selected':'' }}>{{ $ms }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-md-3">
            <label class="form-label">Occupation</label>
            <input type="text" name="occupation" class="form-control form-control-sm" value="{{ old('occupation') }}" list="occupationList" autocomplete="off" placeholder="Type or select...">
          </div>
          <div class="col-md-3">
            <label class="form-label">Mobile Number <span class="text-danger">*</span></label>
            <input type="text" name="phone" class="form-control form-control-sm" value="{{ old('phone') }}" required placeholder="01XXXXXXXXX">
          </div>
          <div class="col-md-3">
            <label class="form-label">Source Type</label>
            <select name="source_type" class="form-select form-select-sm">
              <option value="">— Select —</option>
              @foreach(['Camp Event','Media','Online','Qualified Leads','Referral'] as $st)
              <option value="{{ $st }}" {{ old('source_type')==$st?'selected':'' }}>{{ $st }}</option>
              @endforeach
            </select>
          </div>
        </div>

        <hr class="section-divider">

        {{-- Address --}}
        <div class="row g-2">
          <div class="col-md-4">
            <label class="form-label">Address</label>
            <input type="text" name="address" class="form-control form-control-sm" value="{{ old('address') }}" placeholder="Street / Area / Village">
          </div>
          <div class="col-md-2">
            <label class="form-label">Division</label>
            <select name="division" id="patDivision" class="form-select form-select-sm" onchange="fillDistricts('patDivision','patDistrict','patThana')">
              <option value="">— Select —</option>
              @foreach(['Dhaka','Chattogram','Rajshahi','Khulna','Barishal','Sylhet','Rangpur','Mymensingh'] as $div)
              <option value="{{ $div }}" {{ old('division')==$div?'selected':'' }}>{{ $div }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-md-2">
            <label class="form-label">District</label>
            <select name="district" id="patDistrict" class="form-select form-select-sm" onchange="fillThanas('patDistrict','patThana','patPostCode')">
              <option value="">— Select —</option>
            </select>
          </div>
          <div class="col-md-2">
            <label class="form-label">Thana / Upazila</label>
            <select name="thana" id="patThana" class="form-select form-select-sm" onchange="fillPostCode('patThana','patPostCode')">
              <option value="">— Select —</option>
            </select>
          </div>
          <div class="col-md-2">
            <label class="form-label">Post Code</label>
            <input type="text" name="post_code" id="patPostCode" class="form-control form-control-sm" value="{{ old('post_code') }}" placeholder="e.g. 1212">
          </div>
        </div>

        <hr class="section-divider">

        {{-- Vital Signs --}}
        <div class="row g-2">
          <div class="col-md-2">
            <label class="form-label">Height (cm)</label>
            <input type="number" name="height_cm" class="form-control form-control-sm" value="{{ old('height_cm') }}" placeholder="e.g. 162" min="50" max="250" step="0.1">
          </div>
          <div class="col-md-2">
            <label class="form-label">Weight (kg)</label>
            <input type="number" name="weight_kg" class="form-control form-control-sm" value="{{ old('weight_kg') }}" placeholder="e.g. 58" min="1" max="300" step="0.1">
          </div>
          <div class="col-md-2">
            <label class="form-label">Blood Group</label>
            <select name="blood_group" class="form-select form-select-sm">
              <option value="">— Select —</option>
              @foreach(['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $bg)
              <option value="{{ $bg }}" {{ old('blood_group')==$bg?'selected':'' }}>{{ $bg }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-md-3">
            <label class="form-label">NID Number</label>
            <input type="text" name="nid_number" class="form-control form-control-sm" value="{{ old('nid_number') }}" placeholder="National ID">
          </div>
          <div class="col-md-3">
            <label class="form-label">Notes</label>
            <input type="text" name="notes" class="form-control form-control-sm" value="{{ old('notes') }}" placeholder="Any notes...">
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

{{-- ═══════════════════════════════════════ PARTNER DETAILS ════════════════════════════════ --}}
<div class="reg-card">
  <div class="reg-card-header" style="cursor:pointer;" onclick="toggleSection('partnerBody',this)">
    <i class="bi bi-people-fill"></i> Partner Details
    <span class="ms-auto"><i class="bi bi-chevron-down" id="partnerChevron"></i></span>
  </div>
  <div id="partnerBody" class="reg-card-body" style="display:none;">
    <div class="row g-2">

      {{-- Partner Photo --}}
      <div class="col-md-2 d-flex flex-column align-items-center justify-content-start">
        <div id="partnerPhotoPreview" style="width:90px;height:110px;border:2px dashed #c4b5fd;border-radius:8px;background:#faf5ff;display:flex;align-items:center;justify-content:center;overflow:hidden;cursor:pointer;" onclick="document.getElementById('partnerPhotoFile').click()">
          <i class="bi bi-person-bounding-box text-muted" style="font-size:2rem;"></i>
        </div>
        <input type="file" id="partnerPhotoFile" accept="image/*" style="display:none;" onchange="previewPartnerFile(this)">
        <input type="hidden" name="partner_photo_data" id="partnerPhotoData">
        <button type="button" class="btn btn-sm btn-outline-primary mt-1 px-2 py-0" style="font-size:.72rem;" onclick="document.getElementById('partnerPhotoFile').click()">
          <i class="bi bi-upload me-1"></i>Upload
        </button>
      </div>

      <div class="col-md-10">
      <div class="row g-2">
      <div class="col-md-3">
        <label class="form-label">First Name</label>
        <input type="text" name="partner_first_name" class="form-control form-control-sm" placeholder="Partner first name">
      </div>
      <div class="col-md-3">
        <label class="form-label">Last Name</label>
        <input type="text" name="partner_last_name" class="form-control form-control-sm" placeholder="Partner last name">
      </div>
      <div class="col-md-2">
        <label class="form-label">Date of Birth</label>
        <input type="date" name="partner_dob" id="partDob" class="form-control form-control-sm" onchange="calcAge('partDob','partAgeY','partAgeM','partAgeD','partAge')">
      </div>
      <div class="col-md-2">
        <label class="form-label">Age</label>
        <div class="d-flex gap-1 align-items-center">
          <input type="number" name="partner_age" id="partAge" class="form-control form-control-sm" placeholder="Yrs" style="width:60px;" min="0" max="120">
          <span id="partAgeBadge" class="age-badge d-none" style="white-space:nowrap;"></span>
        </div>
      </div>
      <div class="col-md-2">
        <label class="form-label">Gender</label>
        <select name="partner_gender" class="form-select form-select-sm">
          <option value="male">Male</option>
          <option value="female">Female</option>
          <option value="other">Other</option>
        </select>
      </div>
      <div class="col-md-3">
        <label class="form-label">Marital Status</label>
        <select name="partner_marital_status" class="form-select form-select-sm">
          <option value="">— Select —</option>
          @foreach(['Married','Single','Divorced','Widow','Widower'] as $ms)
          <option value="{{ $ms }}">{{ $ms }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-3">
        <label class="form-label">Occupation</label>
        <input type="text" name="partner_occupation" class="form-control form-control-sm" list="occupationList" autocomplete="off" placeholder="Type or select...">
      </div>
      <div class="col-md-3">
        <label class="form-label">Mobile Number</label>
        <input type="text" name="partner_phone" class="form-control form-control-sm" placeholder="01XXXXXXXXX">
      </div>
      <div class="col-md-3">
        <label class="form-label">Blood Group</label>
        <select name="partner_blood_group" class="form-select form-select-sm">
          <option value="">— Select —</option>
          @foreach(['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $bg)
          <option value="{{ $bg }}">{{ $bg }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-2">
        <label class="form-label">Height (cm)</label>
        <input type="number" name="partner_height_cm" class="form-control form-control-sm" placeholder="162" step="0.1">
      </div>
      <div class="col-md-2">
        <label class="form-label">Weight (kg)</label>
        <input type="number" name="partner_weight_kg" class="form-control form-control-sm" placeholder="70" step="0.1">
      </div>

      <hr class="section-divider">

      <div class="col-md-4">
        <label class="form-label">Address</label>
        <input type="text" name="partner_address" class="form-control form-control-sm" placeholder="Street / Area">
      </div>
      <div class="col-md-2">
        <label class="form-label">Division</label>
        <select name="partner_division" id="partDivision" class="form-select form-select-sm" onchange="fillDistricts('partDivision','partDistrict','partThana')">
          <option value="">— Select —</option>
          @foreach(['Dhaka','Chattogram','Rajshahi','Khulna','Barishal','Sylhet','Rangpur','Mymensingh'] as $div)
          <option value="{{ $div }}">{{ $div }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-2">
        <label class="form-label">District</label>
        <select name="partner_district" id="partDistrict" class="form-select form-select-sm" onchange="fillThanas('partDistrict','partThana','partPostCode')">
          <option value="">— Select —</option>
        </select>
      </div>
      <div class="col-md-2">
        <label class="form-label">Thana</label>
        <select name="partner_thana" id="partThana" class="form-select form-select-sm" onchange="fillPostCode('partThana','partPostCode')">
          <option value="">— Select —</option>
        </select>
      </div>
      <div class="col-md-2">
        <label class="form-label">Post Code</label>
        <input type="text" name="partner_post_code" id="partPostCode" class="form-control form-control-sm" placeholder="e.g. 1000">
      </div>
    </div>
  </div>
</div>

{{-- ═══════════════════════════════════════ VISIT DETAILS ═════════════════════════════════ --}}
<div class="reg-card">
  <div class="reg-card-header"><i class="bi bi-calendar-check-fill"></i> Visit Details</div>
  <div class="reg-card-body">
    <div class="row g-2">
      <div class="col-md-3">
        <label class="form-label">Reason for Visit</label>
        <select name="visit_reason" class="form-select form-select-sm">
          <option value="new_patient">New Patient</option>
          <option value="consultation">Consultation</option>
          <option value="followup">Follow Up</option>
          <option value="scan">Scan</option>
          <option value="iui">IUI</option>
          <option value="stimulation">Stimulation</option>
          <option value="procedure">Procedure</option>
        </select>
      </div>
      <div class="col-md-3">
        <label class="form-label">Consultant</label>
        <select name="consultant_id" id="consultantDd" class="form-select form-select-sm">
          <option value="">— Select —</option>
          @foreach($consultants as $c)
          <option value="{{ $c->id }}" data-fee="{{ $c->consultation_fee }}">{{ $c->name }} — {{ $c->specialty }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-3">
        <label class="form-label">Referral Type</label>
        <select name="referral_type" id="refTypeDd" class="form-select form-select-sm" onchange="toggleRefBy()">
          <option value="">— None —</option>
          @foreach(['Camp','External Doctor','Ads','Internal Doctor','Marketing','Self','Staff Referral','Facebook','Web & Email','Word of Mouth'] as $rt)
          <option value="{{ $rt }}">{{ $rt }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-3" id="refByWrap">
        <label class="form-label">Referred By</label>
        <input type="text" name="referred_by" class="form-control form-control-sm" placeholder="Name / Source">
      </div>
      <div class="col-md-3">
        <label class="form-label">Visit Date</label>
        <input type="date" name="visit_date" class="form-control form-control-sm" value="{{ today()->format('Y-m-d') }}">
      </div>
      <div class="col-md-3">
        <label class="form-label">Visit Time</label>
        <input type="time" name="visit_time" class="form-control form-control-sm">
      </div>
      <div class="col-md-3">
        <label class="form-label">Bill Date</label>
        <input type="date" name="bill_date" class="form-control form-control-sm" value="{{ today()->format('Y-m-d') }}">
      </div>
    </div>
  </div>
</div>

{{-- ═══════════════════════════════════════ BILL DETAILS ══════════════════════════════════ --}}
<div class="reg-card">
  <div class="reg-card-header"><i class="bi bi-receipt"></i> Bill Details</div>
  <div class="reg-card-body p-0">
    <div class="px-3 pt-3 pb-1 d-flex gap-2 flex-wrap">
      {{-- Service Dropdown --}}
      <div class="d-flex gap-1 align-items-center">
        <select id="svcDd" class="form-select form-select-sm" style="width:260px;">
          <option value="">— Select Service —</option>
          @foreach($services as $svc)
          <option value="{{ $svc->id }}" data-code="{{ $svc->service_code }}" data-rate="{{ $svc->charge }}">{{ $svc->service_code }} — {{ $svc->name }} (৳{{ number_format($svc->charge) }})</option>
          @endforeach
        </select>
        <button type="button" class="btn btn-sm btn-primary" onclick="addSvcRow()"><i class="bi bi-plus-lg"></i> Add</button>
      </div>
      {{-- Consultant fee button --}}
      <button type="button" id="addConsultFeeBtn" class="btn btn-sm btn-outline-primary d-none" onclick="addConsultFeeRow()">
        <i class="bi bi-plus-lg me-1"></i>Add Consult Fee
      </button>
      <button type="button" class="btn btn-sm btn-outline-secondary" onclick="addCustomRow()"><i class="bi bi-pencil me-1"></i>Custom Item</button>
    </div>

    <div class="table-responsive">
      <table class="bill-table table table-bordered table-sm mb-0">
        <thead><tr>
          <th style="width:34px;"></th>
          <th style="width:80px;">Code</th>
          <th>Charge Name</th>
          <th style="width:65px;">Qty</th>
          <th style="width:90px;">Price (৳)</th>
          <th style="width:85px;">Discount</th>
          <th style="width:100px;">Amount (৳)</th>
        </tr></thead>
        <tbody id="billRows"></tbody>
        <tfoot>
          <tr>
            <td colspan="5" class="text-end fw-600">Subtotal</td>
            <td colspan="2" class="text-end fw-600" id="ftSubtotal">৳ 0.00</td>
          </tr>
          <tr>
            <td colspan="4" class="text-end fw-600">Overall Discount (৳)</td>
            <td colspan="3">
              <div class="d-flex align-items-center justify-content-end gap-2">
                <input type="number" name="bill_discount" id="billDiscount" class="form-control form-control-sm text-end" value="0" min="0" step="0.01" style="width:100px;" oninput="recalcBill()">
              </div>
            </td>
          </tr>
          <tr>
            <td colspan="5" class="text-end fw-700" style="color:#5b21b6;">NET TOTAL</td>
            <td colspan="2" class="text-end fw-700" style="color:#5b21b6;font-size:1rem;" id="ftNet">৳ 0.00</td>
          </tr>
        </tfoot>
      </table>
    </div>
    <input type="hidden" id="ftNetVal" value="0">
  </div>
</div>

{{-- ═══════════════════════════════════════ PAYMENT DETAILS ═══════════════════════════════ --}}
<div class="reg-card">
  <div class="reg-card-header"><i class="bi bi-cash-coin"></i> Payment Details</div>
  <div class="reg-card-body">
    <div class="row g-2">
      <div class="col-12 mb-1">
        <label class="form-label">Payment Mode</label>
        <div class="d-flex gap-1 flex-wrap">
          @foreach(['cash'=>'Cash','bank'=>'Bank','card'=>'Card','bkash'=>'bKash','nagad'=>'Nagad','rocket'=>'Rocket'] as $val=>$lbl)
          <label class="pay-btn {{ $val=='cash'?'active':'' }}" onclick="onPayMode('{{ $val }}',event)">
            <input type="radio" name="payment_method" value="{{ $val }}" style="display:none;" {{ $val=='cash'?'checked':'' }}> {{ $lbl }}
          </label>
          @endforeach
        </div>
      </div>

      {{-- Paid Amount --}}
      <div class="col-md-2">
        <label class="form-label">Paid Amount (৳)</label>
        <div class="input-group input-group-sm">
          <input type="number" name="paid_amount" id="paidAmt" class="form-control" placeholder="0.00" min="0" step="0.01">
          <button type="button" class="btn btn-outline-success" onclick="document.getElementById('paidAmt').value=document.getElementById('ftNetVal').value" title="Pay full"><i class="bi bi-check-lg"></i></button>
        </div>
      </div>

      {{-- Bank --}}
      <div id="pmBank" class="col-md-10 d-none">
        <div class="row g-2">
          <div class="col-md-4">
            <label class="form-label">Bank Name</label>
            <input type="text" name="meta_bank_name" class="form-control form-control-sm" list="bdBankList" autocomplete="off" placeholder="Type bank name...">
          </div>
          <div class="col-md-4">
            <label class="form-label">Account Holder</label>
            <input type="text" name="meta_account_holder" class="form-control form-control-sm" placeholder="Name on account">
          </div>
          <div class="col-md-4">
            <label class="form-label">Account / Cheque No.</label>
            <input type="text" name="meta_account_number" class="form-control form-control-sm" placeholder="Account number">
          </div>
        </div>
      </div>

      {{-- Card --}}
      <div id="pmCard" class="col-md-10 d-none">
        <div class="row g-2">
          <div class="col-md-3">
            <label class="form-label">Card Type</label>
            <select name="meta_card_type" class="form-select form-select-sm">
              <option value="">— Select —</option>
              <option value="visa">Visa</option><option value="mastercard">Mastercard</option>
              <option value="amex">Amex</option><option value="other">Other</option>
            </select>
          </div>
          <div class="col-md-4">
            <label class="form-label">Card No. (last 4)</label>
            <input type="text" name="meta_card_number" class="form-control form-control-sm" placeholder="XXXX" maxlength="4">
          </div>
          <div class="col-md-4">
            <label class="form-label">Card Holder Name</label>
            <input type="text" name="meta_card_holder" class="form-control form-control-sm" placeholder="Name on card">
          </div>
        </div>
      </div>

      {{-- Mobile --}}
      <div id="pmMobile" class="col-md-10 d-none">
        <div class="row g-2">
          <div class="col-md-4">
            <label class="form-label">Transaction ID</label>
            <input type="text" name="transaction_id" class="form-control form-control-sm" placeholder="e.g. 8N3D7GH2K1">
          </div>
          <div class="col-md-4">
            <label class="form-label">Mobile Number</label>
            <input type="text" name="meta_mobile_number" class="form-control form-control-sm" placeholder="01XXXXXXXXX">
          </div>
        </div>
      </div>

      {{-- Cash default --}}
      <div id="pmCash" class="col-md-10">
        <span class="text-muted" style="font-size:.78rem;padding-top:22px;display:block;"><i class="bi bi-cash me-1"></i>Cash payment — no additional details required.</span>
      </div>

    </div>

    <div class="d-flex justify-content-end align-items-center gap-3 mt-3">
      <a href="{{ route('patients.index') }}" class="btn btn-light">Cancel</a>
      <button type="submit" class="btn fw-700 px-5" style="background:#5b21b6;color:#fff;border-radius:8px;">
        <i class="bi bi-check-lg me-2"></i>Register Patient
      </button>
    </div>
  </div>
</div>

</form>

{{-- Occupation datalist --}}
<datalist id="occupationList">
  <option value="Doctor"><option value="Nurse"><option value="Engineer"><option value="Teacher / Professor">
  <option value="Lawyer / Advocate"><option value="Accountant"><option value="Banker">
  <option value="Government Employee"><option value="Army / Police / BGB"><option value="Businessman">
  <option value="Farmer / Agriculture"><option value="Garments Worker"><option value="Driver">
  <option value="Rickshaw / CNG Driver"><option value="Shopkeeper"><option value="Tailor / Seamstress">
  <option value="Housewife"><option value="Student"><option value="Freelancer / IT">
  <option value="Journalist"><option value="Pharmacist"><option value="Lab Technician">
  <option value="Architect"><option value="Dentist"><option value="Veterinarian">
  <option value="Social Worker"><option value="Religious Leader / Imam"><option value="Fisherman">
  <option value="Day Laborer"><option value="Factory Worker"><option value="Security Guard">
  <option value="Cook / Chef"><option value="Electrician"><option value="Plumber"><option value="Mason">
  <option value="Beautician / Salon Worker"><option value="Airline Staff"><option value="Sailor / Seafarer">
  <option value="NGO Worker"><option value="Research Scientist"><option value="Economist">
  <option value="Politician / Union Member"><option value="Diplomat"><option value="Retired">
  <option value="Unemployed"><option value="Other">
</datalist>

{{-- Bangladesh Bank datalist --}}
<datalist id="bdBankList">
  <option value="Sonali Bank PLC"><option value="Janata Bank PLC"><option value="Agrani Bank PLC">
  <option value="Rupali Bank PLC"><option value="Basic Bank Limited"><option value="Bangladesh Development Bank Limited (BDBL)">
  <option value="AB Bank PLC"><option value="Al-Arafah Islami Bank PLC"><option value="Bank Asia PLC">
  <option value="BRAC Bank PLC"><option value="City Bank PLC"><option value="Dhaka Bank PLC">
  <option value="Dutch-Bangla Bank PLC"><option value="Eastern Bank PLC"><option value="EXIM Bank PLC">
  <option value="First Security Islami Bank PLC"><option value="Global Islami Bank PLC">
  <option value="IFIC Bank PLC"><option value="Islami Bank Bangladesh PLC"><option value="Jamuna Bank PLC">
  <option value="Meghna Bank PLC"><option value="Mercantile Bank PLC"><option value="Midland Bank PLC">
  <option value="Modhumoti Bank PLC"><option value="Mutual Trust Bank PLC"><option value="National Bank Limited">
  <option value="NCC Bank PLC"><option value="One Bank PLC"><option value="Padma Bank PLC">
  <option value="Premier Bank PLC"><option value="Prime Bank PLC"><option value="Pubali Bank PLC">
  <option value="Shahjalal Islami Bank PLC"><option value="Social Islami Bank PLC"><option value="Southeast Bank PLC">
  <option value="Standard Bank PLC"><option value="Trust Bank PLC"><option value="Union Bank PLC">
  <option value="United Commercial Bank PLC (UCB)"><option value="Uttara Bank PLC">
  <option value="Standard Chartered Bank"><option value="HSBC Bangladesh"><option value="Citibank N.A.">
  <option value="Bangladesh Krishi Bank (BKB)"><option value="Rajshahi Krishi Unnayan Bank (RAKUB)">
  <option value="Probashi Kallyan Bank"><option value="Palli Sanchay Bank">
</datalist>

{{-- ═══════════════ REGISTRATION SUCCESS MODAL ═══════════════ --}}
<div class="modal fade" id="regSuccessModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0" style="border-radius:16px;overflow:hidden;">
      <div class="modal-header border-0 pb-0" style="background:linear-gradient(135deg,#16a34a,#22c55e);">
        <div class="w-100 text-center py-3">
          <div style="width:64px;height:64px;background:rgba(255,255,255,.25);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 10px;">
            <i class="bi bi-check-lg text-white" style="font-size:2rem;"></i>
          </div>
          <h5 class="text-white fw-700 mb-0">Registration Successful!</h5>
        </div>
      </div>
      <div class="modal-body text-center py-4">
        <p class="mb-1 text-muted" style="font-size:.82rem;">Patient Registered</p>
        <h4 class="fw-700 text-dark mb-1" id="regSuccessName">—</h4>
        <span class="badge px-3 py-2" style="background:#f3e8ff;color:#5b21b6;font-size:.95rem;border-radius:20px;letter-spacing:1px;" id="regSuccessCode">MIF-000</span>
      </div>
      <div class="modal-footer border-0 justify-content-center gap-2 pb-4">
        <button type="button" class="btn fw-600 px-4" style="background:#5b21b6;color:#fff;border-radius:8px;" id="openBillBtn">
          <i class="bi bi-receipt me-2"></i>View & Print Bill
        </button>
        <button type="button" class="btn btn-outline-secondary px-4" style="border-radius:8px;" onclick="resetRegForm()">
          <i class="bi bi-plus-circle me-2"></i>Register Another
        </button>
      </div>
    </div>
  </div>
</div>

{{-- Validation errors toast --}}
<div class="position-fixed bottom-0 end-0 p-3" style="z-index:9999">
  <div id="errToast" class="toast align-items-center text-bg-danger border-0" role="alert">
    <div class="d-flex">
      <div class="toast-body" id="errToastMsg"></div>
      <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
    </div>
  </div>
</div>

@endsection
@push('scripts')
<script>
// ─── Bangladesh Admin Data (Division → District → Thana → PostCode) ───────────
const BD = {
  'Dhaka': {
    'Dhaka': {
      'Adabor':1207,'Badda':1212,'Bangshal':1100,'Cantonment':1206,'Chawkbazar':1211,
      'Dakshinkhan':1230,'Demra':1361,'Dhanmondi':1205,'Dohar':1323,'Gendaria':1204,
      'Gulshan':1212,'Hazaribagh':1209,'Jatrabari':1204,'Kadamtali':1362,'Kafrul':1216,
      'Kalabagan':1205,'Kamrangirchar':1211,'Keraniganj':1310,'Khilgaon':1219,'Khilkhet':1229,
      'Kotwali':1100,'Lalbagh':1211,'Mirpur':1216,'Mohammadpur':1207,'Motijheel':1000,
      'Nawabganj':1320,'Newmarket':1205,'Pallabi':1216,'Paltan':1000,'Ramna':1000,
      'Rayer Bazar':1207,'Sabujbagh':1214,'Savar':1340,'Shah Ali':1216,'Shahbagh':1000,
      'Shyampur':1204,'Sutrapur':1100,'Tejgaon':1208,'Turag':1711,'Uttara':1230,'Wari':1203
    },
    'Gazipur': {'Gazipur Sadar':1700,'Kaliakair':1750,'Kaliganj':1725,'Kapasia':1730,'Sreepur':1740},
    'Manikganj': {'Manikganj Sadar':1800,'Daulatpur':1820,'Ghior':1841,'Harirampur':1830,'Saturia':1810,'Shivalaya':1850,'Singair':1860},
    'Munshiganj': {'Munshiganj Sadar':1500,'Gazaria':1540,'Lohajang':1520,'Sirajdikhan':1561,'Sreenagar':1550,'Tongibari':1510},
    'Narayanganj': {'Narayanganj Sadar':1400,'Araihazar':1450,'Bandar':1411,'Rupganj':1460,'Sonargaon':1440},
    'Narsingdi': {'Narsingdi Sadar':1600,'Belabo':1620,'Monohardi':1640,'Palash':1611,'Raipura':1610,'Shibpur':1630},
    'Tangail': {'Tangail Sadar':1900,'Basail':1930,'Bhuapur':1963,'Delduar':1940,'Dhanbari':1991,'Ghatail':1960,'Gopalpur':1970,'Kalihati':1951,'Madhupur':1990,'Mirzapur':1870,'Nagarpur':1910,'Sakhipur':1920},
    'Kishoreganj': {'Kishoreganj Sadar':2300,'Austagram':2340,'Bajitpur':2380,'Bhairab':2350,'Hossainpur':2360,'Itna':2330,'Karimganj':2370,'Katiadi':2320,'Kuliarchar':2290,'Mithamain':2320,'Nikli':2330,'Pakundia':2310,'Tarail':2350},
    'Faridpur': {'Faridpur Sadar':7800,'Alfadanga':7810,'Bhanga':7840,'Boalmari':7820,'Char Bhadrasan':7820,'Madhukhali':7810,'Nagarkanda':7870,'Sadarpur':7830,'Saltha':7860},
    'Madaripur': {'Madaripur Sadar':7900,'Kalkini':7940,'Rajoir':7910,'Shibchar':7920},
    'Gopalganj': {'Gopalganj Sadar':8100,'Kashiani':8120,'Kotalipara':8130,'Muksudpur':8110,'Tungipara':8140},
    'Rajbari': {'Rajbari Sadar':7700,'Baliakandi':7740,'Goalanda':7730,'Kalukhali':7710,'Pangsha':7720},
    'Shariatpur': {'Shariatpur Sadar':8000,'Bhedarganj':8020,'Damudya':8010,'Gosairhat':8060,'Naria':8030,'Zanjira':8040},
  },
  'Chattogram': {
    'Chattogram': {
      'Anwara':4376,'Banshkhali':4390,'Boalkhali':4361,'Chandgaon':4212,'Chandanaish':4370,
      'Double Mooring':4100,'Fatikchhari':4335,'Hathazari':4330,'Karnaphuli':4231,'Kotwali':4000,
      'Lohagara':4396,'Mirsharai':4320,'Pahartali':4202,'Panchlaish':4203,'Patiya':4370,
      'Rangunia':4360,'Raozan':4340,'Sandwip':4292,'Satkania':4385,'Sitakunda':4310
    },
    'Coxs Bazar': {'Coxs Bazar Sadar':4700,'Chakaria':4730,'Kutubdia':4741,'Maheshkhali':4720,'Pekua':4723,'Ramu':4730,'Teknaf':4761,'Ukhia':4751},
    'Comilla': {'Comilla Sadar':3500,'Barura':3530,'Brahmanpara':3571,'Burichang':3520,'Chandina':3510,'Chauddagram':3540,'Daudkandi':3560,'Debidwar':3580,'Homna':3570,'Laksam':3550,'Lalmai':3510,'Manoharganj':3590,'Meghna':3561,'Muradnagar':3590,'Nangalkot':3560,'Titas':3560},
    'Brahmanbaria': {'Brahmanbaria Sadar':3400,'Akhaura':3450,'Ashuganj':3410,'Bancharampur':3470,'Bijoynagar':3430,'Kasba':3440,'Nabinagar':3480,'Nasirnagar':3490,'Sarail':3420},
    'Chandpur': {'Chandpur Sadar':3600,'Faridganj':3670,'Haimchar':3610,'Hajiganj':3620,'Kachua':3630,'Matlab Dakshin':3650,'Matlab Uttar':3640,'Shahrasti':3660},
    'Feni': {'Feni Sadar':3900,'Chhagalnaiya':3920,'Daganbhuiyan':3930,'Fulgazi':3910,'Parshuram':3940,'Sonagazi':3950},
    'Khagrachhari': {'Khagrachhari Sadar':4400,'Baghaichhari':4450,'Dighinala':4410,'Lakshmichhari':4440,'Mahalchhari':4450,'Manikchhari':4441,'Matirangua':4430,'Panchhari':4460,'Ramgarh':4420},
    'Lakshmipur': {'Lakshmipur Sadar':3700,'Kamalnagar':3730,'Ramganj':3710,'Ramgati':3741,'Raipur':3720},
    'Noakhali': {'Noakhali Sadar':3800,'Begumganj':3820,'Chatkhil':3850,'Companiganj':3880,'Hatiya':3840,'Kabirhat':3870,'Senbagh':3860,'Sonaimuri':3810,'Subarnachar':3890},
    'Rangamati': {'Rangamati Sadar':4500,'Bagaichhari':4520,'Barkal':4560,'Belaichhari':4570,'Juraichhari':4580,'Kaptai':4530,'Kaukhali':4510,'Langadu':4590,'Naniarchar':4550,'Rajasthali':4540},
  },
  'Rajshahi': {
    'Rajshahi': {'Rajshahi Sadar':6000,'Bagha':6280,'Bagmara':6230,'Charghat':6230,'Durgapur':6250,'Godagari':6270,'Mohanpur':6220,'Paba':6210,'Puthia':6241,'Tanore':6260},
    'Chapainawabganj': {'Chapainawabganj Sadar':6300,'Bholahat':6340,'Gomastapur':6330,'Nachol':6350,'Shibganj':6320},
    'Naogaon': {'Naogaon Sadar':6500,'Atrai':6520,'Badalgachhi':6560,'Dhamoirhat':6570,'Mahadebpur':6580,'Manda':6590,'Mohadevpur':6580,'Niamatpur':6550,'Patnitala':6540,'Porsha':6510,'Raninagar':6530,'Sapahar':6595},
    'Natore': {'Natore Sadar':6400,'Bagatipara':6430,'Baraigram':6440,'Gurudaspur':6460,'Lalpur':6450,'Singra':6420},
    'Sirajganj': {'Sirajganj Sadar':6700,'Belkuchi':6730,'Chauhali':6760,'Kamarkhand':6740,'Kazipur':6770,'Raiganj':6750,'Shahjadpur':6770,'Tarash':6780,'Ullahpara':6710},
    'Pabna': {'Pabna Sadar':6600,'Atgharia':6630,'Bera':6640,'Bhangura':6660,'Chatmohar':6650,'Faridpur':6620,'Ishwardi':6620,'Santhia':6670,'Sujanagar':6690},
    'Bogura': {'Bogura Sadar':5800,'Adamdighi':5820,'Dhunat':5841,'Dhupchanchia':5850,'Gabtali':5870,'Kahaloo':5830,'Nandigram':5860,'Sariakandi':5880,'Shajahanpur':5890,'Sherpur':5840,'Shibganj':5810,'Sonatala':5880},
    'Joypurhat': {'Joypurhat Sadar':5900,'Akkelpur':5930,'Kalai':5920,'Khetlal':5940,'Panchbibi':5910},
  },
  'Khulna': {
    'Khulna': {'Khulna Sadar':9100,'Batiaghata':9220,'Dacope':9241,'Dumuria':9210,'Dighalia':9231,'Koyra':9271,'Paikgachha':9280,'Phultala':9201,'Rupsa':9231,'Terokhada':9261},
    'Bagerhat': {'Bagerhat Sadar':9300,'Chitalmari':9360,'Fakirhat':9330,'Kachua':9351,'Mollahat':9340,'Mongla':9320,'Morrelganj':9390,'Rampal':9371,'Sarankhola':9380},
    'Chuadanga': {'Chuadanga Sadar':7200,'Alamdanga':7210,'Damurhuda':7220,'Jibannagar':7230},
    'Jessore': {'Jessore Sadar':7400,'Abhaynagar':7431,'Bagherpara':7441,'Chaugachha':7420,'Jhikargachha':7440,'Keshabpur':7450,'Manirampur':7430,'Sharsha':7411},
    'Jhenaidah': {'Jhenaidah Sadar':7300,'Harinakunda':7340,'Kaliganj':7330,'Kotchandpur':7320,'Maheshpur':7350,'Shailkupa':7310},
    'Kushtia': {'Kushtia Sadar':7000,'Bheramara':7050,'Daulatpur':7030,'Khoksa':7040,'Kumarkhali':7010,'Mirpur':7020},
    'Magura': {'Magura Sadar':7600,'Mohammadpur':7630,'Shalikha':7620,'Sreepur':7610},
    'Meherpur': {'Meherpur Sadar':7100,'Gangni':7110,'Mujibnagar':7120},
    'Narail': {'Narail Sadar':7500,'Kalia':7531,'Lohagara':7520},
    'Satkhira': {'Satkhira Sadar':9400,'Assasuni':9440,'Debhata':9430,'Kalaroa':9420,'Kaliganj':9450,'Shyamnagar':9471,'Tala':9460},
  },
  'Barishal': {
    'Barishal': {'Barishal Sadar':8200,'Agailjhara':8210,'Babuganj':8230,'Bakerganj':8240,'Banaripara':8280,'Gaurnadi':8250,'Hizla':8290,'Mehendiganj':8260,'Muladi':8281,'Wazirpur':8270},
    'Barguna': {'Barguna Sadar':8700,'Amtali':8710,'Bamna':8730,'Betagi':8740,'Patharghata':8720,'Taltali':8761},
    'Bhola': {'Bhola Sadar':8300,'Borhanuddin':8321,'Char Fasson':8330,'Daulatkhan':8310,'Lalmohan':8340,'Manpura':8353,'Tazumuddin':8360},
    'Jhalokati': {'Jhalokati Sadar':8400,'Kanthalia':8420,'Nalchity':8430,'Rajapur':8410},
    'Patuakhali': {'Patuakhali Sadar':8600,'Bauphal':8630,'Dashmina':8620,'Dumki':8650,'Galachipa':8640,'Kalapara':8660,'Mirzaganj':8670,'Rangabali':8602},
    'Pirojpur': {'Pirojpur Sadar':8500,'Bhandaria':8540,'Kawkhali':8560,'Mathbaria':8531,'Nazirpur':8510,'Nesarabad':8511,'Zianagar':8551},
  },
  'Sylhet': {
    'Sylhet': {'Sylhet Sadar':3100,'Balaganj':3141,'Beanibazar':3150,'Bishwanath':3130,'Companiganj':3161,'Fenchuganj':3116,'Golapganj':3170,'Gowainghat':3160,'Jaintiapur':3156,'Kanaighat':3180,'Osmani Nagar':3110,'South Surma':3111,'Zakiganj':3190},
    'Habiganj': {'Habiganj Sadar':3300,'Ajmiriganj':3320,'Baniachong':3330,'Bahubal':3310,'Chunarughat':3350,'Lakhai':3340,'Madhabpur':3360,'Nabiganj':3380},
    'Moulvibazar': {'Moulvibazar Sadar':3200,'Barlekha':3240,'Juri':3210,'Kamalganj':3250,'Kulaura':3230,'Rajnagar':3210,'Sreemangal':3210},
    'Sunamganj': {'Sunamganj Sadar':3000,'Bishwamvarpur':3030,'Chhatak':3080,'Derai':3010,'Dharampasha':3060,'Dowarabazar':3020,'Jagannathpur':3040,'Jamalganj':3061,'Sulla':3070,'Tahirpur':3050,'Shantiganj':3060},
  },
  'Rangpur': {
    'Rangpur': {'Rangpur Sadar':5400,'Badarganj':5470,'Gangachara':5420,'Kaunia':5430,'Mithapukur':5460,'Pirgachha':5440,'Pirganj':5450,'Taraganj':5411},
    'Dinajpur': {'Dinajpur Sadar':5200,'Birampur':5210,'Birganj':5250,'Biral':5260,'Bochaganj':5241,'Chirirbandar':5221,'Fulbari':5201,'Ghoraghat':5270,'Hakimpur':5231,'Kaharole':5280,'Khansama':5231,'Nawabganj':5290,'Parbatipur':5230},
    'Gaibandha': {'Gaibandha Sadar':5700,'Fulchhari':5740,'Gobindaganj':5740,'Palashbari':5711,'Saghata':5720,'Sadullapur':5730,'Sundarganj':5750},
    'Kurigram': {'Kurigram Sadar':5600,'Bhurungamari':5640,'Char Rajibpur':5661,'Chilmari':5620,'Nageshwari':5650,'Phulbari':5660,'Rajarhat':5630,'Raumari':5670,'Ulipur':5610},
    'Lalmonirhat': {'Lalmonirhat Sadar':5500,'Aditmari':5510,'Hatibandha':5540,'Kaliganj':5520,'Patgram':5530},
    'Nilphamari': {'Nilphamari Sadar':5300,'Dimla':5320,'Domar':5330,'Jaldhaka':5340,'Kishoreganj':5310,'Saidpur':5311},
    'Panchagarh': {'Panchagarh Sadar':5010,'Atwari':5020,'Boda':5030,'Debiganj':5040,'Tetulia':5050},
    'Thakurgaon': {'Thakurgaon Sadar':5100,'Baliadangi':5120,'Haripur':5110,'Pirganj':5130,'Ranisankail':5140},
  },
  'Mymensingh': {
    'Mymensingh': {'Mymensingh Sadar':2200,'Bhaluka':2240,'Dhobaura':2281,'Fulbaria':2210,'Gaffargaon':2230,'Gauripur':2271,'Haluaghat':2290,'Ishwarganj':2220,'Muktagachha':2260,'Nandail':2270,'Phulpur':2281,'Trishal':2250},
    'Jamalpur': {'Jamalpur Sadar':2000,'Baksiganj':2020,'Dewanganj':2040,'Islampur':2050,'Madarganj':2030,'Melandaha':2010,'Sarishabari':2060},
    'Netrokona': {'Netrokona Sadar':2400,'Atpara':2420,'Barhatta':2430,'Durgapur':2450,'Khaliajhuri':2470,'Kalmakanda':2460,'Kendua':2440,'Madan':2480,'Mohanganj':2410,'Purbadhala':2410},
    'Sherpur': {'Sherpur Sadar':2100,'Jhenaigati':2130,'Nakla':2120,'Nalitabari':2140,'Sreebardi':2150},
  }
};

// ─── Fill District dropdown ─────────────────────────────────────────────────
function fillDistricts(divId, distId, thanaId){
  const div = document.getElementById(divId).value;
  const dd  = document.getElementById(distId);
  const td  = document.getElementById(thanaId);
  dd.innerHTML = '<option value="">— District —</option>';
  td.innerHTML = '<option value="">— Thana —</option>';
  if(!div || !BD[div]) return;
  Object.keys(BD[div]).sort().forEach(d=>{
    dd.innerHTML += `<option value="${d}">${d}</option>`;
  });
}

function fillThanas(distId, thanaId, postId){
  const distEl = document.getElementById(distId);
  const divEl  = distId === 'patDistrict' ? document.getElementById('patDivision') : document.getElementById('partDivision');
  const div    = divEl.value;
  const dist   = distEl.value;
  const td     = document.getElementById(thanaId);
  td.innerHTML = '<option value="">— Thana —</option>';
  if(postId) document.getElementById(postId).value = '';
  if(!div || !dist || !BD[div]?.[dist]) return;
  Object.keys(BD[div][dist]).sort().forEach(t=>{
    td.innerHTML += `<option value="${t}">${t}</option>`;
  });
}

function fillPostCode(thanaId, postId){
  const thanaEl  = document.getElementById(thanaId);
  const isPatient = thanaId === 'patThana';
  const divEl  = isPatient ? document.getElementById('patDivision') : document.getElementById('partDivision');
  const distEl = isPatient ? document.getElementById('patDistrict') : document.getElementById('partDistrict');
  const pc = BD[divEl.value]?.[distEl.value]?.[thanaEl.value];
  if(pc && postId) document.getElementById(postId).value = pc;
}

// ─── Age calculator from DOB ────────────────────────────────────────────────
function calcAge(dobId, yId, mId, dId, ageId){
  const dobVal = document.getElementById(dobId).value;
  const badge  = document.getElementById(dobId === 'patDob' ? 'patAgeBadge' : 'partAgeBadge');
  if(!dobVal){ if(badge) badge.classList.add('d-none'); return; }
  const dob   = new Date(dobVal);
  const today = new Date();
  let y = today.getFullYear() - dob.getFullYear();
  let m = today.getMonth()    - dob.getMonth();
  let d = today.getDate()     - dob.getDate();
  if(d < 0){ m--; d += new Date(today.getFullYear(), today.getMonth(), 0).getDate(); }
  if(m < 0){ y--; m += 12; }
  document.getElementById(ageId).value = y;
  if(badge){
    badge.textContent = `${y}Y ${m}M ${d}D`;
    badge.classList.remove('d-none');
  }
}

// ─── Toggle partner section ─────────────────────────────────────────────────
function toggleSection(bodyId, header){
  const body = document.getElementById(bodyId);
  const icon = header.querySelector('i.bi-chevron-down, i.bi-chevron-up');
  const open = body.style.display === 'none';
  body.style.display = open ? '' : 'none';
  if(icon){ icon.className = open ? 'bi bi-chevron-up' : 'bi bi-chevron-down'; }
}

// ─── Services data ──────────────────────────────────────────────────────────
const SERVICES = {
  @foreach($services as $svc)
  "{{ $svc->id }}": {id:{{ $svc->id }},code:"{{ $svc->service_code }}",name:"{{ addslashes($svc->name) }}",rate:{{ $svc->charge }}},
  @endforeach
};
let rowIdx = 0;

function addSvcRow(){
  const dd  = document.getElementById('svcDd');
  const opt = dd.options[dd.selectedIndex];
  if(!opt.value) return;
  const svc = SERVICES[opt.value];
  if(!svc) return;
  if(document.querySelector(`.svc-row[data-svc="${svc.id}"]`)){ dd.value=''; return; }
  const tbody = document.getElementById('billRows');
  const tr    = document.createElement('tr');
  tr.className = 'svc-row'; tr.dataset.svc = svc.id;
  tr.innerHTML = `
    <td class="text-center"><button type="button" class="btn btn-sm btn-outline-danger py-0 px-1 remove-row" style="font-size:.6rem;"><i class="bi bi-x-lg"></i></button></td>
    <td><span class="badge bg-secondary" style="font-size:.7rem;">${svc.code}</span><input type="hidden" name="service_ids[]" value="${svc.id}"></td>
    <td style="font-size:.82rem;">${svc.name}</td>
    <td><input type="number" class="form-control form-control-sm text-center row-qty" value="1" min="1" style="width:55px;"></td>
    <td><input type="number" class="form-control form-control-sm text-end row-rate" value="${svc.rate}" min="0" step="0.01" style="width:85px;"></td>
    <td><input type="number" class="form-control form-control-sm text-end row-disc" value="0" min="0" step="0.01" style="width:75px;"></td>
    <td class="text-end fw-600 text-primary row-net">${svc.rate.toLocaleString('en-BD',{minimumFractionDigits:2})}</td>`;
  tbody.appendChild(tr); dd.value=''; recalcBill();
}

function addConsultFeeRow(){
  document.querySelectorAll('.consult-row').forEach(r=>r.remove());
  const dd  = document.getElementById('consultantDd');
  const fee = parseFloat(dd.options[dd.selectedIndex]?.dataset.fee)||0;
  if(!fee) return;
  const name = dd.options[dd.selectedIndex].text.split('—')[0].trim();
  const tbody = document.getElementById('billRows');
  const tr    = document.createElement('tr');
  tr.className = 'consult-row';
  tr.innerHTML = `
    <td class="text-center"><button type="button" class="btn btn-sm btn-outline-danger py-0 px-1 remove-row" style="font-size:.6rem;"><i class="bi bi-x-lg"></i></button></td>
    <td><span class="badge bg-primary-subtle text-primary" style="font-size:.7rem;">CONSULT</span>
        <input type="hidden" name="custom_desc[]" value="Consultation Fee — ${name}">
        <input type="hidden" name="custom_qty[]"  value="1">
        <input type="hidden" name="custom_rate[]" value="${fee}">
        <input type="hidden" name="custom_disc[]" value="0">
    </td>
    <td style="font-size:.82rem;">Consultation Fee — ${name}</td>
    <td class="text-center">1</td>
    <td class="text-end">${fee.toLocaleString('en-BD')}</td>
    <td class="text-end">—</td>
    <td class="text-end fw-600 text-primary row-net">${fee.toLocaleString('en-BD',{minimumFractionDigits:2})}</td>`;
  tbody.appendChild(tr); recalcBill();
}

function addCustomRow(){
  const tbody = document.getElementById('billRows');
  const tr    = document.createElement('tr');
  tr.className = 'custom-row';
  tr.innerHTML = `
    <td class="text-center"><button type="button" class="btn btn-sm btn-outline-danger py-0 px-1 remove-row" style="font-size:.6rem;"><i class="bi bi-x-lg"></i></button></td>
    <td><span class="badge bg-secondary" style="font-size:.68rem;">CUSTOM</span></td>
    <td><input type="text" name="custom_desc[]" class="form-control form-control-sm" placeholder="Charge name" style="min-width:150px;"></td>
    <td><input type="number" name="custom_qty[]" class="form-control form-control-sm text-center row-qty" value="1" min="1" style="width:55px;"></td>
    <td><input type="number" name="custom_rate[]" class="form-control form-control-sm text-end row-rate" value="" min="0" step="0.01" style="width:85px;" placeholder="0.00"></td>
    <td><input type="number" name="custom_disc[]" class="form-control form-control-sm text-end row-disc" value="0" min="0" step="0.01" style="width:75px;"></td>
    <td class="text-end fw-600 text-primary row-net">0.00</td>`;
  tbody.appendChild(tr);
}

document.addEventListener('click', e=>{
  if(e.target.closest('.remove-row')){
    e.target.closest('tr').remove(); recalcBill();
  }
});
document.addEventListener('input', e=>{
  if(['row-qty','row-rate','row-disc'].some(c=>e.target.classList.contains(c))) recalcBill();
});

function addRegFeeRow(){
  if(document.querySelector('.reg-fee-row')) return;
  const tbody = document.getElementById('billRows');
  const tr    = document.createElement('tr');
  tr.className = 'reg-fee-row';
  tr.innerHTML = `
    <td class="text-center"><button type="button" class="btn btn-sm btn-outline-danger py-0 px-1 remove-row" style="font-size:.6rem;"><i class="bi bi-x-lg"></i></button></td>
    <td><span class="badge bg-success-subtle text-success" style="font-size:.7rem;">REGFEE</span>
        <input type="hidden" name="custom_desc[]" value="Registration Fee (New Patient)">
        <input type="hidden" name="custom_qty[]"  value="1">
        <input type="hidden" name="custom_rate[]" value="200">
        <input type="hidden" name="custom_disc[]" value="0">
    </td>
    <td style="font-size:.82rem;">Registration Fee (New Patient)</td>
    <td class="text-center">1</td>
    <td class="text-end">200</td>
    <td class="text-end">—</td>
    <td class="text-end fw-600 text-success row-net-fixed">200.00</td>`;
  tbody.insertBefore(tr, tbody.firstChild);
  recalcBill();
}

document.addEventListener('DOMContentLoaded', ()=>{ addRegFeeRow(); });

// ─── AJAX Form Submit ─────────────────────────────────────────────────────
let _billUrl = '';

document.getElementById('regForm').addEventListener('submit', function(e){
  e.preventDefault();
  const btn  = this.querySelector('button[type=submit]');
  const orig = btn.innerHTML;
  btn.disabled = true;
  btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Registering…';

  const formData = new FormData(this);

  fetch(this.action, {
    method: 'POST',
    body: formData,
    headers: {
      'X-Requested-With': 'XMLHttpRequest',
      'Accept': 'application/json',
    }
  })
  .then(async res => {
    if (!res.ok) {
      const err = await res.json().catch(()=>({}));
      const msgs = err.errors
        ? Object.values(err.errors).flat().join(' | ')
        : (err.message || 'Registration failed.');
      showErrToast(msgs);
      btn.disabled = false; btn.innerHTML = orig;
      return;
    }
    return res.json();
  })
  .then(data => {
    if (!data || !data.success) return;
    _billUrl = data.bill_url;
    document.getElementById('regSuccessName').textContent = data.patient_name;
    document.getElementById('regSuccessCode').textContent = data.patient_code;
    new bootstrap.Modal(document.getElementById('regSuccessModal')).show();
    btn.disabled = false; btn.innerHTML = orig;
  })
  .catch(err => {
    showErrToast('Network error. Please try again.');
    btn.disabled = false; btn.innerHTML = orig;
  });
});

document.getElementById('openBillBtn').addEventListener('click', function(){
  if(_billUrl) window.open(_billUrl, '_blank');
  bootstrap.Modal.getInstance(document.getElementById('regSuccessModal'))?.hide();
});

function resetRegForm(){
  bootstrap.Modal.getInstance(document.getElementById('regSuccessModal'))?.hide();
  document.getElementById('regForm').reset();
  document.getElementById('photoPreview').innerHTML = '<i class="bi bi-person-bounding-box text-muted" style="font-size:2rem;"></i>';
  document.getElementById('photoData').value = '';
  document.getElementById('billRows').innerHTML = '';
  addRegFeeRow();
  recalcBill();
}

function showErrToast(msg){
  document.getElementById('errToastMsg').textContent = msg;
  bootstrap.Toast.getOrCreateInstance(document.getElementById('errToast')).show();
}

function recalcBill(){
  let gross = 0;
  // Fixed rows (reg-fee, consult)
  document.querySelectorAll('.reg-fee-row .row-net-fixed, .consult-row .row-net').forEach(td=>{
    gross += parseFloat(td.textContent.replace(/,/g,''))||0;
  });
  document.querySelectorAll('.svc-row, .custom-row').forEach(tr=>{
    const qty  = parseFloat(tr.querySelector('.row-qty')?.value||1)||1;
    const rate = parseFloat(tr.querySelector('.row-rate')?.value||0)||0;
    const disc = parseFloat(tr.querySelector('.row-disc')?.value||0)||0;
    const net  = Math.max(0, qty*rate - disc);
    if(tr.querySelector('.row-net')) tr.querySelector('.row-net').textContent = net.toLocaleString('en-BD',{minimumFractionDigits:2});
    gross += qty*rate;
  });
  const disc  = parseFloat(document.getElementById('billDiscount').value||0)||0;
  const net   = Math.max(0, gross - disc);
  document.getElementById('ftSubtotal').textContent = '৳ ' + gross.toLocaleString('en-BD',{minimumFractionDigits:2});
  document.getElementById('ftNet').textContent      = '৳ ' + net.toLocaleString('en-BD',{minimumFractionDigits:2});
  document.getElementById('ftNetVal').value         = net.toFixed(2);
}

// ─── Consultant dropdown → auto-add/replace consult fee row ─────────────
document.getElementById('consultantDd').addEventListener('change', function(){
  document.querySelectorAll('.consult-row').forEach(r=>r.remove());
  const fee = parseFloat(this.options[this.selectedIndex]?.dataset.fee||0)||0;
  document.getElementById('addConsultFeeBtn').classList.toggle('d-none', fee<=0);
  if(fee > 0) addConsultFeeRow();
  recalcBill();
});

// ─── Payment mode ────────────────────────────────────────────────────────
function onPayMode(method, e){
  document.querySelectorAll('.pay-btn').forEach(b=>b.classList.remove('active'));
  e.currentTarget.classList.add('active');
  e.currentTarget.querySelector('input[type=radio]').checked = true;
  ['pmBank','pmCard','pmMobile','pmCash'].forEach(id=>document.getElementById(id)?.classList.add('d-none'));
  const show = {bank:'pmBank',card:'pmCard',bkash:'pmMobile',nagad:'pmMobile',rocket:'pmMobile'};
  document.getElementById(show[method]||'pmCash').classList.remove('d-none');
}

// ─── Referral by toggle ──────────────────────────────────────────────────
function toggleRefBy(){
  const v = document.getElementById('refTypeDd').value;
  document.getElementById('refByWrap').style.display = (v && v !== 'Self') ? '' : 'none';
}
toggleRefBy();

// ─── Partner photo file preview ─────────────────────────────────────────
function previewPartnerFile(input){
  if(!input.files || !input.files[0]) return;
  const reader = new FileReader();
  reader.onload = function(e){
    document.getElementById('partnerPhotoData').value = e.target.result;
    document.getElementById('partnerPhotoPreview').innerHTML = `<img src="${e.target.result}" style="width:100%;height:100%;object-fit:cover;">`;
  };
  reader.readAsDataURL(input.files[0]);
}

// ─── Photo file preview ───────────────────────────────────────────────────
function previewFile(input){
  if(!input.files || !input.files[0]) return;
  const reader = new FileReader();
  reader.onload = function(e){
    document.getElementById('photoData').value = e.target.result;
    document.getElementById('photoPreview').innerHTML = `<img src="${e.target.result}" style="width:100%;height:100%;object-fit:cover;">`;
  };
  reader.readAsDataURL(input.files[0]);
}
</script>
@endpush
