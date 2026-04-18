<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PeriodLock extends Model
{
    protected $fillable = ['month', 'year', 'locked_by', 'reason'];

    public static function isLocked(int $month, int $year): bool
    {
        return static::where('month', $month)->where('year', $year)->exists();
    }

    public static function getLocked(): \Illuminate\Support\Collection
    {
        return static::orderBy('year', 'desc')->orderBy('month', 'desc')->get();
    }
}
