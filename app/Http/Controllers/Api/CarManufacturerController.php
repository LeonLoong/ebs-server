<?php

namespace App\Http\Controllers\Api;

use Validator;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\CarManufacturerResource;
use App\Models\CarManufacturer;

class CarManufacturerController extends Controller
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
        $carManufacturerQuery = CarManufacturer::query();
        $limit = Arr::get($searchParams, 'limit', static::ITEM_PER_PAGE);
        $keyword = Arr::get($searchParams, 'keyword', '');
        if (!empty($keyword)) {
            $carManufacturerQuery->where('manufacturer', 'LIKE', '%' . $keyword . '%');
        }
        return CarManufacturerResource::collection($carManufacturerQuery->paginate($limit));
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
            $foundManufacturer = CarManufacturer::where('manufacturer', $manufacturer)->first();
            if ($foundManufacturer && $foundManufacturer->manufacturer === $manufacturer) {
                return response()->json(['error' => $manufacturer.' already exists'], 403);
            }
            if ($request->hasFile('file')) {
                $file = $params['file'];
                $fileSize = $file->getSize();
                $fileName = $manufacturer . '_' . 'image' . '.' . $file->getClientOriginalExtension();
                $file->move(storage_path('app/public/images/car_manufacturers'), $fileName);
            } else {
                $fileName = NULL;
                $fileSize = NULL;
            }
            $carManufacturer = CarManufacturer::create([
                'manufacturer' => $manufacturer,
                'image' => $fileName,
                'image_size' => $fileSize,
            ]);
            return new CarManufacturerResource($carManufacturer);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(CarManufacturer $carManufacturer)
    {
        return new CarManufacturerResource($carManufacturer);
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
    public function update(Request $request, CarManufacturer $carManufacturer)
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
            $foundManufacturer = CarManufacturer::where('manufacturer', $manufacturer)->first();
            if ($foundManufacturer && $foundManufacturer->id !== $carManufacturer->id) {
                return response()->json(['error' => $manufacturer.' already exists'], 403);
            }
            if ($request->hasFile('file')) {
                $file = $params['file'];
                $fileSize = $file->getSize();
                $fileName = $manufacturer . '_' . 'image' . '.' . $file->getClientOriginalExtension();
                $file->move(storage_path('app/public/images/car_manufacturers'), $fileName);
            } else {
                $fileName = NULL;
                $fileSize = NULL;
            }
            $carManufacturer->manufacturer = $manufacturer;
            $carManufacturer->image = $fileName;
            $carManufacturer->image_size = $fileSize;
            $carManufacturer->save();
            return new CarManufacturerResource($carManufacturer);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(CarManufacturer $carManufacturer)
    {
        try {
            $carManufacturer->delete();
            $fileName = $carManufacturer->image;
            File::delete('storage/images/car_manufacturers/'.$fileName);
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
