<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'car_manufacturer_id', 'model'
    ];

    public function car_manufacturers()
    {
        return $this->hasMany(CarManufacturer::class, 'id', 'car_manufacturer_id');
    }
}
