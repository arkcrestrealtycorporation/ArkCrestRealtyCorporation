@extends('layouts.dashboard')
@section('title', 'Site Visit Request Form')
@section('content')
<style>
.sv-wrap{padding:24px 30px;max-width:816px;margin:0 auto}
.sv-card{background:white;padding:32px 48px;border:1px solid #ccc;font-family:Arial,sans-serif;color:#000;width:816px;box-sizing:border-box;margin:0 auto;}
.sv-tbl{width:100%;border-collapse:collapse;font-size:12px;margin-bottom:10px;}
.sv-tbl td{border:1px solid #000;padding:5px 7px;}
.sv-tbl td.lbl{font-weight:700;white-space:nowrap;background:#fafafa;width:1%;}
.sv-tbl input,.sv-tbl select,.sv-tbl textarea{width:100%;border:none;outline:none;font-size:12px;font-family:Arial,sans-serif;background:transparent;padding:0;}
.sv-sigs{width:100%;border-collapse:collapse;font-size:11px;margin-top:0;}
.sv-sigs td{border:1px solid #000;padding:4px 8px;vertical-align:top;width:50%;}
.sv-btns{display:flex;justify-content:flex-end;gap:10px;margin-top:16px;}
.btn-clear-sv{padding:10px 24px;background:#f3f4f6;color:#374151;border:2px solid #d0d5dd;border-radius:8px;font-weight:600;font-size:14px;cursor:pointer;}
.btn-print-sv{display:inline-flex;align-items:center;gap:8px;padding:10px 24px;background:#1e4575;color:white;border:none;border-radius:8px;font-weight:600;font-size:14px;cursor:pointer;}
@media print{
  body *{visibility:hidden}
  .sv-card,.sv-card *{visibility:visible}
  .sv-card{position:fixed;top:0;left:0;width:100%;padding:0.35in 0.45in;border:none;box-shadow:none;font-size:11px;}
  .sv-btns{display:none!important}
  @page{size:8.5in 11in;margin:0}
}
</style>

<div class="sv-wrap">
<div class="sv-card" id="svCard">

  {{-- Header --}}
  <div style="display:flex;align-items:center;gap:14px;justify-content:center;margin-bottom:12px;">
    <img src="{{ asset('images/ArkCrest_Logo.png') }}" alt="Logo" style="width:80px;height:80px;object-fit:contain;flex-shrink:0;">
    <div style="text-align:center;">
      <div style="font-size:22px;font-weight:700;text-decoration:underline;color:#000;text-transform:uppercase;letter-spacing:.5px;">ARKCREST REALTY CORPORATION</div>
      <div style="font-size:22px;font-weight:700;color:#2563eb;margin-top:8px;letter-spacing:.5px;">SITE VISIT REQUEST FORM</div>
    </div>
  </div>

  {{-- Visit Info --}}
  <table class="sv-tbl">
    <tr>
      <td class="lbl">Agent Name:</td>
      <td><input type="text" id="sv_agent"></td>
      <td class="lbl">Date of Visit:</td>
      <td><input type="date" id="sv_date"></td>
    </tr>
    <tr>
      <td class="lbl">Time of Visit:</td>
      <td><input type="time" id="sv_time"></td>
      <td class="lbl">Type of Visit:</td>
      <td>
        <select id="sv_type">
          <option>Ocular</option>
          <option>Follow-up</option>
          <option>Closing</option>
          <option>Other</option>
        </select>
      </td>
    </tr>
  </table>

  <hr style="border:none;border-top:1.5px solid #000;margin:8px 0;">
  <div style="font-size:13px;font-weight:700;color:#1e3a8a;text-align:center;margin-bottom:8px;letter-spacing:.5px;">CLIENT INFORMATION</div>
  <table class="sv-tbl">
    <tr>
      <td class="lbl">Client Name:</td>
      <td colspan="3"><input type="text" id="sv_client"></td>
    </tr>
    <tr>
      <td class="lbl">Contact No.:</td>
      <td><input type="text" id="sv_phone"></td>
      <td class="lbl">Email:</td>
      <td><input type="text" id="sv_email"></td>
    </tr>
    <tr>
      <td class="lbl">Address:</td>
      <td colspan="3"><input type="text" id="sv_address"></td>
    </tr>
  </table>

  <hr style="border:none;border-top:1.5px solid #000;margin:8px 0;">
  <div style="font-size:13px;font-weight:700;color:#1e3a8a;text-align:center;margin-bottom:8px;letter-spacing:.5px;">PROPERTY DETAILS</div>
  <table class="sv-tbl">
    <tr>
      <td class="lbl">Property Name:</td>
      <td colspan="3"><input type="text" id="sv_property"></td>
    </tr>
    <tr>
      <td class="lbl">Developer:</td>
      <td><input type="text" id="sv_developer"></td>
      <td class="lbl">Location:</td>
      <td><input type="text" id="sv_location"></td>
    </tr>
  </table>

  <hr style="border:none;border-top:1.5px solid #000;margin:8px 0;">
  <div style="font-size:13px;font-weight:700;color:#1e3a8a;text-align:center;margin-bottom:8px;letter-spacing:.5px;">REMARKS / NOTES</div>
  <table class="sv-tbl" style="margin-bottom:16px;">
    <tr>
      <td style="height:60px;vertical-align:top;padding:6px 8px;">
        <textarea id="sv_remarks" style="width:100%;height:54px;resize:none;"></textarea>
      </td>
    </tr>
  </table>

  {{-- Signatures --}}
  <table class="sv-sigs">
    <tr>
      <td style="font-size:11px;font-weight:700;">Prepared by:</td>
      <td style="font-size:11px;font-weight:700;">Noted by:</td>
    </tr>
    <tr>
      <td><div style="height:40px;"></div></td>
      <td><div style="height:40px;"></div></td>
    </tr>
    <tr>
      <td style="font-size:11px;border-top:1px solid #000;">Agent Signature over Printed Name</td>
      <td style="font-size:11px;border-top:1px solid #000;">Sales Manager / Admin</td>
    </tr>
    <tr>
      <td style="font-size:11px;">Date: _______________</td>
      <td style="font-size:11px;">Date: _______________</td>
    </tr>
  </table>

  {{-- Buttons --}}
  <div class="sv-btns">
    <button class="btn-clear-sv" onclick="clearSV()">Clear</button>
    <button class="btn-print-sv" onclick="window.print()">
      <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:18px;height:18px"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
      Print
    </button>
  </div>

</div>
</div>

<script>
function clearSV() {
    ['sv_agent','sv_date','sv_time','sv_client','sv_phone','sv_email','sv_address','sv_property','sv_developer','sv_location','sv_remarks'].forEach(function(id){
        var el = document.getElementById(id); if(el) el.value = '';
    });
    document.getElementById('sv_type').value = 'Ocular';
}
</script>
@endsection
