<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    protected $fillable = [
        'phone_number',
        'user_id',
        'material_id'
    ];
}
