<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BatteryManufacturerResource extends JsonResource
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
            'manufacturer' => $this->manufacturer,
            'description_bm' => $this->description_bm,
            'description_en' => $this->description_en,
            'image' => $this->image,
            'image_size' => $this->image_size,
        ];
    }
}
