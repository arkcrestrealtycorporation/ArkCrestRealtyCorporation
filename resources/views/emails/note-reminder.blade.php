<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<style>
body { font-family: Arial, sans-serif; background: #f1f5f9; margin: 0; padding: 0; }
.wrap { max-width: 520px; margin: 40px auto; background: white; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 24px rgba(0,0,0,.1); }
.header { background: linear-gradient(135deg,#1e4575,#2563eb); padding: 32px 36px; }
.header h1 { color: white; font-size: 22px; margin: 0 0 6px; }
.header p { color: rgba(255,255,255,.75); font-size: 14px; margin: 0; }
.body { padding: 32px 36px; }
.note-card { background: #f8fafc; border-left: 4px solid #1e4575; border-radius: 8px; padding: 18px 20px; margin-bottom: 20px; }
.note-title { font-size: 18px; font-weight: 700; color: #0f172a; margin: 0 0 8px; }
.note-body { font-size: 14px; color: #475569; line-height: 1.6; margin: 0 0 12px; }
.note-meta { font-size: 12px; color: #94a3b8; }
.footer { padding: 20px 36px; background: #f8fafc; border-top: 1px solid #e2e8f0; font-size: 12px; color: #94a3b8; text-align: center; }
</style>
</head>
<body>
<div class="wrap">
    <div class="header">
        <h1>Reminder</h1>
        <p>Arkcrest Realty Corporation — Notes Reminder</p>
    </div>
    <div class="body">
        <p style="font-size:15px;color:#334155;margin:0 0 20px;">Hi <strong>{{ $note->user->name }}</strong>, you have a reminder:</p>
        <div class="note-card">
            <div class="note-title">{{ $note->title }}</div>
            @if($note->body)
            <div class="note-body">{{ $note->body }}</div>
            @endif
            <div class="note-meta">
                Reminder set for: <strong>{{ $note->reminder_at->format('F j, Y \a\t g:i A') }}</strong>
            </div>
        </div>
        <p style="font-size:13px;color:#94a3b8;margin:0;">This is an automated reminder from your Arkcrest system.</p>
    </div>
    <div class="footer">Arkcrest Realty Corporation &mdash; Internal System</div>
</div>
</body>
</html>
