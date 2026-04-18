<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeamMonthlyQuota extends Model
{
    protected $fillable = ['team_id', 'date_from', 'date_to', 'quota_amount'];

    protected $casts = ['date_from' => 'date', 'date_to' => 'date'];

    public function team()
    {
        return $this->belongsTo(SalesTeam::class, 'team_id');
    }
}
