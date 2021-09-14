<?php

namespace App\Http\Controllers\Api;

use Validator;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\BatteryManufacturerResource;
use App\Models\BatteryManufacturer;

class BatteryManufacturerController extends Controller
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
        $batteryManufacturerQuery = BatteryManufacturer::query();
        $limit = Arr::get($searchParams, 'limit', static::ITEM_PER_PAGE);
        $keyword = Arr::get($searchParams, 'keyword', '');
        if (!empty($keyword)) {
            $batteryManufacturerQuery->where('manufacturer', 'LIKE', '%' . $keyword . '%');
        }   
        return BatteryManufacturerResource::collection($batteryManufacturerQuery->paginate($limit));
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
            $manufacturer = $params['manufacturer'];
            $foundManufacturer = BatteryManufacturer::where('manufacturer', $manufacturer)->first();
            if ($foundManufacturer && $foundManufacturer->manufacturer === $manufacturer) {
                return response()->json(['error' => $manufacturer.' already exists'], 403);
            }
            if ($request->hasFile('file')) {
                $file = $params['file'];
                $fileSize = $file->getSize();
                $fileName = $manufacturer . '_' . 'image' . '.' . $file->getClientOriginalExtension();
                $file->move(storage_path('app/public/images/battery_manufacturers'), $fileName);
            } else {
                $fileName = NULL;
                $fileSize = NULL;
            }
            $batteryManufacturer = BatteryManufacturer::create([
                'manufacturer' => $manufacturer,
                'description_bm' => $params['descriptionBM'] ?? '',
                'description_en' => $params['descriptionEN'] ?? '',
                'image' => $fileName,
                'image_size' => $fileSize,
            ]);
            return new BatteryManufacturerResource($batteryManufacturer);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(BatteryManufacturer $batteryManufacturer)
    {
        return new BatteryManufacturerResource($batteryManufacturer);
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
    public function update(Request $request, BatteryManufacturer $batteryManufacturer)
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
            $manufacturer = $params['manufacturer'];
            $foundManufacturer = BatteryManufacturer::where('manufacturer', $manufacturer)->first();
            if ($foundManufacturer && $foundManufacturer->id !== $batteryManufacturer->id) {
                return response()->json(['error' => $manufacturer.' already exists'], 403);
            }
            if ($request->hasFile('file')) {
                $file = $params['file'];
                $fileSize = $file->getSize();
                $fileName = $manufacturer . '_' . 'image' . '.' . $file->getClientOriginalExtension();
                $file->move(storage_path('app/public/images/battery_manufacturers'), $fileName);
            } else {
                $fileName = NULL;
                $fileSize = NULL;
            }
            $batteryManufacturer->manufacturer = $manufacturer;
            $batteryManufacturer->description_bm = $params['descriptionBM'] ?? '';
            $batteryManufacturer->description_en = $params['descriptionEN'] ?? '';
            $batteryManufacturer->image = $fileName;
            $batteryManufacturer->image_size = $fileSize;
            $batteryManufacturer->save();
            return new BatteryManufacturerResource($batteryManufacturer);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(BatteryManufacturer $batteryManufacturer)
    {
        try {
            $batteryManufacturer->delete();
            $fileName = $batteryManufacturer->image;
            File::delete('storage/images/battery_manufacturers/'.$fileName);
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
            'manufacturer' => 'required',
        ];
    }
}

// Accomplished
