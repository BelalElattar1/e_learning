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

    public function subscribes() {
        $this->belongsTo(Subscribe::class);
    }

    public function material() {
        return $this->belongsTo(Material::class);
    }
}
