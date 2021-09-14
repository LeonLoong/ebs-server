<?php

namespace App\Http\Resources;

use App\Models\Car;
use App\Models\User;
use App\Models\Battery;
use App\Models\PaymentMethod;
use App\Models\CarManufacturer;
use App\Models\BatteryManufacturer;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionRecordResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user' => User::where('phone_no', $this->phone_no)->value('name'),
            'car_manufacturer' => CarManufacturer::findOrFail(Car::findOrFail($this->car_id)->manufacturer_id)->manufacturer,
            'car_model' => Car::findOrFail($this->car_id)->model,
            'car_description' => Car::findOrFail($this->car_id)->description,
            'battery_manufacturer' => BatteryManufacturer::findOrFail(Battery::findOrFail($this->battery_id)->manufacturer_id)->manufacturer,
            'battery_model' => Battery::findOrFail($this->battery_id)->model,
            'payment_method' => PaymentMethod::findOrFail($this->payment_method_id)->method,
            'phone_no' => $this->phone_no,
            'service_point' => $this->service_point,
            'created_at' => $this->created_at,
        ];
    }
}
