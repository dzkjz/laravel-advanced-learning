<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function parkArea()
    {
        return $this->hasOne(ParkArea::class);
    }
}
