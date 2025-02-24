<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    protected $fillable = [
        'phone_number',
        'approve',
        'user_id',
        'material_id'
    ];
}
