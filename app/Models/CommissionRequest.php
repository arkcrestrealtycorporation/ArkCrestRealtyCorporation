<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CommissionRequest extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'control_number',
        'requestor_name',
        'department',
        'category',
        'date_requested',
        'requested_amount',
        'status',
        'date_released',
        'total_expenses',
        'amount_returned',
        'date_of_amount_returned',
        // Commission fields
        'project_name',
        'property_details',
        'client_name',
        'terms_of_payment',
        'agent_name',
        'number_of_units',
        'net_tcp',
        'commission',
        'mode_of_payment'
    ];

    protected $casts = [
        'date_requested' => 'date',
        'date_released' => 'date',
        'date_of_amount_returned' => 'date',
        'requested_amount' => 'decimal:2',
        'total_expenses' => 'decimal:2',
        'amount_returned' => 'decimal:2'
    ];
}
