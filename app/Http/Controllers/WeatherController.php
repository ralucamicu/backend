<?php

namespace App\Http\Controllers;

use App\Models\ApiResponseModel;
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
    public function setCityInDB($location) {
        $data = $this->getCityFromApi($location);
        $response = new ApiResponseModel;
        
        $response->result =  $this->getCityFromApi($location);
        $response->type = 'city';
        $response->name = $location;
    
        $response->save();

        return ['success'=>true, 200];
    }

    public function getCityFromDB($location) {
        $response = ApiResponseModel::where('type', '=', 'city')->where('name', '=', $location)->first();

        if($response) {
            return $response->result;
        }

        return $response;
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
        $data=[];
        $response = new ApiResponseModel;
        
        $response->result = $data;
        $response->type = 'forecast';
        $response->name = $location;

        if($response->result != $this->getForecastFromApi($lat,$lon)) {
            $response = ApiResponseModel::where('type', '=', 'forecast')->update('result', '=', $this->getForecastFromApi($lat,$lon));
        }
    
        $response->save();
    }

    public function getForecastFromDB($location) {
        $response = ApiResponseModel::where('result')->where('type', '=', 'forecast')->where('name', '=', $location)->first();

        if($response) {
            return $response->result;
        }

        return null;
    }
    public function getForecast($location,$lat,$lon) {
        $data = $this->getForecastFromDB($location);

        if(!$data) {

            $city = $this->getCity($location);
            if($city) {
                $data = $this->getForecastFromApi($lat,$lon);
                if($data) {
                    $this->setForecastInDB($location,$lat,$lon);
                } else {
                    return ['success'=>false, 'error-msg'=>'no forecast for this city'];
                }  
            }else {
                return ['success'=>false, 'error-msg'=>'no city found'];
            }  

        }

        return $data;
    }
	/**
	 */
	function __construct() {
	}
}