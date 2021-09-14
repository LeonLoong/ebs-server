<?php

namespace App\Http\Resources;

use App\Models\BatteryType;
use App\Models\BatterySeries;
use App\Models\BatteryTradeIn;
use App\Models\BatteryManufacturer;
use Illuminate\Http\Resources\Json\JsonResource;

class BatteryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $batteryManufacturer = BatteryManufacturer::where('id', '=', $this->manufacturer_id)->first();
        if ($batteryManufacturer === null) {
            $batteryManufacturer = null;
         } else {
            $batteryManufacturer = $batteryManufacturer->manufacturer;
         };
        $batterySeries = BatterySeries::where('id', '=', $this->series_id)->first();
        if ($batterySeries === null) {
            $batterySeries = null;
         } else {
            $batterySeries = $batterySeries->series;
         };
        return [
            'id' => $this->id,
            'manufacturer' => $batteryManufacturer,
            'manufacturer_id' => $this->manufacturer_id,
            'series' => $batterySeries,
            'series_id' => $this->series_id,
            'type' => BatteryType::findOrFail($this->type_id)->type,
            'type_id' => $this->type_id,
            'trade_in' => BatteryTradeIn::findOrFail($this->trade_in_id)->price,
            'trade_in_id' => $this->trade_in_id,
            'model' => $this->model,
            'model_reference' => $this->model_reference,
            'price' => $this->price,
            'warranty' => $this->warranty,
            'stock' => $this->stock,
            'volt' => $this->volt,
            'ah' => $this->ah,
            'cca' => $this->cca,
            'rc' => $this->rc,
            'length' => $this->length,
            'width' => $this->width,
            'height' => $this->height,
            'image' => $this->image,
            'description' => $this->description,
            'specifications' => $this->specifications,
            'image' => $this->image,
            'image_size' => $this->image_size,
        ];
    }
}
