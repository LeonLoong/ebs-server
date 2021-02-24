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
        $batterySeries = BatterySeries::where('id', '=', $this->battery_series_id)->first();
        if ($batterySeries === null) {
            $batterySeries = null;
         } else {
            $batterySeries = $batterySeries->series;
         };
        return [
            'id' => $this->id,
            'manufacturer' => BatteryManufacturer::findOrFail($this->battery_manufacturer_id)->manufacturer,
            'series' => $batterySeries,
            'type_bm' => BatteryType::findOrFail($this->battery_type_id)->type_bm,
            'type_en' => BatteryType::findOrFail($this->battery_type_id)->type_en,
            'type_zh' => BatteryType::findOrFail($this->battery_type_id)->type_zh,
            'trade_in' => BatteryTradeIn::findOrFail($this->battery_trade_in_id)->price,
            'model' => $this->model,
            'model_reference' => $this->model_reference,
            'price' => $this->price,
            'warranty' => $this->warranty,
            'stock' => $this->stock,
            'image' => $this->image,
            'description' => $this->description,
            'specifications' => $this->specifications,
        ];
    }
}
