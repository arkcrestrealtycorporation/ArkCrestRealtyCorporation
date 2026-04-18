<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $table = 'clients';

    protected $fillable = ['name', 'address', 'emails', 'phones', 'notes'];

    protected $casts = [
        'emails' => 'array',
        'phones' => 'array',
    ];
}
