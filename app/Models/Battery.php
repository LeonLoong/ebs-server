<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Battery extends Model
{
    protected $fillable = [
        'battery_manufacturer_id', 'battery_series_id', 'battery_type_id', 'battery_trade_in_id', 'model', 'model_reference', 'price', 'warranty', 'stock', 'image', 'description', 'specifications'
    ];

    public function battery_manufacturers()
    {
        return $this->hasMany(BatteryManufacturer::class, 'id', 'battery_manufacturer_id');
    }

    public function battery_series()
    {
        return $this->hasMany(BatterySeries::class, 'id', 'battery_series_id');
    }
}
