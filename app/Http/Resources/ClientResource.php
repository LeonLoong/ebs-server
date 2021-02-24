<?php

namespace App\Http\Resources;

use App\Models\Car;
use App\Models\Battery;
use App\Models\PaymentMethod;
use App\Models\CarManufacturer;
use App\Models\BatteryManufacturer;
use Illuminate\Http\Resources\Json\JsonResource;

class ClientResource extends JsonResource
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
            'car_manufacturer' => CarManufacturer::findOrFail(Car::findOrFail($this->car_id)->car_manufacturer_id)->manufacturer,
            'car_model' => Car::findOrFail($this->car_id)->model,
            'battery_manufacturer' => BatteryManufacturer::findOrFail(Battery::findOrFail($this->battery_id)->battery_manufacturer_id)->manufacturer,
            'battery_model' => Battery::findOrFail($this->battery_id)->model,
            'payment_method' => PaymentMethod::findOrFail($this->payment_method_id)->method,
            'handphone_number' => $this->handphone_number,
            'total_visits' => $this->total_visits,
            'total_transactions' => $this->total_transactions,
            'service_difficulty' => $this->service_difficulty,
            'service_points' => $this->service_points,
            'discount' => $this->discount,
            'description' => $this->description,
            'first_visit_at' => $this->first_visit_at,
            'last_visit_at' => $this->last_visit_at,
        ];
    }
}
