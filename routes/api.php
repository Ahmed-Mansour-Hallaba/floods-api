<?php

use App\Http\Controllers\CiviliansController;
use App\Http\Controllers\FloodsController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\RequestsController;
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


Route::controller(RegisterController::class)->group(function () {
    Route::post('register', 'register');
    Route::post('login', 'login');
});


Route::controller(CiviliansController::class)->group(function () {
    Route::post('civilian/register', 'register');
});

//Authenticated routes
Route::middleware('auth:sanctum')->group(function () {
    //civilians routes
    Route::post('civilian/update',[CiviliansController::class,'update']);
    //Flood routes
    Route::get('flood/all',[FloodsController::class,'getAllActive']);
    Route::post('flood/create',[FloodsController::class,'create']);
    Route::post('flood/update',[FloodsController::class,'update']);
    //Requests routes
    Route::get('request/pending',[RequestsController::class,'gettAllPending']);
    Route::get('request/all',[RequestsController::class,'gettAll']);
    Route::post('request/create',[RequestsController::class,'create']);
    Route::post('request/update',[RequestsController::class,'update']);
});
