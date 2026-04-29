<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\CommissionRequest;
use App\Models\CommissionRequestSales;
use App\Models\TripSchedule;
use App\Services\AdminEmailNotifier;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class SendEventReminders extends Command
{
    protected $signature   = 'events:send-reminders {--trigger=day_before : day_before or same_day}';
    protected $description = 'Send email reminders for upcoming events';

    public function handle(): void
    {
        $trigger = $this->option('trigger');
        $isToday = $trigger === 'same_day';

        $date        = $isToday ? Carbon::today()->toDateString()    : Carbon::tomorrow()->toDateString();
        $displayDate = $isToday ? Carbon::today()->format('F j, Y')  : Carbon::tomorrow()->format('F j, Y');
        $when        = $isToday ? 'Today'                            : 'Tomorrow';
        $prefix      = $isToday ? 'TODAY'                            : 'on';

        // ── Recipients: admin + users with 'admin sales' position ──────
        $adminSalesEmails = $this->getAdminAndSalesAdminEmails();

        // ── 1. Commission Releases → admin + admin sales ────────────────
        $commReleases = collect();
        CommissionRequestSales::whereDate('date_released', $date)
            ->where('status', 'Not Yet Released')->get()
            ->each(fn($c) => $commReleases->push($c));
        CommissionRequest::whereDate('date_released', $date)
            ->where('status', 'Not Yet Released')->get()
            ->each(fn($c) => $commReleases->push($c));

        if ($commReleases->isNotEmpty()) {
            $rows = $commReleases->map(fn($c) =>
                "<b>💰 Commission Release {$when}</b><br>" .
                ($c->client_name ?? '—') . " — " . ($c->project_name ?? '—') .
                " | Agent: " . ($c->agent_name ?? '—') .
                " | ₱" . number_format($c->commission ?? 0, 2) . "<br><br>"
            )->implode('');

            $this->sendToRecipients(
                $adminSalesEmails,
                "ArkCrest: Commission Release {$prefix} {$displayDate}",
                "Commission Release {$when} — {$displayDate}",
                $rows
            );
            $this->info("Commission release reminder sent ({$commReleases->count()} record/s).");
        }

        // ── 2. Downpayments → admin + admin sales ───────────────────────
        $downpayments = CommissionRequestSales::whereDate('date_of_downpayment', $date)
            ->where('client_status', '!=', 'Done')->get();

        if ($downpayments->isNotEmpty()) {
            $rows = $downpayments->map(fn($c) =>
                "<b>📋 Downpayment Due {$when}</b><br>" .
                "{$c->client_name} — {$c->project_name} | Agent: {$c->agent_name}<br><br>"
            )->implode('');

            $this->sendToRecipients(
                $adminSalesEmails,
                "ArkCrest: Downpayment Due {$prefix} {$displayDate}",
                "Downpayment Due {$when} — {$displayDate}",
                $rows
            );
            $this->info("Downpayment reminder sent ({$downpayments->count()} record/s).");
        }

        // ── 3. Site Visits → admin + admin sales + the agent ────────────
        $trips = TripSchedule::whereDate('tripping_date', $date)
            ->whereIn('status', ['confirmed', 'pending'])->get();

        if ($trips->isNotEmpty()) {
            // Group by agent so each agent gets their own trips
            $tripsByAgent = $trips->groupBy('agent_name');

            // Build full list for admin/sales admin
            $adminRows = $trips->map(function($t) use ($when) {
                $time = $t->tripping_time ? Carbon::parse($t->tripping_time)->format('g:i A') : 'Time TBD';
                return "<b>🏠 Site Visit {$when}</b><br>" .
                    "{$t->client_name} — {$t->property_name} | Agent: {$t->agent_name} | {$time}<br><br>";
            })->implode('');

            $this->sendToRecipients(
                $adminSalesEmails,
                "ArkCrest: Site Visit {$prefix} {$displayDate}",
                "Site Visit {$when} — {$displayDate}",
                $adminRows
            );

            // Send to each agent their own trips
            foreach ($tripsByAgent as $agentName => $agentTrips) {
                $agentUser = User::where('name', $agentName)
                    ->where('status', 'active')
                    ->whereNotNull('email')
                    ->where('email', 'not like', 'pending_%')
                    ->first();

                if (!$agentUser) continue;
                // Skip if already in admin list
                if (in_array($agentUser->email, $adminSalesEmails)) continue;

                $agentRows = $agentTrips->map(function($t) use ($when) {
                    $time = $t->tripping_time ? Carbon::parse($t->tripping_time)->format('g:i A') : 'Time TBD';
                    return "<b>🏠 Your Site Visit {$when}</b><br>" .
                        "{$t->client_name} — {$t->property_name} | {$time}<br><br>";
                })->implode('');

                $this->sendToRecipients(
                    [$agentUser->email],
                    "ArkCrest: Your Site Visit {$prefix} {$displayDate}",
                    "Your Site Visit {$when} — {$displayDate}",
                    $agentRows
                );
            }

            $this->info("Site visit reminder sent ({$trips->count()} trip/s).");
        }

        if ($commReleases->isEmpty() && $downpayments->isEmpty() && $trips->isEmpty()) {
            $this->info("No events for {$when} ({$displayDate}). No email sent.");
        }

        $this->info('Done.');
    }

    private function getAdminAndSalesAdminEmails(): array
    {
        return User::where(function($q) {
                $q->where('role', 'admin')
                  ->orWhereRaw("LOWER(position) LIKE '%admin sales%'")
                  ->orWhereRaw("LOWER(position) LIKE '%sales admin%'");
            })
            ->where('status', 'active')
            ->whereNotNull('email')
            ->where('email', 'not like', 'pending_%')
            ->pluck('email')
            ->unique()
            ->toArray();
    }

    private function sendToRecipients(array $emails, string $subject, string $title, string $body): void
    {
        if (empty($emails)) return;

        // Load SMTP — prefer DB settings, fallback to .env
        $s = \DB::table('app_settings')->pluck('value', 'key');
        $smtpHost     = $s['smtp_host']     ?? config('mail.mailers.smtp.host');
        $smtpPort     = $s['smtp_port']     ?? config('mail.mailers.smtp.port', '587');
        $smtpUser     = $s['smtp_username'] ?? config('mail.from.address');
        $smtpPass     = $s['smtp_password'] ?? config('mail.mailers.smtp.password');
        $smtpFromName = $s['smtp_from_name'] ?? config('app.name');

        if (empty($smtpHost) || empty($smtpUser) || empty($smtpPass)) {
            $this->error('SMTP not configured.');
            return;
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

        foreach ($emails as $email) {
            // Get recipient name from users table
            $user = User::where('email', $email)->first();
            $recipientName = $user ? $user->name : '';

            $html = AdminEmailNotifier::buildPublicHtml($title, $body, $recipientName);

            try {
                Mail::html($html, fn($msg) => $msg->to($email)->subject($subject)->from($smtpUser, $smtpFromName));
                $this->info("  → Sent to {$email}");
            } catch (\Exception $e) {
                $this->error("  ✗ Failed to send to {$email}: " . $e->getMessage());
            }
        }
    }
}
