<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemNotification extends Model
{
    protected $table = 'system_notifications';

    protected $fillable = ['user_id', 'note_id', 'type', 'title', 'message', 'is_read', 'notified_at'];

    protected $casts = [
        'is_read'     => 'boolean',
        'notified_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function note()
    {
        return $this->belongsTo(Note::class);
    }

    public static function notify(int $userId, string $type, string $title, string $message, ?int $noteId = null): void
    {
        static::create([
            'user_id'     => $userId,
            'note_id'     => $noteId,
            'type'        => $type,
            'title'       => $title,
            'message'     => $message,
            'is_read'     => false,
            'notified_at' => now(),
        ]);
    }
}
