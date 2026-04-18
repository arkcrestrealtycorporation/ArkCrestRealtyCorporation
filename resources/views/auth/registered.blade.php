<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Account Created — ARCKREST REALTY CORPORATION</title>
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
.ov-body h2{font-size:17px;font-weight:600;color:rgba(255,255,255,.75);margin-bottom:18px}
.ov-body h2 span{color:#d4a855;font-weight:600}
.ov-body p{font-size:13px;color:rgba(255,255,255,.55);line-height:1.7;margin-bottom:28px}
.btn-outline{display:inline-block;padding:12px 36px;border:none;color:#0f172a;border-radius:30px;font-size:12px;font-weight:700;letter-spacing:1px;text-transform:uppercase;cursor:pointer;background:linear-gradient(135deg,#d4a855,#f0c96a);box-shadow:0 4px 20px rgba(212,168,85,.4);transition:all .25s;text-decoration:none}
.btn-outline:hover{transform:translateY(-2px);box-shadow:0 8px 28px rgba(212,168,85,.5)}
.ov-footer{position:relative;z-index:1;font-size:10px;color:rgba(255,255,255,.3);letter-spacing:.5px}
.form-area{width:50%;height:100%;display:flex;flex-direction:column;padding:32px 44px;background:linear-gradient(160deg,#f8fafc 0%,#ffffff 60%,#f0f4ff 100%);justify-content:center}
.step-circle{width:24px;height:24px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:10px;font-weight:700;flex-shrink:0}
.step-circle.done{background:#d1fae5;color:#059669;border:none}
.step-circle.active{background:linear-gradient(135deg,#1e4575,#2563eb);color:white;box-shadow:0 2px 8px rgba(30,69,117,.3);border:none}
.step-line{flex:1;height:2px;margin-top:11px;margin-left:4px;margin-right:4px}
.step-line.done{background:#2563eb}
.form-title{font-size:22px;font-weight:800;color:#0f172a;margin-bottom:4px;letter-spacing:-0.5px}
.form-sub{font-size:11px;color:#94a3b8;margin-bottom:24px;line-height:1.5}
.success-icon{width:64px;height:64px;background:linear-gradient(135deg,#d1fae5,#a7f3d0);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 20px}
.success-icon svg{width:32px;height:32px;color:#059669}
.success-msg{text-align:center;margin-bottom:20px}
.success-msg h3{font-size:18px;font-weight:700;color:#0f172a;margin-bottom:8px}
.success-msg p{font-size:13px;color:#64748b;line-height:1.7}
.waiting-badge{display:flex;align-items:center;gap:10px;background:#fffbeb;border:1.5px solid #fcd34d;border-radius:12px;padding:14px 16px;margin-bottom:24px}
.waiting-badge svg{width:20px;height:20px;color:#d97706;flex-shrink:0}
.waiting-badge p{font-size:12px;color:#92400e;line-height:1.6}
.waiting-badge strong{color:#78350f}
hr{border:none;border-top:1.5px solid #e2e8f0;margin:0 0 20px}
.btn-primary{width:100%;padding:13px;background:linear-gradient(135deg,#1e4575 0%,#2563eb 60%,#1e4575 100%);background-size:200% auto;color:white;border:none;border-radius:12px;font-size:13px;font-weight:700;letter-spacing:1.2px;text-transform:uppercase;cursor:pointer;box-shadow:0 6px 20px rgba(30,69,117,.35);transition:all .3s;animation:btnShimmer 3s linear infinite;text-decoration:none;display:block;text-align:center;line-height:1}
.btn-primary:hover{transform:translateY(-2px);box-shadow:0 8px 24px rgba(30,69,117,.4)}
@keyframes shimmer{0%{background-position:200% center}100%{background-position:-200% center}}
@keyframes btnShimmer{0%{background-position:200% center}100%{background-position:-200% center}}
@keyframes pulse{0%,100%{transform:scale(1);opacity:.18}50%{transform:scale(1.08);opacity:.25}}
</style>
</head>
<body>
<div class="card">
  <!-- FORM SIDE -->
  <div class="form-area">
    <!-- Steps -->
    <div style="display:flex;align-items:flex-start;margin-bottom:20px;flex-shrink:0;">
      <div style="display:flex;flex-direction:column;align-items:center;gap:3px;">
        <div class="step-circle done">&#10003;</div>
        <span style="font-size:8px;font-weight:700;color:#059669;white-space:nowrap;">Your Info</span>
      </div>
      <div class="step-line done"></div>
      <div style="display:flex;flex-direction:column;align-items:center;gap:3px;">
        <div class="step-circle done">&#10003;</div>
        <span style="font-size:8px;font-weight:700;color:#059669;white-space:nowrap;">Security</span>
      </div>
      <div class="step-line done"></div>
      <div style="display:flex;flex-direction:column;align-items:center;gap:3px;">
        <div class="step-circle done">&#10003;</div>
        <span style="font-size:8px;font-weight:700;color:#059669;white-space:nowrap;">Verify</span>
      </div>
      <div class="step-line done"></div>
      <div style="display:flex;flex-direction:column;align-items:center;gap:3px;">
        <div class="step-circle active">4</div>
        <span style="font-size:8px;font-weight:700;color:#1e4575;white-space:nowrap;">Approval</span>
      </div>
    </div>

    <div class="form-title">Account Created!</div>
    <div class="form-sub">Step 4 of 4 &mdash; You're almost in</div>

    <!-- Success Icon -->
    <div class="success-icon">
      <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
      </svg>
    </div>

    <!-- Message -->
    <div class="success-msg">
      <h3>Registration Successful</h3>
      <p>Your account has been created. An admin will review and approve your account shortly.</p>
    </div>

    <!-- Waiting badge -->
    <div class="waiting-badge">
      <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
      </svg>
      <p>You will receive an <strong>email notification</strong> once your account has been approved. Please wait for the admin's confirmation before logging in.</p>
    </div>

    <hr>

    <a href="{{ route('login') }}" class="btn-primary" style="padding:13px;display:flex;align-items:center;justify-content:center;">
      OK
    </a>
  </div>

  <!-- OVERLAY SIDE -->
  <div class="overlay">
    <div class="brand">
      <div class="brand-logo"><img src="{{ asset('images/ArkCrest_Logo.png') }}" alt="Arckrest"></div>
      <div class="brand-name">ARCKREST REALTY CORPORATION</div>
    </div>
    <div class="ov-body">
      <div class="ov-eyebrow">You're All Set!</div>
      <h2>Already have an <span>Account?</span></h2>
      <p>ArkCrest Realty: Making your real estate dreams a reality with expert support and exceptional service.</p>
      <a href="{{ route('login') }}" class="btn-outline">Sign In</a>
    </div>
    <div class="ov-footer">&copy; {{ date('Y') }} Arckrest Realty Corporation</div>
  </div>
</div>
</body>
</html>
