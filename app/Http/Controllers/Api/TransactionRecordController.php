<?php

namespace App\Http\Controllers\Api;

use Validator;
use Illuminate\Support\Arr;
use App\Models\TransactionRecord;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\TransactionRecordResource;

class TransactionRecordController extends Controller
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
        $clientQuery = TransactionRecord::query();
        $limit = Arr::get($searchParams, 'limit', static::ITEM_PER_PAGE);
        $keyword = Arr::get($searchParams, 'keyword', '');

        if (!empty($keyword)) {
            $clientQuery->where('name', 'LIKE', '%' . $keyword . '%');
        }
        
        return TransactionRecordResource::collection($clientQuery->paginate($limit));
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
            $transactionRecord = TransactionRecord::create([
                'battery_id' => $params['batteryID'],
                'car_id' => $params['carID'],
                'payment_method_id' => $params['paymentMethodID'],
                'phone_no' => $params['phoneNo'],
                'plate_no' => $params['plateNo'],
                'service_point' => $params['servicePoint'],
                'created_at' => $params['createdAt'], 
            ]);
            return new TransactionRecordResource($transactionRecord);
        }
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
     * @param bool $isNew
     * @return array
     */
    private function getValidationRules($isNew = true)
    {
        return [
            'batteryID' => 'required',
            'carID' => 'required', 
            'paymentMethodID' => 'required',
            'phoneNo' => 'required',
            'plateNo' => 'required', 
            'servicePoint' => 'required',
            'createdAt' => 'required',
        ];
    }
}
