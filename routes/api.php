<?php

use \App\Laravue\Acl;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::namespace('Api')->group(function() {
    Route::post('auth/login', 'AuthController@login');
    Route::group(['middleware' => 'auth:sanctum'], function () {
        // Auth routes
        Route::get('auth/user', 'AuthController@user');
        Route::post('auth/logout', 'AuthController@logout');

        Route::get('/user', function (Request $request) {
            return new UserResource($request->user());
        });

        // Api resource routes
        Route::apiResource('roles', 'RoleController')->middleware('permission:' . Acl::PERMISSION_PERMISSION_MANAGE);
        Route::apiResource('users', 'UserController')->middleware('permission:' . Acl::PERMISSION_USER_MANAGE);
        Route::apiResource('permissions', 'PermissionController')->middleware('permission:' . Acl::PERMISSION_PERMISSION_MANAGE);

        // EBS
        Route::apiResource('ebs/batteries', 'BatteryController');
        Route::apiResource('ebs/battery-manufacturers', 'BatteryManufacturerController');
        Route::apiResource('ebs/battery-series', 'BatterySeriesController');
        Route::apiResource('ebs/battery-types', 'BatteryTypeController');
        Route::apiResource('ebs/battery-trade-ins', 'BatteryTradeInController');
        Route::apiResource('ebs/cars', 'CarController');
        Route::apiResource('ebs/car-manufacturers', 'CarManufacturerController');
        Route::apiResource('ebs/transaction-records', 'TransactionRecordController');
        Route::apiResource('ebs/payment-methods', 'PaymentMethodController');

        // Custom routes
        Route::get('ebs/cars/{car}/batteries', 'CarController@getCarBatteries');
        // // Route::get('pms/projects/{project}/departments', 'PmsProjectDepartmentController')->name('projects.departments.index');
        // Route::get('pms/projects/{project}/progresses', 'PmsDepartmentTaskController@getProgress');
        // Route::get('statuses/pms', 'StatusController@pms');
        // // Route::get('pms/projects/{project}/episodes/{episode}/departments/{department}/tasks', 'PmsTaskController@getDepartmentTaskProgress');
        // Route::put('pms/projects/{project}/departments/{department}', 'PmsProjectDepartmentController@updateProjectDepartmentUser');
    });
});
