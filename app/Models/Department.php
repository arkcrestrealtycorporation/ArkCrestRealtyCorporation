<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $fillable = ['name', 'slug', 'allowable_budget', 'budget_from', 'budget_to'];

    protected $casts = [
        'budget_from' => 'date',
        'budget_to'   => 'date',
    ];

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    public function categories()
    {
        return $this->hasMany(ExpenseCategory::class);
    }
}
