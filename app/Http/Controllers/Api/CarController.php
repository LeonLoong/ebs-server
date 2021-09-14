<?php

namespace App\Http\Controllers\Api;

use Validator;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\CarResource;
use App\Http\Resources\CarBatteryResource;
use App\Models\Car;
use App\Models\CarManufacturer;
use App\Models\CarBattery;

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
            ->orWhere('model', 'LIKE', '%' . $keyword . '%');
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
        $validator = Validator::make($request->all(), $this->getValidationRules(false));
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 403);
        } else {
            $model = $params['model'];
            $manufacturerID = $params['manufacturerID'];
            $manufacturer = CarManufacturer::find($manufacturerID)->manufacturer;
            $description = $params['description'];
            $foundModelManufacturer = Car::where('model', $model)->where('manufacturer_id', $manufacturerID)->first();
            if ($foundModelManufacturer && $foundModelManufacturer->model === $model && $foundModelManufacturer->description === $description) {
                $manufacturer = Car::find($foundModelManufacturer->id)->car_manufacturers->first()->manufacturer;
                return response()->json(['error' => $manufacturer .' '.$model . ' '.$description .' already exists'], 403);
            }
            if ($request->hasFile('file')) {
                $file = $params['file'];
                $fileSize = $file->getSize();
                $fileName = $model . '_' . 'image' . '.' . $file->getClientOriginalExtension();
                $file->move(storage_path('app/public/images/cars'), $fileName);
            } else {
                $fileName = NULL;
                $fileSize = NULL;
            }
            $car = Car::create([
                'manufacturer_id' => $manufacturerID,
                'model' => $model,
                'description' => $description ?? NULL,
                'image' => $fileName,
                'image_size' => $fileSize,
            ]);
            return new CarResource($car);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Car $car)
    {
        return new CarResource($car);
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
    public function update(Request $request, Car $car)
    {
        $params = $request->all();
        $currentUser = Auth::user();
        if (!$currentUser->isAdmin()
            && !$currentUser->hasPermission(\App\Laravue\Acl::PERMISSION_UPDATE_EBS)
        ) {
            return response()->json(['error' => 'Permission denied'], 403);
        }
        $validator = Validator::make($request->all(), $this->getValidationRules(false));
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 403);
        } else {
            $model = $params['model'];
            $manufacturerID = $params['manufacturerID'];
            $manufacturer = CarManufacturer::find($manufacturerID)->manufacturer;
            $foundModelManufacturer = Car::where('model', $model)->where('manufacturer_id', $manufacturerID)->first();
            if ($foundModelManufacturer && $foundModelManufacturer->id !== $car->id) {
                $manufacturer = Car::find($foundModelManufacturer->id)->car_manufacturers->first()->manufacturer;
                return response()->json(['error' => $manufacturer .' '.$model . ' already exists'], 403);
            }
            if ($request->hasFile('file')) {
                $file = $params['file'];
                $fileSize = $file->getSize();
                $fileName = $manufacturer .'_'.preg_replace('/[^A-Za-z0-9\-]/', '', $model) . '_' . 'image' . '.' . $file->getClientOriginalExtension();
                $file->move(storage_path('app/public/images/cars'), $fileName);
            } else {
                $fileName = NULL;
                $fileSize = NULL;
            }
            $car->model = $model;
            $car->manufacturer_id = $manufacturerID;
            $car->description = $params['description'] ?? '';
            $car->image = $fileName;
            $car->image_size = $fileSize;
            $car->save();
            return new CarResource($car);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Car $car)
    {
        try {
            $car->delete();
            $fileName = $car->image;
            File::delete('storage/images/cars/'.$fileName);
        } catch (\Exception $ex) {
            response()->json(['error' => $ex->getMessage()], 403);
        }

        return response()->json(null, 204);
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

    /**
     * @param bool $isNew
     * @return array
     */
    private function getValidationRules($isNew = true)
    {
        return [
            'model' => 'required',
            'manufacturerID' => 'required',
        ];
    }
}

// Accomplished
