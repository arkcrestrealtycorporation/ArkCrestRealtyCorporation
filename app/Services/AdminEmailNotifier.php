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
        $smtpHost     = $s['smtp_host']     ?? null;
        $smtpPort     = $s['smtp_port']     ?? '587';
        $smtpUser     = $s['smtp_username'] ?? null;
        $smtpPass     = $s['smtp_password'] ?? null;
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

        $html = self::buildHtml($title, $body);

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
        return "<!DOCTYPE html><html><body style='font-family:Segoe UI,sans-serif;background:#f0f2f5;padding:24px;'>
<div style='max-width:560px;margin:0 auto;background:white;border-radius:12px;overflow:hidden;box-shadow:0 4px 16px rgba(0,0,0,.1);'>
<div style='background:linear-gradient(135deg,#1e4575,#2563eb);padding:20px 24px;'>
<h2 style='color:white;margin:0;font-size:16px;'>ArkCrest Realty Corporation</h2>
<p style='color:rgba(255,255,255,.7);margin:3px 0 0;font-size:12px;'>System Notification</p>
</div>
<div style='padding:24px;'>
<h3 style='font-size:15px;font-weight:700;color:#0f172a;margin:0 0 12px;'>{$title}</h3>
<div style='font-size:13px;color:#374151;line-height:1.7;'>{$body}</div>
</div>
<div style='background:#f8fafc;padding:14px 24px;border-top:1px solid #f1f5f9;'>
<p style='font-size:11px;color:#94a3b8;margin:0;'>Automated notification from ArkCrest Realty System — " . now()->format('F j, Y g:i A') . "</p>
</div>
</div></body></html>";
    }
}
