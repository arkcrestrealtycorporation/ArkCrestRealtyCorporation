<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommissionRequestSales extends Model
{
    protected $table = 'commission_requests_sales';
    
    protected $fillable = [
        'developer_name',
        'date_requested',
        'reservation_date',
        'date_of_downpayment',
        'project_name',
        'property_details',
        'block_lot_number',
        'client_name',
        'lot_area',
        'price_sqm',
        'tcp',
        'discount',
        'net_tcp',
        'terms_of_payment',
        'agent_name',
        'number_of_units',
        'commission_percent',
        'commission',
        'mode_of_payment',
        'remarks',
        'date_released',
        'status',
        'client_status',
    ];

    protected $casts = [
        'date_requested' => 'date',
        'reservation_date' => 'date',
        'date_of_downpayment' => 'date',
        'date_released' => 'date',
        'lot_area' => 'decimal:4',
        'price_sqm' => 'decimal:2',
        'tcp' => 'decimal:2',
        'discount' => 'decimal:2',
        'net_tcp' => 'decimal:2',
        'commission_percent' => 'decimal:4',
        'commission' => 'decimal:2',
        'number_of_units' => 'integer',
    ];
}
