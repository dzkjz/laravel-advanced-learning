<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParkArea extends Model
{
    public function car()
    {
        return $this->belongsTo(Car::class);
    }
}
