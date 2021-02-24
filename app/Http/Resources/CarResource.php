<?php

namespace App\Http\Resources;

use App\Models\CarManufacturer;
use Illuminate\Http\Resources\Json\JsonResource;

class CarResource extends JsonResource
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
            'manufacturer' => CarManufacturer::findOrFail($this->car_manufacturer_id)->manufacturer,
            'model' => $this->model,
        ];
    }
}
