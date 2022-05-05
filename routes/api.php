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

// City
Route::get('city', 'WeatherController@getCity')->middleware('cors');
Route::get('apiCity', 'WeatherController@getCityFromApi')->middleware('cors');
Route::get('cityFromDB', 'WeatherController@getCityFromDB')->middleware('cors');

// Forecast
Route::get('forecast', 'WeatherController@getForecast')->middleware('cors');

Route::get('apiForecast', 'WeatherController@getForecastFromApi')->middleware('cors');
Route::get('forecastFromDB', 'WeatherController@getForecastFromDB')->middleware('cors');


Route::post('saveCity', 'WeatherController@setCityInDB');
Route::post('saveForecast', 'WeatherController@setForecastInDB');