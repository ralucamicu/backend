<?php

namespace App\Http\Controllers;

use App\Models\ApiResponseModel;
use Carbon\Carbon;
use DateTimeZone;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Http;

class WeatherController extends BaseController

{    protected $timeout_city = 1800;

    protected $timeout_forecast = 3600;
    /**
     */

    // protected $api_key = env('FORECAST_API_KEY');
    function __construct()
    {
    }


    //Api calls
    public function getCityFromApi($location)
    {
        $api_key = "f77221ff9711060bd0cc9778bc441b3b";
        $json = Http::get("http://api.openweathermap.org/geo/1.0/direct?limit=1&appid=" . $api_key . "&exclude=id&q=" . $location);
        $city = json_decode($json, true);

        return $city;
    }

    public function getForecastFromApi($latitude, $longitude)
    {
        $api_key = "f77221ff9711060bd0cc9778bc441b3b";
        $json = Http::get("http://api.openweathermap.org/data/2.5/onecall?lat=" . $latitude . "&lon=" . $longitude . "&exclude=id,current,hourly,minutely&units=metric&appid=" . $api_key);
        $forecast = json_decode($json, true);

        return $forecast;
    }





    //For city
    public function setCityInDB($location, $data = [])
    {
        $response = new ApiResponseModel;

        $response->result = $data;
        $response->type = 'city';
        $response->name = $location;

        $response->save();

        return ['success' => true, 200];
    }

    public function getCityFromDB($location)
    {
        $response = ApiResponseModel::where('type', '=', 'city')->where('name', '=', $location)->first();

        if ($response) {
            // if (Carbon::now($response->updated_at) + Carbon::add($this->timeout_city,'seconds') >= Carbon::now()) {
            // }
            return $response->result;
        }


        return null;
    }

    public function getCity($location)
    {
        $data = $this->getCityFromDB($location);

        if (!$data) {
            $data = $this->getCityFromApi($location);
            if ($data) {
                $this->setCityInDB($location, $data);
            }
        }
        return $data;
    }



    //For forecast
    public function setForecastInDB($location, $data = [])
    {
        $response = new ApiResponseModel;

        $response->result = $data;
        $response->type = 'forecast';
        $response->name = $location;

        $response->save();

        return ['success' => true, 200];
    }

    public function getForecastFromDB($location)
    {
        $response = new ApiResponseModel;
        $response = ApiResponseModel::where('type', '=', 'forecast')->where('name', '=', $location)->first();

        if ($response) {
            // if ($response->updated_at + Carbon::addSeconds($this->timeout_forecast) >= Carbon::now(new DateTimeZone('Europe/Bucharest'))) {
            // }
            return $response->result;
        }

        return null;
    }
    public function getForecast($location)
    {
        $city = $this->getCity($location);
        if (!$city) {
            return ['success' => false, 'error-msg' => 'no city found'];
        }

        $forecast = $this->getForecastFromDB($location);
        if (!$forecast) {
            // $lat = $city["lat"];
            // $lon = $city["lon"];
            $lat = $city[0]['lat'];
            $lon = $city[0]['lon'];
            $forecast = $this->getForecastFromApi($lat, $lon);
            if ($forecast) {
                $this->setForecastInDB($location,$forecast);
            }
            else {
                return ['success' => false, 'error-msg' => 'no forecast for this city'];
            }
        }
        return ['success' => true, 'forecast' => $forecast, 'city' => $city];
    }
}