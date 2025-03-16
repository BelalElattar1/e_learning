<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    protected $fillable = [
        'phone_number',
        'user_id',
        'material_id',
        'is_subscriber'
    ];

    public function subscribes() {
        return $this->hasMany(Subscribe::class);
    }

    public function material() {
        return $this->belongsTo(Material::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}
