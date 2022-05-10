<?php

namespace App\Http\Controllers;

use App\Models\ApiResponseModel;
use Carbon\Carbon;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Http;

class WeatherController extends BaseController

{
    protected $timeout_city = 86400;

    protected $timeout_forecast = 7200;
    /**
     */


    function __construct()
    {
    }

    //Api calls
    public function getCityFromApi($location)
    {
        $json = Http::get("http://api.openweathermap.org/geo/1.0/direct?limit=1&appid=" . $api_key = env('FORECAST_API_KEY') . "&exclude=id&q=" . $location);
        $city = json_decode($json, true);

        return $city;
    }

    public function getForecastFromApi($latitude, $longitude)
    {
        $json = Http::get("http://api.openweathermap.org/data/2.5/onecall?lat=" . $latitude . "&lon=" . $longitude . "&exclude=id,current,hourly,minutely&units=metric&appid="
            . $api_key = env('FORECAST_API_KEY'));
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
            if (Carbon::create($response->updated_at, 'UTC')->addSeconds($this->timeout_city)->greaterThan(Carbon::now('UTC'))) {
                return $response->result;
            }
        }
        $this->deleteOldCityFromDB($location);
        return null;
    }

    public function deleteOldCityFromDB($location)
    {
        $response = ApiResponseModel::where('type', '=', 'city')->where('name', '=', $location)->first();
        return $response->delete();
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
        $response = ApiResponseModel::where('type', '=', 'forecast')->where('name', '=', $location)->first();
        if ($response) {
            if (Carbon::create($response->updated_at, 'UTC')->addSeconds($this->timeout_forecast)->greaterThan(Carbon::now('UTC'))) {
                return $response->result;
            }
        }
        $this->deleteOldForecastFromDB($location);
        return null;
    }

    public function deleteOldForecastFromDB($location)
    {
        $response = ApiResponseModel::where('type', '=', 'forecast')->where('name', '=', $location)->first();
        return $response->delete();
    }


    public function getForecast($location)
    {
        $city = $this->getCity($location);
        if (!$city) {
            return ['success' => false, 'error-msg' => 'no city found'];
        }

        $forecast = $this->getForecastFromDB($location);
        if (!$forecast) {
            $lat = $city[0]['lat'];
            $lon = $city[0]['lon'];
            $forecast = $this->getForecastFromApi($lat, $lon);
            if ($forecast) {
                $this->setForecastInDB($location, $forecast);
            }
            else {
                return ['success' => false, 'error-msg' => 'no forecast for this city'];
            }
        }
        return ['success' => true, 'forecast' => $forecast, 'city' => $city];
    }
}