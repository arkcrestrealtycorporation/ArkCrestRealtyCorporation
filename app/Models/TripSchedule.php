<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TripSchedule extends Model
{
    protected $table = 'tripping_schedules';

    protected $fillable = [
        'agent_name', 'team_name',
        'client_name', 'client_email', 'client_phone', 'client_phone_code', 'client_address',
        'property_name', 'company_name',
        'tripping_date', 'tripping_time', 'tripping_type',
        'status',
    ];

    protected $casts = ['tripping_date' => 'date'];
}
