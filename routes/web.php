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

Route::get('/apiCity/{city}', [WeatherController::class, 'getCityFromApi']);
Route::get('/cityFromDB/{cityFromDB}', [WeatherController::class, 'getCityFromDB']);

// Forecast
Route::get('/forecast/{city}/{lat}/{lon}', [WeatherController::class, 'getForecast']);

Route::get('/apiForecast/{lat}/{lon}', [WeatherController::class, 'getForecastFromApi']);
Route::get('/forecastFromDB/{city}', [WeatherController::class, 'getForecastFromDB']);



Route::post('saveCity/{city}', [WeatherController::class, 'setCityInDB']);
Route::post('saveForecast/{city}', [WeatherController::class, 'setForecastInDB']);