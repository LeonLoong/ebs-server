<?php

namespace App\Http\Controllers\Api;

use App\Models\Car;
use App\Models\Battery;
use App\Models\CarBattery;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Http\Resources\CarResource;
use App\Http\Controllers\Controller;
use App\Http\Resources\BatteryResource;
use App\Http\Resources\CarBatteryResource;

class CarController extends Controller
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
        $carQuery = Car::query();
        $limit = Arr::get($searchParams, 'limit', static::ITEM_PER_PAGE);
        $keyword = Arr::get($searchParams, 'keyword', '');

        if (!empty($keyword)) {
            $carQuery = Car::whereHas('car_manufacturers', function($query) use ($keyword) {
                $query->where('manufacturer', 'LIKE', '%' . $keyword . '%');
            })
            ->orWhere('model', 'LIKE', '%' . $keyword . '%')
            ->orWhere('model_reference', 'LIKE', '%' . $keyword . '%');
        }
        
        return CarResource::collection($carQuery->paginate($limit));
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
        $car = Car::create([
            'car_manufacturer_id' => $params['car_manufacturer_id'],
            'model' => $params['model'],
        ]);

        return new CarResource($car);
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

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getCarBatteries(Request $request, $carID)
    {
        $searchParams = $request->all();
        $carBatteryQuery = CarBattery::with('battery')->where(['car_id' => $carID]);
        $limit = Arr::get($searchParams, 'limit', static::ITEM_PER_PAGE);

        if (!empty($keyword)) {
            $carBatteryQuery = CarBattery::whereHas('battery_manufacturers', function($query) use ($keyword) {
                $query->where('manufacturer', 'LIKE', '%' . $keyword . '%');
            });
        }
        
        return CarBatteryResource::collection($carBatteryQuery->paginate($limit));
    }
}
