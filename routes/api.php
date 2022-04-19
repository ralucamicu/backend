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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('city', 'WeatherController@getCity');
Route::get('forecast', 'WeatherController@getForecast');
Route::get('cityFromDB', 'WeatherController@getCityFromDB');
Route::get('check/{cityFromDB}', 'WeatherController@checkIfCityExists');


Route::post('save', 'WeatherController@save');
Route::post('saveCity', 'WeatherController@setCityInDB');
Route::post('saveForecast', 'WeatherController@setForecastInDB');