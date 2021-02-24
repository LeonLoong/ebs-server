<?php

namespace App\Http\Resources;

use App\Models\BatteryType;
use App\Models\BatterySeries;
use App\Models\BatteryTradeIn;
use App\Models\BatteryManufacturer;
use Illuminate\Http\Resources\Json\JsonResource;

class CarBatteryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $batterySeries = BatterySeries::where('id', '=', $this->battery->battery_series_id)->first();
        if ($batterySeries === null) {
            $batterySeries = null;
         } else {
            $batterySeries = $batterySeries->series;
         };
        return [
            'id' => $this->id,
            'manufacturer' => BatteryManufacturer::findOrFail($this->battery->battery_manufacturer_id)->manufacturer,
            'series' => $batterySeries,
            'type_bm' => BatteryType::findOrFail($this->battery->battery_type_id)->type_bm,
            'type_en' => BatteryType::findOrFail($this->battery->battery_type_id)->type_en,
            'type_zh' => BatteryType::findOrFail($this->battery->battery_type_id)->type_zh,
            'trade_in' => BatteryTradeIn::findOrFail($this->battery->battery_trade_in_id)->price,
            'model' => $this->battery->model,
            'model_reference' => $this->battery->model_reference,
            'price' => $this->battery->price,
            'warranty' => $this->battery->warranty,
            'stock' => $this->battery->stock,
            'image' => $this->battery->image,
            'description' => $this->battery->description,
            'specifications' => $this->battery->specifications,
        ];
    }
}
