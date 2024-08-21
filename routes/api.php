<?php

use App\Http\Controllers\Api\V1\TaskController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(["prefix"=> "v1"], function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    })->middleware('auth:sanctum');

    Route::patch('/task/{id}/change-completed', [TaskController::class,'change_completed']);
    Route::apiResource('/task', TaskController::class);
});