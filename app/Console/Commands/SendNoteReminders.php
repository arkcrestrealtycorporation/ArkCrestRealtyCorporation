<?php

namespace App\Console\Commands;

use App\Mail\NoteReminder;
use App\Models\Note;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendNoteReminders extends Command
{
    protected $signature   = 'notes:send-reminders';
    protected $description = 'Send email and in-app reminders for due notes';

    public function handle(): void
    {
        // Find notes where date+time is within the past minute and not yet sent
        $due = Note::with('user')
            ->whereNotNull('note_date')
            ->whereNotNull('reminder_time')
            ->where('reminder_sent', false)
            ->get()
            ->filter(fn($note) => $note->isDueNow());

        foreach ($due as $note) {
            try {
                // Send email
                Mail::to($note->user->email)->send(new NoteReminder($note));
                // Push persistent in-app notification
                \App\Models\SystemNotification::notify(
                    $note->user_id,
                    'note_reminder',
                    'Note Reminder: ' . $note->title,
                    ($note->body ? \Illuminate\Support\Str::limit($note->body, 80) : 'You have a scheduled note.') .
                    ($note->reminder_time ? ' — ' . \Carbon\Carbon::parse($note->reminder_time)->format('g:i A') : '')
                );
                $note->update(['reminder_sent' => true]);
                $this->info("Sent reminder for note #{$note->id}: {$note->title}");
            } catch (\Exception $e) {
                $this->error("Failed for note #{$note->id}: " . $e->getMessage());
            }
        }

        $this->info("Done. Processed {$due->count()} reminder(s).");
    }
}
