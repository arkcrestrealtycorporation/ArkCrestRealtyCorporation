<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SummaryReport extends Model
{
    protected $fillable = [
        'month',
        'year',
        'units',
        'gross_sales',
        'coh'
    ];

    protected $casts = [
        'units' => 'decimal:2',
        'gross_sales' => 'decimal:2',
        'coh' => 'decimal:2'
    ];
}
