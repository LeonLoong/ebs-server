<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionRecord extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'battery_id', 'car_id', 'payment_method_id', 'phone_no', 'plate_no', 'service_point', 'created_at'
    ];
}
