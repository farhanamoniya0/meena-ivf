@extends('layouts.app')
@section('title','Receipt — '.$payment->receipt_no)
@section('page-title','Payment Receipt')
@push('styles')
<style>
.rcpt-font  { font-family: Arial, Helvetica, sans-serif; }
.rcpt-th    { background:#f0f0f0; font-weight:bold; padding:5px 8px; border:1px solid #333; font-size:11.5px; }
.rcpt-td    { padding:4px 8px; border:1px solid #bbb; font-size:11.5px; vertical-align:middle; }
.rcpt-label { font-weight:bold; white-space:nowrap; }
.rcpt-sum-label { border:1px solid #555; font-weight:bold; padding:4px 8px; font-size:11.5px; background:#f8f8f8; }
.rcpt-sum-val   { border:1px solid #555; text-align:right; padding:4px 8px; font-size:11.5px; }
@media print {
  @page { size: A4 portrait; margin: 10mm; }
  body  { font-size:11px !important; background:#fff !important; }
  #sidebar, #topbar, .no-print { display:none !important; }
  #main-content { margin:0 !important; padding:0 !important; }
  #receiptCard { box-shadow:none !important; border:none !important; max-width:100% !important; }
  .rcpt-th { -webkit-print-color-adjust:exact; print-color-adjust:exact; }
  a { text-decoration:none !important; color:inherit !important; }
}
</style>
@endpush
@section('content')

<div class="d-flex justify-content-end gap-2 mb-3 no-print">
  <button onclick="window.print()" class="btn btn-primary btn-sm"><i class="bi bi-printer me-1"></i>Print</button>
  <a href="{{ route('billing.patient', $payment->patient) }}" class="btn btn-light btn-sm">Back to Billing</a>
</div>

@if(session('success'))
<div class="alert alert-success no-print">{{ session('success') }}</div>
@endif

{{-- Receipt Card --}}
<div id="receiptCard" class="rcpt-font" style="max-width:800px;margin:0 auto;background:#fff;color:#000;border:1px solid #ccc;">

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
        <td width="110"></td>
      </tr>
    </table>
  </div>

  {{-- ═══ TITLE ═══ --}}
  <div style="text-align:center;border-bottom:1px solid #555;padding:5px 0;font-weight:bold;font-size:13px;background:#f0f0f0;letter-spacing:1px;">PAYMENT RECEIPT</div>

  {{-- ═══ PATIENT & RECEIPT INFO ═══ --}}
  <div style="border-bottom:1px solid #555;padding:8px 14px;">
    <table width="100%" cellpadding="3" cellspacing="0">
      <tr>
        <td width="50%" style="vertical-align:top;">
          <table cellpadding="2" cellspacing="0">
            <tr>
              <td class="rcpt-label" style="width:115px;">UHID</td>
              <td>: <strong>{{ $payment->patient->patient_code }}</strong></td>
            </tr>
            <tr>
              <td class="rcpt-label">Patient Name</td>
              <td>: {{ $payment->patient->name }}</td>
            </tr>
            <tr>
              <td class="rcpt-label">Age / Gender</td>
              <td>: {{ $payment->patient->age ? $payment->patient->age.' Year(s)' : '—' }} / {{ ucfirst($payment->patient->gender ?? '—') }}</td>
            </tr>
            @if($payment->patient->phone)
            <tr>
              <td class="rcpt-label">Mobile No.</td>
              <td>: {{ $payment->patient->phone }}</td>
            </tr>
            @endif
          </table>
        </td>
        <td width="50%" style="vertical-align:top;">
          <table cellpadding="2" cellspacing="0">
            <tr>
              <td class="rcpt-label" style="width:130px;">Receipt No.</td>
              <td>: {{ $payment->receipt_no }}</td>
            </tr>
            <tr>
              <td class="rcpt-label">Date &amp; Time</td>
              <td>: {{ $payment->created_at->format('d/m/Y') }} {{ $payment->created_at->format('g:i A') }}</td>
            </tr>
            <tr>
              <td class="rcpt-label">IVF Package</td>
              <td>: {{ $payment->patientPackage->ivfPackage->name }}</td>
            </tr>
            <tr>
              <td class="rcpt-label">Received By</td>
              <td>: {{ $payment->receivedBy->name }}</td>
            </tr>
          </table>
        </td>
      </tr>
    </table>
  </div>

  {{-- ═══ PACKAGE DETAILS TABLE ═══ --}}
  <table width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;">
    <thead>
      <tr>
        <th class="rcpt-th" style="text-align:left;width:60%;">Package Details</th>
        <th class="rcpt-th" style="text-align:right;width:40%;">Amount (BDT)</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td class="rcpt-td">Package Name</td>
        <td class="rcpt-td" style="text-align:right;">{{ $payment->patientPackage->ivfPackage->name }}</td>
      </tr>
      <tr>
        <td class="rcpt-td">Package Total</td>
        <td class="rcpt-td" style="text-align:right;">{{ number_format($payment->patientPackage->total_amount, 2) }}</td>
      </tr>
      @if($payment->patientPackage->discount > 0)
      <tr>
        <td class="rcpt-td">Discount</td>
        <td class="rcpt-td" style="text-align:right;color:#c00;">- {{ number_format($payment->patientPackage->discount, 2) }}</td>
      </tr>
      @endif
      <tr>
        <td class="rcpt-td" style="font-weight:bold;">Net Payable</td>
        <td class="rcpt-td" style="text-align:right;font-weight:bold;">{{ number_format($payment->patientPackage->net_amount, 2) }}</td>
      </tr>
    </tbody>
  </table>

  {{-- ═══ THIS PAYMENT TABLE ═══ --}}
  @php
    $mc = ['cash'=>'success','bank'=>'primary','card'=>'info','bkash'=>'danger','nagad'=>'warning','rocket'=>'secondary'];
    $remaining = $payment->patientPackage->remaining ?? 0;
  @endphp
  <table width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;margin-top:6px;">
    <thead>
      <tr>
        <th class="rcpt-th" style="text-align:left;width:60%;">This Payment</th>
        <th class="rcpt-th" style="text-align:right;width:40%;">Amount (BDT)</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td class="rcpt-td" style="font-weight:bold;">Amount Paid</td>
        <td class="rcpt-td" style="text-align:right;font-weight:bold;font-size:13px;">{{ number_format($payment->amount, 2) }}</td>
      </tr>
      <tr>
        <td class="rcpt-td">Payment Method</td>
        <td class="rcpt-td" style="text-align:right;">
          {{ ucfirst($payment->payment_method) }}
          @if($payment->transaction_id) &nbsp;(Txn: {{ $payment->transaction_id }})@endif
          @if($payment->bank_name) &nbsp;— {{ $payment->bank_name }}@endif
        </td>
      </tr>
      <tr>
        <td class="rcpt-td">Total Paid (All Instalments)</td>
        <td class="rcpt-td" style="text-align:right;">{{ number_format($payment->patientPackage->paid_amount, 2) }}</td>
      </tr>
      <tr>
        <td class="rcpt-td rcpt-sum-label" style="background:#e8f5e9;">Remaining Balance (BDT)</td>
        <td class="rcpt-sum-val" style="font-weight:bold;background:#e8f5e9;{{ $remaining > 0 ? 'color:#c00;' : 'color:#2e7d32;' }}">
          {{ $remaining > 0 ? number_format($remaining, 2) : 'FULLY PAID' }}
        </td>
      </tr>
    </tbody>
  </table>

  {{-- ═══ FOOTER ═══ --}}
  <div style="border-top:1px solid #555;padding:8px 14px;">
    <table width="100%" cellpadding="3" cellspacing="0">
      <tr>
        <td width="60%" style="vertical-align:top;font-size:11.5px;">
          <div><span class="rcpt-label">Amount in Words</span> : {{ \App\Models\Bill::amountInWords((float)$payment->amount) }}</div>
          <div style="margin-top:4px;"><span class="rcpt-label">Print Date &amp; Time</span> : {{ now()->format('d/m/Y h:i A') }}</div>
        </td>
        <td width="40%" style="vertical-align:top;text-align:right;font-size:11.5px;">
          <div><span class="rcpt-label">Patient Signature</span> : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
          <div style="margin-top:18px;"><span class="rcpt-label">Authorised By</span> : {{ $payment->receivedBy->name }}</div>
        </td>
      </tr>
    </table>
  </div>

  {{-- Computer generated note --}}
  <div style="text-align:center;border-top:1px solid #ddd;padding:4px;font-size:10px;color:#666;">This is a computer-generated receipt. No separate signature is required. &nbsp;|&nbsp; Thank you for choosing Meena IVF &amp; Fertility Care Limited.</div>

</div>
@endsection
