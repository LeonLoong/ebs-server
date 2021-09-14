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
        $carManufacturer = CarManufacturer::where('id', '=', $this->manufacturer_id)->first();
        if ($carManufacturer === null) {
            $carManufacturer = null;
         } else {
            $carManufacturer = $carManufacturer->manufacturer;
         };
         
        return [
            'id' => $this->id,
            'manufacturer' => $carManufacturer,
            'manufacturer_id' => $this->manufacturer_id,
            'model' => $this->model,
            'description' => $this->description,
            'image' => $this->image,
            'image_size' => $this->image_size,
        ];
    }
}
