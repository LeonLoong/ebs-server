<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Battery extends Model
{
    protected $fillable = [
        'manufacturer_id', 'series_id', 'type_id', 'trade_in_id', 'model', 'model_reference', 'price', 'warranty', 'stock', 'image', 'image_size', 'description', 'specifications'
    ];

    public function battery_manufacturers()
    {
        return $this->hasMany(BatteryManufacturer::class, 'id', 'manufacturer_id');
    }

    public function battery_series()
    {
        return $this->hasMany(BatterySeries::class, 'id', 'series_id');
    }
}
