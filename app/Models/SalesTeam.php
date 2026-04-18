<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesTeam extends Model
{
    protected $fillable = ['team_name', 'sales_manager', 'leader_name'];

    public function agents()
    {
        return $this->hasMany(SalesAgent::class, 'team_id');
    }

    public function quotas()
    {
        return $this->hasMany(TeamMonthlyQuota::class, 'team_id');
    }

    public function quotaForMonth(string $month): ?TeamMonthlyQuota
    {
        return $this->quotas()->where('month', $month)->first();
    }
}
