<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CarBattery extends Model
{
    public function battery()
    {
        return $this->belongsTo(Battery::class);
    }
}
