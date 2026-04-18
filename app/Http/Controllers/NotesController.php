<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\SystemNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;

class NotesController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'title'         => 'required|string|max:255',
            'body'          => 'nullable|string',
            'note_date'     => 'nullable|date',
            'reminder_time' => 'nullable|date_format:H:i',
        ]);

        $note = Note::create([
            'user_id'       => auth()->id(),
            'title'         => $request->title,
            'body'          => $request->body,
            'note_date'     => $request->note_date ?: null,
            'reminder_time' => $request->reminder_time ?: null,
            'reminder_sent' => false,
        ]);

        // If note is for today, push notification immediately
        if ($note->note_date && \Carbon\Carbon::parse($note->note_date)->isToday()) {
            SystemNotification::notify(
                auth()->id(),
                'note_reminder',
                'Note Reminder: ' . $note->title,
                ($note->body ? Str::limit($note->body, 80) : 'You have a note scheduled today.') .
                ($note->reminder_time ? ' at ' . \Carbon\Carbon::parse($note->reminder_time)->format('g:i A') : ''),
                $note->id
            );
        }

        return back()->with('note_success', 'Note saved.');
    }

    public function destroy($id)
    {
        $note = Note::where('id', $id)->where('user_id', auth()->id())->firstOrFail();
        $note->delete();
        return back()->with('note_success', 'Note deleted.');
    }

    // Mark note as done (soft-complete, keep for history)
    public function done($id)
    {
        $note = Note::where('id', $id)->where('user_id', auth()->id())->firstOrFail();
        $note->update(['completed_at' => now()]);
        return response()->json([
            'success'      => true,
            'note_id'      => $note->id,
            'title'        => $note->title,
            'body'         => $note->body,
            'note_date'    => $note->note_date ? \Carbon\Carbon::parse($note->note_date)->format('M d, Y') : null,
            'completed_at' => 'just now',
        ]);
    }

    // Done by title (fallback for old notifications without note_id)
    public function doneByTitle(Request $request)
    {
        $title = $request->input('title');
        $title = preg_replace('/^Note Reminder:\s*/i', '', $title);
        $note = Note::where('user_id', auth()->id())->where('title', $title)->whereNull('completed_at')->first();
        if ($note) {
            $note->update(['completed_at' => now()]);
            return response()->json([
                'success'      => true,
                'note_id'      => $note->id,
                'title'        => $note->title,
                'body'         => $note->body,
                'note_date'    => $note->note_date ? \Carbon\Carbon::parse($note->note_date)->format('M d, Y') : null,
                'completed_at' => 'just now',
            ]);
        }
        return response()->json(['success' => false]);
    }

    // Snooze — push a new in-app notification in 30 minutes, don't change the note
    public function snooze($id)
    {
        $note = Note::where('id', $id)->where('user_id', auth()->id())->firstOrFail();
        $snoozeTime = now()->addMinutes(30);

        // Just push a new notification scheduled 30 min from now
        // We store it but mark reminder_sent = false so the scheduler picks it up
        // For immediate in-app: push a new system notification with a future notified_at
        \App\Models\SystemNotification::create([
            'user_id'     => auth()->id(),
            'note_id'     => $note->id,
            'type'        => 'note_reminder',
            'title'       => 'Note Reminder: ' . $note->title,
            'message'     => ($note->body ? \Illuminate\Support\Str::limit($note->body, 80) : 'Snoozed reminder.') . ' at ' . $snoozeTime->format('g:i A'),
            'is_read'     => false,
            'notified_at' => $snoozeTime,
        ]);

        return response()->json(['success' => true, 'snooze_time' => $snoozeTime->format('g:i A')]);
    }
}
