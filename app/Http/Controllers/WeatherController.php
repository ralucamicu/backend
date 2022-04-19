<?php

namespace App\Http\Controllers;

use App\Models\ApiResponse;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use \Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Scalar\String_;


class WeatherController extends BaseController 
{
    public function getCity($location) {
        $api_key = "f77221ff9711060bd0cc9778bc441b3b";
        $city = Http::get("http://api.openweathermap.org/geo/1.0/direct?limit=1&appid=" . $api_key . "&exclude=id&q=". $location);
        return $city;

        //de aici am nevoie de name, lat & lon
    }

    public function getForecast($latitude, $longitude) {
        $api_key = "f77221ff9711060bd0cc9778bc441b3b";
        $forecast = Http::get("http://api.openweathermap.org/data/2.5/onecall?lat=" . $latitude. "&lon=" . $longitude. "&exclude=id,current,hourly,minutely&units=metric&appid=" . $api_key);
        
        return $forecast;

        //de aici doar de daily
    }

                                                                                    //For city
    public function setCityInDB($location) {
        $response = new ApiResponse;
        
        $response->result = $this->getCity($location);
        $response->type = 'city';
        $response->name = $location;
    
        $response->save();
    }

    public function getCityFromDB($location) {
        $dbCity = DB::select("select name from api_responses where name = '". $location . "'");

        // $lat = DB::('selec result.lat from api_responses')
        
        return $dbCity;
    }

    public function checkIfCityExists($location) {
        $data = $this->getCityFromDB($location);

        if(!$data) {
            $data = $this->getCity($location);
            $data->name = $this->setCityInDB($location);
        }
        return $data;
    }
    
                                                                                    //For forecast
    public function setForecastInDB($location,$lat,$lon) {
        $response = new ApiResponse;
        
        $response->result = $this->getForecast($lat,$lon);
        $response->type = 'forecast';
        $response->name = $location;
    
        $response->save();
    }

    public function getForecastFromDB($location) {
        $dbForecast = DB::select("select result from api_responses where type = 'forecast' and name = '" .$location . "'");

        return $dbForecast;
    }
    public function checkIfForecastExists($location) {
        $data = $this->getCityFromDB($location);

        if(!$data) {
            $data = $this->getCity($location);
            $data->name = $this->setCityInDB($location);
        }
        return $data;
    }
}
