<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $fillable = ['department_id', 'expense_date', 'categories_data', 'total_amount'];

    protected $casts = [
        'categories_data' => 'array',
        'expense_date' => 'date'
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
