<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Verify Email — ARCKREST REALTY CORPORATION</title>
<style>
*,*::before,*::after{margin:0;padding:0;box-sizing:border-box}
html,body{height:100%;overflow:hidden;font-family:"Segoe UI",system-ui,sans-serif}
body{display:flex;align-items:center;justify-content:center;background:linear-gradient(135deg,#0a1628 0%,#1a3a6b 50%,#0f2a4a 100%)}
.card{position:relative;width:920px;max-width:96vw;height:600px;border-radius:24px;overflow:hidden;box-shadow:0 40px 100px rgba(0,0,0,.55);background:white;display:flex}
.overlay{position:absolute;top:0;right:0;width:50%;height:100%;background:linear-gradient(150deg,#1e4575 0%,#0f2a4a 100%);display:flex;flex-direction:column;align-items:center;justify-content:space-between;padding:40px 36px;text-align:center;z-index:10;overflow:hidden}
.overlay::before{content:"";position:absolute;width:280px;height:280px;border-radius:50%;background:radial-gradient(circle,rgba(163,121,41,.18),transparent 65%);top:-80px;left:-80px;pointer-events:none;animation:pulse 4s ease-in-out infinite}
.brand{display:flex;flex-direction:column;align-items:center;gap:10px;position:relative;z-index:1}
.brand-logo{width:80px;height:80px}
.brand-logo img{width:100%;height:100%;object-fit:contain}
.brand-name{font-size:32px;font-weight:800;letter-spacing:1px;text-transform:uppercase;line-height:1.3;text-align:center;background:linear-gradient(90deg,#ffffff,#d4a855,#ffffff);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;background-size:200% auto;animation:shimmer 3s linear infinite}
.ov-body{position:relative;z-index:1}
.ov-eyebrow{font-size:22px;font-weight:700;color:white;margin-bottom:8px}
.ov-body h2{font-size:17px;font-weight:600;color:rgba(255,255,255,.75);margin-bottom:18px;white-space:nowrap}
.ov-body h2 span{color:#d4a855;font-weight:600}
.ov-body p{font-size:13px;color:rgba(255,255,255,.55);line-height:1.7;margin-bottom:28px}
.btn-outline{display:inline-block;padding:12px 36px;border:none;color:#0f172a;border-radius:30px;font-size:12px;font-weight:700;letter-spacing:1px;text-transform:uppercase;cursor:pointer;background:linear-gradient(135deg,#d4a855,#f0c96a);box-shadow:0 4px 20px rgba(212,168,85,.4);transition:all .25s;text-decoration:none}
.btn-outline:hover{transform:translateY(-2px);box-shadow:0 8px 28px rgba(212,168,85,.5)}
.ov-footer{position:relative;z-index:1;font-size:10px;color:rgba(255,255,255,.3);letter-spacing:.5px}
.form-area{width:50%;height:100%;display:flex;flex-direction:column;padding:32px 44px;background:linear-gradient(160deg,#f8fafc 0%,#ffffff 60%,#f0f4ff 100%);justify-content:center;}
.step-circle{width:24px;height:24px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:10px;font-weight:700;flex-shrink:0}
.step-circle.active{background:linear-gradient(135deg,#1e4575,#2563eb);color:white;box-shadow:0 2px 8px rgba(30,69,117,.3);border:none}
.step-circle.done{background:#d1fae5;color:#059669;border:none}
.step-circle.inactive{background:#f9fafb;color:#94a3b8;border:2px solid #e2e8f0}
.step-line{flex:1;height:2px;margin-top:11px;margin-left:4px;margin-right:4px}
.step-line.done{background:#2563eb}
.step-line.inactive{background:#e2e8f0}
.form-title{font-size:22px;font-weight:800;color:#0f172a;margin-bottom:2px;letter-spacing:-0.5px}
.form-sub{font-size:11px;color:#94a3b8;margin-bottom:20px;line-height:1.5}
.alert-error{background:#fef2f2;border-left:3px solid #ef4444;color:#dc2626;padding:10px 14px;border-radius:8px;font-size:12px;margin-bottom:12px}
.alert-success{background:#f0fdf4;border-left:3px solid #22c55e;color:#16a34a;padding:10px 14px;border-radius:8px;font-size:12px;margin-bottom:12px}
.email-badge{display:inline-block;background:#eff6ff;color:#1e4575;font-size:12px;font-weight:600;padding:6px 16px;border-radius:20px;margin-bottom:20px}
.otp-wrap{display:flex;gap:10px;margin-bottom:24px}
.otp-wrap input{width:48px;height:54px;border:1.5px solid #e2e8f0;border-radius:10px;font-size:22px;font-weight:700;text-align:center;color:#0f172a;background:#fafafa;transition:all .2s}
.otp-wrap input:focus{outline:none;border-color:#1e4575;background:white;box-shadow:0 0 0 3px rgba(30,69,117,.08)}
.btn-primary{width:100%;padding:13px;background:linear-gradient(135deg,#1e4575 0%,#2563eb 60%,#1e4575 100%);background-size:200% auto;color:white;border:none;border-radius:12px;font-size:13px;font-weight:700;letter-spacing:1.2px;text-transform:uppercase;cursor:pointer;box-shadow:0 6px 20px rgba(30,69,117,.35);transition:all .3s;animation:btnShimmer 3s linear infinite;margin-bottom:14px}
.btn-primary:hover{transform:translateY(-2px);box-shadow:0 8px 24px rgba(30,69,117,.4)}
.back-link{font-size:12px;color:#94a3b8;text-align:center}
.back-link a{color:#1e4575;font-weight:600;text-decoration:none}
@keyframes shimmer{0%{background-position:200% center}100%{background-position:-200% center}}
@keyframes btnShimmer{0%{background-position:200% center}100%{background-position:-200% center}}
@keyframes pulse{0%,100%{transform:scale(1);opacity:.18}50%{transform:scale(1.08);opacity:.25}}
</style>
</head>
<body>
<div class="card">
  {{-- FORM SIDE (left) --}}
  <div class="form-area">
    <div style="display:flex;align-items:flex-start;margin-bottom:10px;flex-shrink:0;">
      <div style="display:flex;flex-direction:column;align-items:center;gap:3px;">
        <div class="step-circle done" style="cursor:pointer;" onclick="goBackToStep(1)">&#10003;</div>
        <span style="font-size:8px;font-weight:700;color:#059669;white-space:nowrap;">Your Info</span>
      </div>
      <div class="step-line done"></div>
      <div style="display:flex;flex-direction:column;align-items:center;gap:3px;">
        <div class="step-circle done" style="cursor:pointer;" onclick="goBackToStep(2)">&#10003;</div>
        <span style="font-size:8px;font-weight:700;color:#059669;white-space:nowrap;">Security</span>
      </div>
      <div class="step-line done"></div>
      <div style="display:flex;flex-direction:column;align-items:center;gap:3px;">
        <div class="step-circle active">3</div>
        <span style="font-size:8px;font-weight:700;color:#1e4575;white-space:nowrap;">Verify</span>
      </div>
      <div class="step-line inactive"></div>
      <div style="display:flex;flex-direction:column;align-items:center;gap:3px;">
        <div class="step-circle inactive">4</div>
        <span style="font-size:8px;color:#94a3b8;white-space:nowrap;">Approval</span>
      </div>
    </div>
    <div class="form-title">Check your email</div>
    <div class="form-sub">Step 3 of 4 &mdash; We sent a 6-digit code to</div>
    <div class="email-badge">{{ $email }}</div>
    @if(session('success'))<div class="alert-success">{{ session('success') }}</div>@endif
    @if($errors->any())<div class="alert-error">{{ $errors->first() }}</div>@endif
    <div style="flex:1;">
      <form method="POST" action="{{ route('register.verify.post') }}" id="otpForm">
        @csrf
        <input type="hidden" name="email" value="{{ $email }}">
        <div class="otp-wrap">
          @for($i = 0; $i < 6; $i++)
          <input type="text" maxlength="1" class="otp-digit" inputmode="numeric" pattern="[0-9]" autocomplete="off">
          @endfor
        </div>
        <input type="hidden" name="code" id="codeInput">
      </form>
    </div>
    <div style="flex-shrink:0;margin-top:10px;">
      <button type="submit" form="otpForm" class="btn-primary">Verify &amp; Create Account</button>

    </div>
  </div>
  {{-- OVERLAY SIDE (right) --}}
  <div class="overlay">
    <div class="brand">
      <div class="brand-logo"><img src="{{ asset('images/ArkCrest_Logo.png') }}" alt="Arckrest"></div>
      <div class="brand-name">ARCKREST REALTY CORPORATION</div>
    </div>
    <div class="ov-body">
      <div class="ov-eyebrow">Almost There!</div>
      <h2>Already have an <span>Account?</span></h2>
      <p>ArkCrest Realty: Making your real estate dreams a reality with expert support and exceptional service.</p>
      <a href="{{ route('login') }}" class="btn-outline">Sign In</a>
    </div>
    <div class="ov-footer">&copy; {{ date('Y') }} Arckrest Realty Corporation</div>
  </div>
</div>
<script>
function goBackToStep(step){
  sessionStorage.setItem('reg_back_step', step);
  window.location.href = '{{ route("login") }}?register=1';
}
var digits = document.querySelectorAll('.otp-digit');
digits.forEach(function(d, i) {
  d.addEventListener('input', function() {
    this.value = this.value.replace(/[^0-9]/g, '');
    if (this.value && i < 5) digits[i+1].focus();
    updateCode();
  });
  d.addEventListener('keydown', function(e) {
    if (e.key === 'Backspace' && !this.value && i > 0) digits[i-1].focus();
  });
  d.addEventListener('paste', function(e) {
    var paste = (e.clipboardData || window.clipboardData).getData('text').replace(/\D/g,'');
    paste.split('').forEach(function(c, j) { if (digits[j]) digits[j].value = c; });
    updateCode();
    e.preventDefault();
  });
});
function updateCode() {
  document.getElementById('codeInput').value = Array.from(digits).map(function(d){return d.value;}).join('');
}
document.getElementById('otpForm').addEventListener('submit', function() { updateCode(); });
digits[0].focus();
</script>
</body>
</html>
