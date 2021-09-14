<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'manufacturer_id', 'model', 'description', 'image', 'image_size'
    ];

    public function car_manufacturers()
    {
        return $this->hasMany(CarManufacturer::class, 'id', 'manufacturer_id');
    }
}
