<?php

use App\Http\Controllers\WeatherController;
use Illuminate\Support\Facades\Route;
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
// Route::get('/dbCity/{city}', [WeatherController::class, 'getCityFromDB']);  



// Forecast
Route::get('/forecast/{city}', [WeatherController::class, 'getForecast']);
Route::get('/dbForecast/{city}', [WeatherController::class, 'getForecastFromDB']);