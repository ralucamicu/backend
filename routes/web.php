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

Route::get('/dbForecast/{city}', [WeatherController::class, 'getForecastFromDB']);
Route::get('/dbCity/{city}', [WeatherController::class, 'getCityFromDB']);
// Route::put('/update/{city}', [WeatherController::class, 'updateForecastInDB']);

// Forecast
Route::get('/forecast/{city}/{lat}/{lon}', [WeatherController::class, 'getForecast']);

