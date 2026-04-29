@extends('layouts.dashboard')
@section('title', 'Budget Request Form')
@section('content')
<style>
.frm-wrap{padding:24px 30px;max-width:816px;margin:0 auto}
.btn-clear-f{padding:10px 24px;background:#f3f4f6;color:#374151;border:2px solid #d0d5dd;border-radius:8px;font-weight:600;font-size:14px;cursor:pointer}
.btn-print-f{display:inline-flex;align-items:center;gap:8px;padding:10px 24px;background:#1e4575;color:white;border:none;border-radius:8px;font-weight:600;font-size:14px;cursor:pointer}
.frm-card{background:white;padding:32px 48px 32px 48px;border:1px solid #ccc;font-family:Arial,sans-serif;color:#000;width:816px;box-sizing:border-box;margin:0 auto;}
.frm-hdr{display:flex;align-items:center;justify-content:flex-start;gap:16px;margin-bottom:4px}
.frm-logo{width:80px;height:80px;flex-shrink:0;}
.frm-logo img{width:100%;height:100%;object-fit:contain}
.frm-title-block .dept{font-size:20px;font-weight:900;text-decoration:underline;color:#000;letter-spacing:.3px}
.frm-title-block .form-name{font-size:14px;font-weight:700;color:#1e3a8a;margin-top:2px}
.ctrl-num{text-align:center;font-size:13px;font-weight:700;color:#dc2626;margin:8px 0 10px;letter-spacing:.5px}
.info-tbl{width:100%;border-collapse:collapse;font-size:12px;margin-bottom:0}
.info-tbl td{border:1px solid #000;padding:5px 7px}
.info-tbl td.lbl{font-weight:700;white-space:nowrap;background:#fafafa;width:1%}
.info-tbl input,.info-tbl select{width:100%;border:none;outline:none;font-size:12px;font-family:Arial,sans-serif;background:transparent;padding:0}
.frm-note{font-size:11px;color:#dc2626;margin:4px 0 4px;line-height:1.5}
.frm-divider{border:none;border-top:1.5px solid #333;margin:10px 0}
.liq-hdr{text-align:center;font-size:15px;font-weight:700;color:#1e3a8a;margin:0 0 6px;letter-spacing:.5px}
.liq-tbl{width:100%;border-collapse:collapse;font-size:11px}
.liq-tbl th,.liq-tbl td{border:1px solid #000;padding:1px 4px;text-align:center;height:20px;}
.liq-tbl th{background:#f0f0f0;font-weight:700;font-size:11px}
.liq-tbl td.amt{text-align:left;padding-left:5px}
.cert{font-size:11px;margin:8px 0 10px;line-height:1.4}
.sigs{width:100%;border-collapse:collapse;font-size:11px;margin-top:0}
.sigs td{border:1px solid #000;padding:4px 8px;vertical-align:top;width:33.33%}
.sig-space{height:30px}
.dept-sel{font-size:12px;color:#555;margin-top:10px}
.dept-sel select{font-size:12px;padding:2px 6px;border:1px solid #ccc;border-radius:4px}
.frm-btns{display:flex;justify-content:flex-end;gap:10px;margin-top:16px}
@media print{
  body *{visibility:hidden}
  .frm-card,.frm-card *{visibility:visible}
  .frm-card{
    position:fixed;top:0;left:0;
    width:100%;height:auto;
    padding:0.35in 0.45in;
    border:none;
    box-shadow:none;
    font-size:11px;
  }
  .dept-sel,.frm-btns{display:none!important}
  @page{size:8.5in 11in;margin:0}
}
</style>

<div class="frm-wrap">

  {{-- Form Tabs --}}
  <div style="display:flex;gap:10px;margin-bottom:20px;">
    <button id="tab-budget" onclick="switchForm('budget')" style="padding:10px 24px;border-radius:8px;font-size:14px;font-weight:700;cursor:pointer;border:2px solid #1e4575;background:#1e4575;color:white;transition:all .2s;">
      📋 Budget Request Form
    </button>
    <button id="tab-sitevisit" onclick="switchForm('sitevisit')" style="padding:10px 24px;border-radius:8px;font-size:14px;font-weight:700;cursor:pointer;border:2px solid #1e4575;background:white;color:#1e4575;transition:all .2s;">
      🏠 Site Visit Form
    </button>
  </div>

  {{-- Budget Request Form --}}
  <div id="form-budget">
  <div class="frm-card" id="frmCard">

    <!-- Header -->
    <div style="display:flex;flex-direction:column;align-items:center;margin-bottom:6px;">
      <div style="display:flex;align-items:center;gap:14px;justify-content:center;">
        <img src="{{ asset('images/ArkCrest_Logo.png') }}" alt="Logo" style="width:80px;height:80px;object-fit:contain;flex-shrink:0;">
        <div style="text-align:center;">
          <div id="disp_dept" style="font-size:24px;font-weight:700;text-decoration:underline;color:#000;text-transform:uppercase;letter-spacing:.5px;">HUMAN RESOURCES DEPARTMENT</div>
          <div style="font-size:24px;font-weight:700;color:#2563eb;margin-top:10px;letter-spacing:.5px;">BUDGET REQUEST FORM</div>
          <div class="dept-sel" style="margin-top:6px;">
            <select id="f_dept" onchange="updDept()" style="font-size:12px;padding:3px 8px;border:1px solid #ccc;border-radius:4px;">
              @foreach($departments->where('slug', '!=', 'capex') as $dept)
              <option value="{{ strtoupper($dept->name) }}">{{ $dept->name }} Department</option>
              @endforeach
            </select>
          </div>
        </div>
      </div>
    </div>
    <div style="text-align:right;font-size:16px;font-weight:700;color:#dc2626;margin-bottom:4px;margin-top:10px;letter-spacing:.3px;">Control Number: <span id="ctrlNumDisplay">Loading...</span></div>
    <table class="info-tbl">
      <tr>
        <td class="lbl">Name:</td>
        <td><input type="text" id="f_name" autocomplete="off" onblur="addToNameList(this.value)" oninput="showNameSuggestions(this.value)" style="width:100%;border:none;outline:none;font-size:12px;font-family:Arial,sans-serif;background:transparent;padding:0">
        <div id="nameSuggestBox" style="display:none;position:absolute;background:white;border:1px solid #ccc;border-radius:4px;z-index:9999;min-width:200px;max-height:150px;overflow-y:auto;box-shadow:0 2px 8px rgba(0,0,0,.15);font-size:12px;font-family:Arial,sans-serif;"></div></td>
        <td class="lbl">Date Requested:</td>
        <td><input type="date" id="f_date_req"></td>
      </tr>
      <tr>
        <td class="lbl">Amount Requested: &#8369;</td>
        <td><input type="number" id="f_amount" placeholder="0.00" step="0.01" min="0"></td>
        <td class="lbl">Target Date Released:</td>
        <td><input type="date" id="f_target"></td>
      </tr>
      <tr>
        <td class="lbl">Particular :</td>
        <td>
          <input type="text" id="f_cat" list="f_cat_list" placeholder="Select or type..." autocomplete="off"
            style="width:100%;border:none;outline:none;font-size:12px;font-family:Arial,sans-serif;background:transparent;padding:0">
          <datalist id="f_cat_list"></datalist>
        </td>
        <td class="lbl">Remarks:</td>
        <td><input type="text" id="f_remarks"></td>
      </tr>
    </table>

    <!-- Note -->
    <div class="frm-note">
      <strong>Note:</strong> (a) For amount less than <strong>&#8369;1,000.00</strong> disbursement will be processed with in the day<br>
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(b) Amount of <strong>&#8369;1,000.00</strong> or more than will be disbursed at least one week after the submission.
    </div>

    <!-- Liquidation Report -->
    <hr style="border:none;border-top:1.5px solid #000;margin:10px 0 8px 0;">
    <p class="liq-hdr" style="color:#2563eb;font-weight:700;font-size:20px;text-align:center;margin:0 0 8px 0;letter-spacing:.5px;">LIQUIDATION REPORT</p>
    <table class="liq-tbl">
      <thead>
        <tr>
          <th style="width:13%">DATE</th>
          <th style="width:20%">RECEIPT / INVOICE NO.</th>
          <th style="width:47%">PARTICULARS</th>
          <th style="width:20%">AMOUNT</th>
        </tr>
      </thead>
      <tbody>
        @for($i=0;$i<25;$i++)
        <tr>
          <td>&nbsp;</td>
          <td></td>
          <td><input type="text" style="width:100%;border:none;outline:none;font-size:11px;font-family:Arial,sans-serif;background:transparent;padding:0;"></td>
          <td class="amt">&#8369;</td>
        </tr>
        @endfor
      </tbody>
    </table>
    <table style="width:100%;border-collapse:collapse;font-size:11px;font-weight:700;margin-top:10px;">
      <tr>
        <td style="border:1px solid #000;padding:5px 7px;width:50%;">TOTAL EXPENSES: &#8369; _______________</td>
        <td style="border:1px solid #000;padding:5px 7px;width:50%;">LESS CASH ADVANCE: &#8369; _______________</td>
      </tr>
      <tr>
        <td colspan="2" style="border:1px solid #000;padding:5px 7px;">AMOUNT TO BE RETURNED: &#8369; _______________</td>
      </tr>
    </table>

    <!-- Certification -->
    <p style="font-size:11px;font-weight:700;margin:6px 0 8px 0;padding:0;line-height:1.4;">This is to certify that the foregoing expenses were disbursed in conformity with the above stated purpose(s).</p>

    <!-- Signatures -->
    <table class="sigs">
      <tr>
        <td style="padding:4px 8px;font-size:11px;font-weight:400;">Checked &amp; Approved by:</td>
        <td style="padding:4px 8px;font-size:11px;font-weight:400;">Released by:</td>
        <td style="padding:4px 8px;font-size:11px;font-weight:400;">Received by:</td>
      </tr>
      <tr>
        <td style="padding:4px 8px;height:50px;vertical-align:bottom;font-size:11px;font-weight:700;">
          <input type="text" id="f_approved_by" style="border:none;outline:none;font-size:11px;font-weight:700;font-family:Arial,sans-serif;background:transparent;width:100%;padding:0;text-align:center;text-transform:uppercase;" placeholder="NAME">
        </td>
        <td style="padding:4px 8px;height:50px;vertical-align:bottom;font-size:11px;"></td>
        <td style="padding:4px 8px;height:50px;vertical-align:bottom;font-size:11px;"></td>
      </tr>
      <tr>
        <td style="padding:4px 8px;font-size:11px;">Date: _______________</td>
        <td style="padding:4px 8px;font-size:11px;">Date: _______________</td>
        <td style="padding:4px 8px;font-size:11px;">Date: _______________</td>
      </tr>
    </table>

    <!-- Buttons (screen only) -->
    <div class="frm-btns dept-sel">
      <button class="btn-clear-f" onclick="clearForm()">Clear</button>
      <button class="btn-print-f" onclick="openPreview()">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:18px;height:18px"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
        View
      </button>
    </div>

  </div>
</div>

{{-- Preview Modal --}}
<div id="frmPreviewModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.6);z-index:9999;align-items:flex-start;justify-content:center;overflow-y:auto;padding:32px 16px;">
  <div style="background:white;border-radius:16px;width:100%;max-width:820px;box-shadow:0 20px 60px rgba(0,0,0,.3);overflow:hidden;">
    {{-- Modal Header --}}
    <div style="background:linear-gradient(135deg,#1e4575,#2563eb);padding:16px 24px;display:flex;align-items:center;justify-content:space-between;">
      <div style="color:white;font-weight:700;font-size:16px;">Budget Request Form � Preview</div>
      <div style="display:flex;gap:10px;align-items:center;">
        <button onclick="incrementAndPrint()" style="display:inline-flex;align-items:center;gap:6px;padding:8px 18px;background:rgba(255,255,255,.15);color:white;border:1px solid rgba(255,255,255,.3);border-radius:8px;font-size:13px;font-weight:600;cursor:pointer;">
          <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:16px;height:16px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
          Print
        </button>
        <button onclick="closePreview()" style="background:rgba(255,255,255,.15);border:1px solid rgba(255,255,255,.3);color:white;width:34px;height:34px;border-radius:8px;cursor:pointer;font-size:18px;display:flex;align-items:center;justify-content:center;">&times;</button>
      </div>
    </div>
    {{-- Modal Body: cloned form --}}
    <div style="padding:20px;display:flex;justify-content:center;"><div id="frmPreviewBody" style="background:white;box-shadow:0 4px 24px rgba(0,0,0,.3);width:816px;"></div></div>
  </div>
</div>

<script>
// Dynamic categories from DB
var _deptCategories = {};
@foreach($departments->where('slug', '!=', 'capex') as $dept)
_deptCategories['{{ strtoupper($dept->name) }}'] = [
  @foreach($dept->categories as $cat)'{{ addslashes($cat->name) }}',@endforeach
];
@endforeach

function updDept(){
  var v = document.getElementById('f_dept').value;
  document.getElementById('disp_dept').textContent = v ? v + ' DEPARTMENT' : 'DEPARTMENT';

  // Rebuild the datalist for the Particular combobox
  var list = document.getElementById('f_cat_list');
  list.innerHTML = '';
  var cats = _deptCategories[v] || [];
  cats.forEach(function(cat){
    var opt = document.createElement('option');
    opt.value = cat;
    list.appendChild(opt);
  });

  // Clear current value
  document.getElementById('f_cat').value = '';
}
// Name suggestions from DB (past requestors)
window._frmNames = @json($requestorNames ?? []);
function showNameSuggestions(val){
  var box=document.getElementById('nameSuggestBox');
  if(!val||val.trim().length<2){box.style.display='none';return;}
  var names=window._frmNames||[];
  var q=val.toLowerCase();
  var matches=names.filter(function(n){return n.toLowerCase().indexOf(q)===0;});
  if(!matches.length){box.style.display='none';return;}
  box.innerHTML=matches.map(function(n){
    return '<div style="padding:7px 12px;cursor:pointer;" onmousedown="pickName(\''+n.replace(/'/g,"\\'")+'\')" onmouseover="this.style.background=\'#f1f5f9\'" onmouseout="this.style.background=\'white\'">'+n+'</div>';
  }).join('');
  box.style.display='block';
}
function pickName(n){
  document.getElementById('f_name').value=n;
  document.getElementById('nameSuggestBox').style.display='none';
}
function addToNameList(val){
  document.getElementById('nameSuggestBox').style.display='none';
}
document.addEventListener('click',function(e){
  if(e.target.id!=='f_name') document.getElementById('nameSuggestBox').style.display='none';
});
function clearForm(){
  ['f_name','f_date_req','f_target','f_amount','f_remarks','f_cat'].forEach(function(id){document.getElementById(id).value='';});
  document.getElementById('f_dept').value='HUMAN RESOURCES';
  updDept();
}
function openPreview(){
  var clone=document.getElementById('frmCard').cloneNode(true);
  // Remove buttons and dept selector from clone
  clone.querySelectorAll('.frm-btns,.dept-sel').forEach(function(el){el.remove();});
  document.getElementById('frmPreviewBody').innerHTML='';
  document.getElementById('frmPreviewBody').appendChild(clone);
  var modal=document.getElementById('frmPreviewModal');
  modal.style.display='flex';
}
function closePreview(){
  document.getElementById('frmPreviewModal').style.display='none';
}
// Load control number on page load
var _ctrlNum = '';
fetch('{{ url("/api/forms/control-number") }}')
  .then(function(r){return r.json();})
  .then(function(d){
    _ctrlNum = d.control_number;
    // Format: ARCS-03-001-26 → underline only the number parts
    var parts = d.control_number.split('-'); // ['ARCS','03','001','26']
    var html = parts[0] + '-'
      + '<span style="text-decoration:underline;">' + parts[1] + '</span>-'
      + '<span style="text-decoration:underline;">' + parts[2] + '</span>-'
      + '<span style="text-decoration:underline;">' + parts[3] + '</span>';
    document.getElementById('ctrlNumDisplay').innerHTML = html;
  });

function incrementAndPrint(){
  fetch('{{ url("/api/forms/control-number/increment") }}', {
    method:'POST',
    headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}','Content-Type':'application/json'}
  }).then(function(){window.print();});
}

function downloadPDF(){
  var btn = document.querySelector('[onclick="downloadPDF()"]');
  if(btn){ btn.disabled=true; btn.textContent='Generating...'; }

  // Get the form element to capture
  var el = document.getElementById('frmCard');

  // Hide screen-only elements temporarily
  el.querySelectorAll('.frm-btns,.dept-sel').forEach(function(e){ e.style.display='none'; });

  // Get control number for filename
  var ctrlNum = document.getElementById('ctrlNumDisplay') ? document.getElementById('ctrlNumDisplay').textContent.trim() : 'form';

  fetch('{{ url("/api/forms/control-number/increment") }}', {
    method:'POST',
    headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}','Content-Type':'application/json'}
  }).then(function(){
    html2canvas(el, {scale:3, useCORS:true, backgroundColor:'#ffffff', logging:false}).then(function(canvas){
      var imgData = canvas.toDataURL('image/jpeg', 1.0);
      var pdf = new window.jspdf.jsPDF({orientation:'portrait', unit:'in', format:'letter'});
      pdf.addImage(imgData, 'JPEG', 0, 0, 8.5, 11);
      pdf.save(ctrlNum + '.pdf');

      // Restore hidden elements
      el.querySelectorAll('.frm-btns,.dept-sel').forEach(function(e){ e.style.display=''; });
      if(btn){ btn.disabled=false; btn.innerHTML='<svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:15px;height:15px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg> Download PDF'; }
    });
  });
}
// Close modal on backdrop click
document.getElementById('frmPreviewModal').addEventListener('click',function(e){
  if(e.target===this)closePreview();
});

// Populate categories for default department on load
document.addEventListener('DOMContentLoaded', function(){ updDept(); });
</script>

<!-- html2canvas + jsPDF for real PDF download -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

</div>{{-- #form-budget --}}

{{-- ── SITE VISIT FORM ──────────────────────────────────────────────────── --}}
<div id="form-sitevisit" style="display:none;">
<div class="frm-card" id="svCard" style="width:816px;box-sizing:border-box;margin:0 auto;background:white;padding:32px 48px;border:1px solid #ccc;font-family:Arial,sans-serif;color:#000;">

  <div style="display:flex;align-items:center;gap:14px;justify-content:center;margin-bottom:10px;">
    <img src="{{ asset('images/ArkCrest_Logo.png') }}" alt="Logo" style="width:80px;height:80px;object-fit:contain;flex-shrink:0;">
    <div style="text-align:center;">
      <div style="font-size:22px;font-weight:700;text-decoration:underline;color:#000;text-transform:uppercase;letter-spacing:.5px;">ARKCREST REALTY CORPORATION</div>
      <div style="font-size:22px;font-weight:700;color:#2563eb;margin-top:8px;letter-spacing:.5px;">SITE VISIT REQUEST FORM</div>
    </div>
  </div>

  <table class="info-tbl" style="margin-bottom:10px;">
    <tr>
      <td class="lbl">Agent Name:</td>
      <td><input type="text" id="sv_agent" style="width:100%;border:none;outline:none;font-size:12px;font-family:Arial,sans-serif;background:transparent;"></td>
      <td class="lbl">Date of Visit:</td>
      <td><input type="date" id="sv_date"></td>
    </tr>
    <tr>
      <td class="lbl">Time of Visit:</td>
      <td><input type="time" id="sv_time"></td>
      <td class="lbl">Type of Visit:</td>
      <td>
        <select id="sv_type" style="width:100%;border:none;outline:none;font-size:12px;font-family:Arial,sans-serif;background:transparent;">
          <option>Ocular</option><option>Follow-up</option><option>Closing</option><option>Other</option>
        </select>
      </td>
    </tr>
  </table>

  <hr style="border:none;border-top:1.5px solid #000;margin:8px 0;">
  <div style="font-size:13px;font-weight:700;color:#1e3a8a;text-align:center;margin-bottom:8px;letter-spacing:.5px;">CLIENT INFORMATION</div>
  <table class="info-tbl" style="margin-bottom:10px;">
    <tr>
      <td class="lbl">Client Name:</td>
      <td colspan="3"><input type="text" id="sv_client" style="width:100%;border:none;outline:none;font-size:12px;font-family:Arial,sans-serif;background:transparent;"></td>
    </tr>
    <tr>
      <td class="lbl">Contact No.:</td>
      <td><input type="text" id="sv_phone" style="width:100%;border:none;outline:none;font-size:12px;font-family:Arial,sans-serif;background:transparent;"></td>
      <td class="lbl">Email:</td>
      <td><input type="text" id="sv_email" style="width:100%;border:none;outline:none;font-size:12px;font-family:Arial,sans-serif;background:transparent;"></td>
    </tr>
    <tr>
      <td class="lbl">Address:</td>
      <td colspan="3"><input type="text" id="sv_address" style="width:100%;border:none;outline:none;font-size:12px;font-family:Arial,sans-serif;background:transparent;"></td>
    </tr>
  </table>

  <hr style="border:none;border-top:1.5px solid #000;margin:8px 0;">
  <div style="font-size:13px;font-weight:700;color:#1e3a8a;text-align:center;margin-bottom:8px;letter-spacing:.5px;">PROPERTY DETAILS</div>
  <table class="info-tbl" style="margin-bottom:10px;">
    <tr>
      <td class="lbl">Property Name:</td>
      <td colspan="3"><input type="text" id="sv_property" style="width:100%;border:none;outline:none;font-size:12px;font-family:Arial,sans-serif;background:transparent;"></td>
    </tr>
    <tr>
      <td class="lbl">Developer:</td>
      <td><input type="text" id="sv_developer" style="width:100%;border:none;outline:none;font-size:12px;font-family:Arial,sans-serif;background:transparent;"></td>
      <td class="lbl">Location:</td>
      <td><input type="text" id="sv_location" style="width:100%;border:none;outline:none;font-size:12px;font-family:Arial,sans-serif;background:transparent;"></td>
    </tr>
  </table>

  <hr style="border:none;border-top:1.5px solid #000;margin:8px 0;">
  <div style="font-size:13px;font-weight:700;color:#1e3a8a;text-align:center;margin-bottom:8px;letter-spacing:.5px;">REMARKS / NOTES</div>
  <table class="info-tbl" style="margin-bottom:16px;">
    <tr><td style="height:60px;vertical-align:top;padding:6px 8px;">
      <textarea id="sv_remarks" style="width:100%;height:54px;border:none;outline:none;font-size:12px;font-family:Arial,sans-serif;background:transparent;resize:none;"></textarea>
    </td></tr>
  </table>

  <table class="sigs">
    <tr>
      <td style="width:50%;padding:4px 8px;font-size:11px;font-weight:700;">Prepared by:</td>
      <td style="width:50%;padding:4px 8px;font-size:11px;font-weight:700;">Noted by:</td>
    </tr>
    <tr>
      <td style="padding:4px 8px;"><div style="height:36px;"></div></td>
      <td style="padding:4px 8px;"><div style="height:36px;"></div></td>
    </tr>
    <tr>
      <td style="padding:4px 8px;font-size:11px;border-top:1px solid #000;">Agent Signature over Printed Name</td>
      <td style="padding:4px 8px;font-size:11px;border-top:1px solid #000;">Sales Manager / Admin</td>
    </tr>
    <tr>
      <td style="padding:4px 8px;font-size:11px;">Date: _______________</td>
      <td style="padding:4px 8px;font-size:11px;">Date: _______________</td>
    </tr>
  </table>

  <div class="sv-noprint" style="display:flex;justify-content:flex-end;gap:10px;margin-top:16px;">
    <button class="btn-clear-f" onclick="clearSVForm()">Clear</button>
    <button class="btn-print-f" onclick="printSVForm()">
      <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:18px;height:18px"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
      Print
    </button>
  </div>

</div>
</div>{{-- #form-sitevisit --}}

</div>{{-- .frm-wrap --}}

<script>
function switchForm(type) {
    document.getElementById('form-budget').style.display    = type === 'budget'    ? '' : 'none';
    document.getElementById('form-sitevisit').style.display = type === 'sitevisit' ? '' : 'none';
    document.getElementById('tab-budget').style.background    = type === 'budget'    ? '#1e4575' : 'white';
    document.getElementById('tab-budget').style.color         = type === 'budget'    ? 'white'   : '#1e4575';
    document.getElementById('tab-sitevisit').style.background = type === 'sitevisit' ? '#1e4575' : 'white';
    document.getElementById('tab-sitevisit').style.color      = type === 'sitevisit' ? 'white'   : '#1e4575';
}
function clearSVForm() {
    ['sv_agent','sv_date','sv_time','sv_client','sv_phone','sv_email','sv_address','sv_property','sv_developer','sv_location','sv_remarks'].forEach(function(id){
        var el = document.getElementById(id); if(el) el.value='';
    });
    document.getElementById('sv_type').value = 'Ocular';
}
function printSVForm() {
    document.querySelectorAll('.sv-noprint').forEach(function(e){ e.style.display='none'; });
    var orig = document.body.innerHTML;
    document.body.innerHTML = document.getElementById('svCard').outerHTML;
    window.print();
    document.body.innerHTML = orig;
    window.location.reload();
}
</script>

@endsection

