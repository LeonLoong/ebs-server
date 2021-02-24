<?php

namespace App\Http\Controllers\Api;

use App\Models\Battery;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\BatteryResource;

class BatteryController extends Controller
{
    const ITEM_PER_PAGE = 15;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $searchParams = $request->all();
        $batteryQuery = Battery::query();
        $limit = Arr::get($searchParams, 'limit', static::ITEM_PER_PAGE);
        $keyword = Arr::get($searchParams, 'keyword', '');

        if (!empty($keyword)) {
            $batteryQuery = Battery::whereHas('battery_manufacturers', function($query) use ($keyword) {
                $query->where('manufacturer', 'LIKE', '%' . $keyword . '%');
            })
            ->orwhereHas('battery_series', function($query) use($keyword) {
                $query->where('series', 'LIKE', '%' . $keyword . '%');
           })
            ->orWhere('model', 'LIKE', '%' . $keyword . '%')
            ->orWhere('model_reference', 'LIKE', '%' . $keyword . '%');
        }
        
        return BatteryResource::collection($batteryQuery->paginate($limit));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $params = $request->all();
        $battery = Battery::create([
            'battery_manufacturer_id' => $params['battery_manufacturer_id'],
            'battery_series_id' => $params['battery_series_id'],
            'battery_type_id' => $params['battery_type_id'],
            'battery_trade_in_id' => $params['battery_trade_in_id'],
            'model' => $params['model'],
            'model_reference' => $params['model_reference'],
            'price' => $params['price'],
            'warranty' => $params['warranty'],
            'stock' => $params['stock'],
            'image' => $params['image'],
            'description' => $params['description'],
            'specifications' => $params['specifications'],
        ]);

        return new BatteryResource($battery);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
