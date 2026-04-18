<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = ['user_id', 'action', 'module', 'description', 'ip', 'meta'];

    protected $casts = ['meta' => 'array'];

    public function user()
    {
        return $this->belongsTo(User::class)->withDefault(['name' => 'System']);
    }

    public static function log(string $action, string $module, string $description, array $meta = []): void
    {
        static::create([
            'user_id'     => auth()->id(),
            'action'      => $action,
            'module'      => $module,
            'description' => $description,
            'ip'          => request()->ip(),
            'meta'        => !empty($meta) ? $meta : null,
        ]);
    }
}
