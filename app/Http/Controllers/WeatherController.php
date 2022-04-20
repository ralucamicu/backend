<?php

namespace App\Http\Controllers;

use App\Models\ApiResponse;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

class WeatherController extends BaseController 
{
    public function getCityFromApi($location) {
        $api_key = "f77221ff9711060bd0cc9778bc441b3b";
        $json = Http::get("http://api.openweathermap.org/geo/1.0/direct?limit=1&appid=" . $api_key . "&exclude=id&q=". $location);
        $city = json_decode($json,true);

        return $city;

        //de aici am nevoie de name, lat & lon
    }

    public function getForecastFromApi($latitude, $longitude) {
        $api_key = "f77221ff9711060bd0cc9778bc441b3b";
        $json = Http::get("http://api.openweathermap.org/data/2.5/onecall?lat=" . $latitude. "&lon=" . $longitude. "&exclude=id,current,hourly,minutely&units=metric&appid=" . $api_key);
        $forecast = json_decode($json,true);
        
        return $forecast;

        //de aici doar de daily
    }

    
    //For city
    public function setCityInDB($location , $data=[]) {
    
        $response = new ApiResponse;
        
        $response->result = $data;
        $response->type = 'city';
        $response->name = $location;
    
        $response->save();
    }

    public function getCityFromDB($location) {
        $response = ApiResponse::where('type', '=', 'city')->where('name', '=', $location)->first();

        if($response) {
            return $response->result;
        }

        return null;
    }

    public function getCity($location) {
        $data = $this->getCityFromDB($location);

        if(!$data) {
            $data = $this->getCityFromApi($location);
            if($data) {
                $this->setCityInDB($location, $data);
            }
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
        $response = ApiResponse::where('result')->where('type', '=', 'forecast')->where('name', '=', $location)->first();

        if($response) {
            return $response->result;
        }

        return null;
    }
    public function getForecast($location) {
        $data = $this->getForecastFromDB($location);

        if(!$data) {

            $city = $this->getCity($location);
            if($city) {
                $data = $this->getForecastFromApi($location);
                if($data) {
                    $this->setForecastInDB($location, $data);
                } else {
                    return ['success'=>false, 'error-msg'=>'no forecast for this city'];
                }  
            }else {
                return ['success'=>false, 'error-msg'=>'no city found'];
            }  

        }

        return $data;
    }
}