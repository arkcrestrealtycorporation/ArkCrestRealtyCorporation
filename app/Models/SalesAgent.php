<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesAgent extends Model
{
    protected $fillable = ['team_id', 'name'];

    public function team()
    {
        return $this->belongsTo(SalesTeam::class, 'team_id');
    }
}
