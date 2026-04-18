<!DOCTYPE html>
<html>
<head><meta charset="utf-8"><style>
body{font-family:Arial,sans-serif;background:#f1f5f9;margin:0;padding:0}
.wrap{max-width:480px;margin:40px auto;background:white;border-radius:16px;overflow:hidden;box-shadow:0 4px 24px rgba(0,0,0,.1)}
.header{background:linear-gradient(135deg,#1e4575,#2563eb);padding:28px 32px;text-align:center}
.header h1{color:white;font-size:20px;margin:0}
.body{padding:32px}
.code-box{background:#f8fafc;border:2px dashed #1e4575;border-radius:12px;padding:24px;text-align:center;margin:20px 0}
.code{font-size:42px;font-weight:800;color:#1e4575;letter-spacing:12px}
.note{font-size:13px;color:#94a3b8;margin-top:8px}
.footer{padding:16px 32px;background:#f8fafc;border-top:1px solid #e2e8f0;font-size:11px;color:#94a3b8;text-align:center}
</style></head>
<body>
<div class="wrap">
    <div class="header"><h1>Email Verification — Arckrest Realty</h1></div>
    <div class="body">
        <p style="font-size:15px;color:#334155;">Hi <strong>{{ $name }}</strong>,</p>
        <p style="font-size:14px;color:#475569;">Use the code below to verify your email address. This code expires in <strong>10 minutes</strong>.</p>
        <div class="code-box">
            <div class="code">{{ $code }}</div>
            <div class="note">Do not share this code with anyone.</div>
        </div>
        <p style="font-size:13px;color:#94a3b8;">If you did not request this, please ignore this email.</p>
    </div>
    <div class="footer">Arckrest Realty Corporation &mdash; Internal System</div>
</div>
</body>
</html>
