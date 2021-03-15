<?php

namespace App\Http\Controllers\Api;

use Validator;
use App\Models\Car;
use App\Models\Battery;
use App\Models\CarBattery;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Http\Resources\CarResource;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\BatteryResource;
use App\Http\Resources\CarBatteryResource;

class CarController extends Controller
{
    const ITEM_PER_PAGE = 50;
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
    public function update(Request $request, Car $car)
    {
        $currentUser = Auth::user();
        if (!$currentUser->isAdmin()
            && $currentUser->id !== $car->id
            && !$currentUser->hasPermission(\App\Laravue\Acl::PERMISSION_UPDATE_PROJECT)
        ) {
            return response()->json(['error' => 'Permission denied'], 403);
        }

        $validator = Validator::make($request->all(), $this->getValidationRules(false));
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 403);
        } else {
            $model = $request->get('model');
            $found = Car::where('model', $model)->first();
            if ($found && $found->id !== $car->id) {
                return response()->json(['error' => $model.' already exists'], 403);
            }
            $car->model = $request->get('model');
            $car->car_manufacturer_id = $request->get('manufacturer');
            $car->save();

            // $departments = $request->get('departments');
            // $projectDepartmentToDelete = PmsProjectHasDepartment::where('project_id', $project->id)->get()->pluck('project_id', 'department_id');
            // foreach ( $departments as $department ) {
            //     $updateOrCreateProjectDepartment = PmsProjectHasDepartment::updateOrCreate(['project_id' => $project->id, 'department_id' => $department]);
                
            //     if (!empty($projectDepartmentToDelete[$updateOrCreateProjectDepartment->department_id])) {
            //         unset($projectDepartmentToDelete[$updateOrCreateProjectDepartment->department_id]);
            //     }
                
            // };
            // if (count($projectDepartmentToDelete)) {
            //     foreach($projectDepartmentToDelete as $departmentToDelete => $projectToDelete) {
            //         PmsProjectHasDepartment::whereIn('project_id', [$projectToDelete])->whereIn('department_id', [$departmentToDelete])->delete(); 
            //     }
            // }

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
    private function getValidationRules()
    {
        return [
            'model' => 'required',
            'manufacturer' => 'required',
        ];
    }
}
