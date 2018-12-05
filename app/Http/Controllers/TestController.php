<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TestModel\CityModel;
use App\TestModel\DistrictModel;
use App\TestModel\CollectDataModel;
use App\TestModel\CollectData1Model;
use Log;
use QL\QueryList;


class TestController extends Controller
{

    public function test()
    { 
       try {
          throw new \Exception("Value must be 1 or below");
       } catch(\Exception $e) {
          print("hello");
          Log::info();
       }
       
    }
  
}
