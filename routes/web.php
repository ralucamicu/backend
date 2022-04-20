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
// City
Route::get('/city/{city}', [WeatherController::class, 'getCity']);

Route::get('cityFromDB/{cityFromDB}', [WeatherController::class, 'getCityFromDB']);
Route::get('checkCity/{cityFromDB}', [WeatherController::class, 'checkIfCityExists']);

// Forecast
Route::get('/forecast/{city}', [WeatherController::class, 'getForecast']);
Route::get('forecastFromDB/{city}', [WeatherController::class, 'getForecastFromDB']);
Route::get('checkForecast/{city}/{latitude}/{longitude}', [WeatherController::class, 'checkIfForecastExists']);



Route::post('saveCity/{saveCity}', [WeatherController::class, 'setCityInDB']);
Route::post('saveForecast/{city}/{latitude}/{longitude}', [WeatherController::class, 'setForecastInDB']);