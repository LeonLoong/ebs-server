<?php

namespace App\Http\Controllers\Api;

use Validator;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\BatteryManufacturer;
use App\Models\Battery;

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
        $validator = Validator::make($request->all(), $this->getValidationRules(false));
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 403);
        } else {
            $model = $params['model'];
            $manufacturerID = $params['manufacturerID'];
            $manufacturer = BatteryManufacturer::find($manufacturerID)->manufacturer;
            $foundModelManufacturer = Battery::where('model', $model)->where('manufacturer_id', $manufacturerID)->first();
            if ($foundModelManufacturer&& $foundModelManufacturer->model === $model) {
                $manufacturer = Battery::find($foundModelManufacturer->id)->battery_manufacturers->first()->manufacturer;
                return response()->json(['error' => $manufacturer .' '.$model . ' already exists'], 403);
            }
            if ($request->hasFile('file')) {
                $file = $params['file'];
                $fileSize = $file->getSize();
                $fileName = $manufacturer .'_'.preg_replace('/[^A-Za-z0-9\-]/', '', $model) . '_' . 'image' . '.' . $file->getClientOriginalExtension();
                $file->move(storage_path('app/public/images/batteries'), $fileName);
            } else {
                $fileName = NULL;
                $fileSize = NULL;
            }
            $battery = Battery::create([
                'model' => $model,
                'model_reference' => $params['modelReference'] ?? NULL,
                'manufacturer_id' => $manufacturerID,
                'series_id' => $params['seriesID'] ?? NULL,
                'type_id' => $params['typeID'],
                'price' => $params['price'],
                'warranty' => $params['warranty'],
                'trade_in_id' => $params['tradeInID'],
                'stock' => $params['stock'],
                'description' => $params['description'] ?? '',
                'specifications' => $params['specifications'] ?? '{}',
                'image' => $fileName,
                'image_size' => $fileSize,
            ]);
            return new BatteryResource($battery);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Battery $battery)
    {
        return new BatteryResource($battery);
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
    public function update(Request $request, Battery $battery)
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
            $manufacturer = BatteryManufacturer::find($manufacturerID)->manufacturer;
            $foundModelManufacturer = Battery::where('model', $model)->where('manufacturer_id', $manufacturerID)->first();
            if ($foundModelManufacturer && $foundModelManufacturer->id !== $battery->id) {
                $manufacturer = Battery::find($foundModelManufacturer->id)->battery_manufacturers->first()->manufacturer;
                return response()->json(['error' => $manufacturer .' '.$model . ' already exists'], 403);
            }
            if ($request->hasFile('file')) {
                $file = $params['file'];
                $fileSize = $file->getSize();
                $fileName = $manufacturer .'_'.preg_replace('/[^A-Za-z0-9\-]/', '', $model) . '_' . 'image' . '.' . $file->getClientOriginalExtension();
                $file->move(storage_path('app/public/images/batteries'), $fileName);
            } else {
                $fileName = NULL;
                $fileSize = NULL;
            }
            $battery->model = $model;
            $battery->model_reference = $params['modelReference'] ?? NULL;
            $battery->manufacturer_id = $manufacturerID;
            $battery->series_id = $params['seriesID'] ?? NULL;
            $battery->type_id = $params['typeID'];
            $battery->price = $params['price'];
            $battery->warranty = $params['warranty'];
            $battery->trade_in_id = $params['tradeInID'];
            $battery->stock = $params['stock'];
            $battery->description = $params['description'] ?? '';
            $battery->specifications = $params['specifications'] ?? '{}';
            $battery->image = $fileName;
            $battery->image_size = $fileSize;
            $battery->save();
            return new BatteryResource($battery);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Battery $battery)
    {
        try {
            $battery->delete();
            $fileName = $battery->image;
            File::delete('storage/images/batteries/'.$fileName);
        } catch (\Exception $ex) {
            response()->json(['error' => $ex->getMessage()], 403);
        }
        return response()->json(null, 204);
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
            'typeID' => 'required', 
            'price' => 'required', 
            'warranty' => 'required', 
            'tradeInID' => 'required', 
            'stock' => 'required', 
        ];
    }
}
