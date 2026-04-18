<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\CommissionRequestSales;
use App\Models\TripSchedule;
use App\Models\Note;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class SendEventReminders extends Command
{
    protected $signature   = 'events:send-reminders';
    protected $description = 'Send daily email reminders (day before events) to admins and note owners';

    public function handle(): void
    {
        $tomorrow = Carbon::tomorrow()->toDateString();
        $displayDate = Carbon::tomorrow()->format('F j, Y');

        // Get all active admin emails
        $adminEmails = User::where('role', 'admin')
            ->where('status', 'active')
            ->whereNotNull('email')
            ->where('email', 'not like', 'pending_%')
            ->pluck('email', 'id')
            ->toArray();

        if (empty($adminEmails)) {
            $this->error('No active admin emails found.');
            return;
        }

        $adminEvents = [];

        // 1. Commission releases tomorrow
        $commReleases = CommissionRequestSales::whereDate('date_released', $tomorrow)
            ->where('status', 'Not Yet Released')->get();
        foreach ($commReleases as $c) {
            $adminEvents[] = [
                'type'   => '💰 Commission Release Tomorrow',
                'detail' => "{$c->client_name} — {$c->project_name} | Agent: {$c->agent_name} | ₱" . number_format($c->commission ?? 0, 2),
            ];
        }

        // 2. Downpayment due tomorrow
        $downpayments = CommissionRequestSales::whereDate('date_of_downpayment', $tomorrow)->get();
        foreach ($downpayments as $c) {
            $adminEvents[] = [
                'type'   => '📋 Downpayment Due Tomorrow',
                'detail' => "{$c->client_name} — {$c->project_name} | Agent: {$c->agent_name}",
            ];
        }

        // 3. Scheduled site visits tomorrow
        $trips = TripSchedule::whereDate('tripping_date', $tomorrow)
            ->whereIn('status', ['confirmed', 'pending'])->get();
        foreach ($trips as $t) {
            $time = $t->tripping_time ? Carbon::parse($t->tripping_time)->format('g:i A') : 'Time TBD';
            $adminEvents[] = [
                'type'   => '🏠 Site Visit Tomorrow',
                'detail' => "{$t->client_name} — {$t->property_name} | Agent: {$t->agent_name} | {$time}",
            ];
        }

        // Send admin events to all admins
        if (!empty($adminEvents)) {
            $subject = "ArkCrest Reminder: Events on {$displayDate}";
            $html = $this->buildEmailHtml($adminEvents, $displayDate, 'Tomorrow\'s Important Events');
            foreach ($adminEmails as $email) {
                try {
                    Mail::html($html, fn($m) => $m->to($email)->subject($subject));
                    $this->info("Admin reminder sent to: {$email}");
                } catch (\Exception $e) {
                    $this->error("Failed to send to {$email}: " . $e->getMessage());
                }
            }
        } else {
            $this->info('No admin events for tomorrow.');
        }

        // 4. Personal notes due tomorrow — send to note owner's email
        $notes = Note::whereDate('note_date', $tomorrow)->whereNull('completed_at')->with('user')->get();
        foreach ($notes as $note) {
            $owner = $note->user;
            if (!$owner || empty($owner->email) || str_contains($owner->email, 'pending_')) continue;

            $noteEvents = [[
                'type'   => '📝 Your Note Reminder Tomorrow',
                'detail' => $note->title . ($note->body ? " — {$note->body}" : '') .
                            ($note->reminder_time ? ' at ' . Carbon::parse($note->reminder_time)->format('g:i A') : ''),
            ]];

            $subject = "ArkCrest Note Reminder: {$note->title}";
            $html = $this->buildEmailHtml($noteEvents, $displayDate, "Hi {$owner->name}, you have a note reminder tomorrow");
            try {
                Mail::html($html, fn($m) => $m->to($owner->email)->subject($subject));
                $this->info("Note reminder sent to: {$owner->email}");
            } catch (\Exception $e) {
                $this->error("Failed to send note to {$owner->email}: " . $e->getMessage());
            }
        }

        $this->info('Done.');
    }

    private function buildEmailHtml(array $events, string $date, string $intro): string
    {
        $rows = '';
        foreach ($events as $e) {
            $rows .= "<tr><td style='padding:12px 20px;border-bottom:1px solid #f1f5f9;'>
                <div style='font-size:13px;font-weight:700;color:#1e4575;margin-bottom:3px;'>{$e['type']}</div>
                <div style='font-size:12px;color:#374151;'>{$e['detail']}</div>
            </td></tr>";
        }

        return "<!DOCTYPE html><html><body style='font-family:Segoe UI,sans-serif;background:#f0f2f5;padding:24px;margin:0;'>
<div style='max-width:600px;margin:0 auto;background:white;border-radius:12px;overflow:hidden;box-shadow:0 4px 16px rgba(0,0,0,.1);'>
    <div style='background:linear-gradient(135deg,#1e4575,#2563eb);padding:24px 28px;'>
        <h2 style='color:white;margin:0;font-size:18px;'>ArkCrest Realty Corporation</h2>
        <p style='color:rgba(255,255,255,.75);margin:6px 0 0;font-size:13px;'>Event Reminder for {$date}</p>
    </div>
    <div style='padding:20px 0;'>
        <p style='padding:0 20px;font-size:13px;color:#64748b;margin-bottom:12px;'>{$intro}:</p>
        <table style='width:100%;border-collapse:collapse;'>{$rows}</table>
    </div>
    <div style='background:#f8fafc;padding:14px 20px;border-top:1px solid #f1f5f9;'>
        <p style='font-size:11px;color:#94a3b8;margin:0;'>Automated reminder from ArkCrest Realty System. Do not reply.</p>
    </div>
</div></body></html>";
    }
}
