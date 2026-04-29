<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Mail;

class AdminEmailNotifier
{
    public static function send(string $subject, string $title, string $body): void
    {
        // Load SMTP from DB settings
        $s = \DB::table('app_settings')->pluck('value', 'key');
        $smtpHost     = $s['smtp_host']     ?? config('mail.mailers.smtp.host');
        $smtpPort     = $s['smtp_port']     ?? config('mail.mailers.smtp.port', '587');
        $smtpUser     = $s['smtp_username'] ?? config('mail.from.address');
        $smtpPass     = $s['smtp_password'] ?? config('mail.mailers.smtp.password');
        $smtpFromName = $s['smtp_from_name'] ?? config('app.name');

        if (empty($smtpHost) || empty($smtpUser) || empty($smtpPass)) {
            return; // SMTP not configured, skip silently
        }

        config([
            'mail.mailers.smtp.host'       => $smtpHost,
            'mail.mailers.smtp.port'       => $smtpPort,
            'mail.mailers.smtp.username'   => $smtpUser,
            'mail.mailers.smtp.password'   => $smtpPass,
            'mail.mailers.smtp.encryption' => 'tls',
            'mail.from.address'            => $smtpUser,
            'mail.from.name'               => $smtpFromName,
            'mail.default'                 => 'smtp',
        ]);

        $admins = User::where('role', 'admin')
            ->whereNotNull('email')
            ->where('email', 'not like', 'pending_%')
            ->pluck('email')
            ->toArray();

        if (empty($admins)) return;

        $html = self::buildPublicHtml($title, $body);

        foreach ($admins as $email) {
            try {
                Mail::html($html, function($msg) use ($email, $subject, $smtpUser, $smtpFromName) {
                    $msg->to($email)->subject($subject)->from($smtpUser, $smtpFromName);
                });
            } catch (\Exception $e) {
                // Fail silently — don't break the app if email fails
            }
        }
    }

    private static function buildHtml(string $title, string $body): string
    {
        return self::buildPublicHtml($title, $body);
    }

    public static function buildPublicHtml(string $title, string $body, string $recipientName = ''): string
    {
        $hour = (int) now()->format('H');
        $greeting = $hour < 12 ? 'Good Morning' : ($hour < 18 ? 'Good Afternoon' : 'Good Evening');
        $name = $recipientName ? ", {$recipientName}" : '';
        $time = now()->format('F j, Y g:i A');

        return "<!DOCTYPE html>
<html>
<head><meta charset='UTF-8'><meta name='viewport' content='width=device-width,initial-scale=1'></head>
<body style='margin:0;padding:0;background:#f0f4f8;font-family:\"Segoe UI\",Arial,sans-serif;'>
<table width='100%' cellpadding='0' cellspacing='0' style='background:#f0f4f8;padding:32px 16px;'>
  <tr><td align='center'>
    <table width='600' cellpadding='0' cellspacing='0' style='max-width:600px;width:100%;background:white;border-radius:16px;overflow:hidden;box-shadow:0 8px 32px rgba(0,0,0,.10);'>

      {{-- Header --}}
      <tr>
        <td style='background:linear-gradient(135deg,#0f2444 0%,#1e4575 50%,#2563eb 100%);padding:36px 36px 28px;text-align:center;'>
          <div style='display:inline-block;background:rgba(255,255,255,.12);border-radius:50%;width:56px;height:56px;line-height:56px;font-size:26px;margin-bottom:14px;'>🏢</div>
          <h1 style='color:white;margin:0;font-size:22px;font-weight:700;letter-spacing:.3px;'>ArkCrest Realty Corporation</h1>
          <p style='color:rgba(255,255,255,.7);margin:6px 0 0;font-size:13px;letter-spacing:.2px;'>Internal System Notification</p>
        </td>
      </tr>

      {{-- Greeting --}}
      <tr>
        <td style='padding:32px 36px 0;'>
          <p style='margin:0 0 4px;font-size:22px;font-weight:700;color:#0f2444;'>Happy ArkCrest {$greeting}{$name}! 👋</p>
          <p style='margin:10px 0 0;font-size:14px;color:#64748b;line-height:1.6;'>
            Here's a quick heads-up from your ArkCrest system. We've got some important updates lined up for you — please take a moment to review the details below.
          </p>
        </td>
      </tr>

      {{-- Divider --}}
      <tr><td style='padding:20px 36px 0;'><div style='height:1px;background:linear-gradient(90deg,#e2e8f0,#cbd5e1,#e2e8f0);'></div></td></tr>

      {{-- Title --}}
      <tr>
        <td style='padding:20px 36px 8px;'>
          <p style='margin:0;font-size:11px;font-weight:700;color:#2563eb;text-transform:uppercase;letter-spacing:1.2px;'>📋 Reminder</p>
          <h2 style='margin:6px 0 0;font-size:17px;font-weight:700;color:#0f2444;'>{$title}</h2>
        </td>
      </tr>

      {{-- Body --}}
      <tr>
        <td style='padding:8px 36px 28px;'>
          <div style='background:#f8fafc;border-left:4px solid #2563eb;border-radius:0 10px 10px 0;padding:18px 20px;font-size:13px;color:#374151;line-height:1.8;'>
            {$body}
          </div>
        </td>
      </tr>

      {{-- CTA --}}
      <tr>
        <td style='padding:0 36px 28px;text-align:center;'>
          <a href='https://arkcrestrealtycorporation.com' style='display:inline-block;background:linear-gradient(135deg,#1e4575,#2563eb);color:white;text-decoration:none;padding:12px 32px;border-radius:8px;font-size:13px;font-weight:700;letter-spacing:.3px;'>Open ArkCrest System →</a>
        </td>
      </tr>

      {{-- Footer --}}
      <tr>
        <td style='background:#f8fafc;border-top:1px solid #e2e8f0;padding:18px 36px;text-align:center;'>
          <p style='margin:0;font-size:11px;color:#94a3b8;line-height:1.6;'>
            This is an automated reminder from the ArkCrest Realty System.<br>
            Sent on {$time} (Philippine Time) &bull; Do not reply to this email.
          </p>
        </td>
      </tr>

    </table>
  </td></tr>
</table>
</body>
</html>";
    }
}
