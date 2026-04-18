<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PersonnelContact extends Model
{
    protected $fillable = ['name', 'company', 'email', 'phone', 'facebook', 'address'];
}
