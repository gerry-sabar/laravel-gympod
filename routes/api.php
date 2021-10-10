<?php

use Illuminate\Http\Request;
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

Route::post('login','App\Http\Controllers\GympodController@login');
Route::get('refresh','App\Http\Controllers\GympodController@refreshToken');

Route::Group(['middleware' => [
    'auth:api',
    ]], function () {
        Route::get('pods','App\Http\Controllers\GympodController@getPods');
        Route::get('detail/{uuid}','App\Http\Controllers\GympodController@getPodDetail');
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
