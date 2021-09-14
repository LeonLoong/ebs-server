<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BatteryManufacturer extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'manufacturer', 'image', 'description_bm', 'description_en'
    ];

    // public function battery()
    // {
    //     return $this->belongsTo(Battery::class);
    // }
}
