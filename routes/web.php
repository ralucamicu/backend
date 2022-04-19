<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WeatherController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/city/{city}', [WeatherController::class, 'getCity']);
Route::get('/forecast/{latitude}/{longitude}', [WeatherController::class, 'getForecast']);
Route::get('cityFromDB/{cityFromDB}', [WeatherController::class, 'getCityFromDB']);
Route::get('check/{cityFromDB}', [WeatherController::class, 'checkIfCityExists']);
Route::get('forecastFromDB/{city}', [WeatherController::class, 'getForecastFromDB']);



Route::post('save', [WeatherController::class, 'save']);
Route::post('saveCity/{saveCity}', [WeatherController::class, 'setCityInDB']);
Route::post('saveForecast/{city}/{latitude}/{longitude}', [WeatherController::class, 'setForecastInDB']);