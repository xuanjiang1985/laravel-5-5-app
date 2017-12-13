<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TestModel\CityModel;
use App\TestModel\DistrictModel;
use App\TestModel\CollectDataModel;
use Log;
use QL\QueryList;


class TestController extends Controller
{

  public function cityedit()
  {
    $province = CityModel::where('pid', 0)->get();

    return view('index',['province' => $province]);
  }
  public function cityget($id)
  {
    
    $city = CityModel::where('pid', $id)->get()->toArray();
    return response()->json($city);
  }

  public function districtget($id)
  {
    
    $district = DistrictModel::where('city_id', $id)->get()->toArray();
    foreach ($district as $key => $value) {
      if($value['address_baixing'] == null) {
        $district[$key]['address_baixing'] = '';
      }
    }
    return response()->json($district);
  }

  public function districtupdate(Request $request)
  {
    
    DistrictModel::where('id', $request->input('id'))->update(['address_baixing' => $request->input('value')]);
    return response()->json(['code' => 0]);
  }

	public function test2()
	{
    set_time_limit(0);
    $citys = CityModel::where('pid', '>', 0)->whereNotNull('address_baixing')->get();
    foreach ($citys as $key => $value) {
      Log::info("开始爬取城市:".$value->name);
      $this->storeDistrict($value);
    }

    Log::info("采集完毕----------------------------------------------------------");

	}


  public function object2array($object) {
      if (is_object($object)) {
        foreach ($object as $key => $value) {
          $array[$key] = $value;
            }
          }
          else {
            $array = $object;
          }
          return $array;
    }

  public function storeDistrict($city)
  {

    $host = "http://127.0.0.1:1234/module/collect/district?city=".$city->address58;
    //http://127.0.0.1:1234/module/collect/district?city=sz
    $json = file_get_contents($host);
    $obj = json_decode($json);
    if ($obj->code == 0) {
      $arr = $obj->data;
      array_shift($arr);
      foreach($arr as $key =>  $value) {
          $valueArr = explode("|", $value);
          $valueName = $valueArr[1];
          if (count($valueName) < 2 ) {
            continue;
          }
          $hrefArr = explode("/", $valueArr[0]);
          $valueHref = $hrefArr[1];
          Log::info("正在匹配区名:", $valueName);
          $dbDistrict = DistrictModel::where('city_id', $city->id)->where('name', 'like', $valueName.'%')->get();
          if ($dbDistrict->isEmpty()) {
            continue;
          }
          $dbDistrict->first()->address58 = $valueHref;
          $dbDistrict->save()->address58 = $valueHref;
      }
    }
  }

  public function collectFriend($city)
  {
    //http://shenzhen.baixing.com/huodong/m5236/?afo=kuA
    $url = 'http://'.$city.'.baixing.com';
    //$html = file_get_contents('http://sz.58.com/zufang/32361871000239x.shtml');
    //dd($html);
    $rule = [
      'link' => ['.list-ad-items li a:first-child' => 'href'],
      'title' => ['.list-ad-items li a:first-child' => 'text']
    ];
    $data = QueryList::get('http://sz.58.com/zufang/32361871000239x.shtml')->find('title')->text();
    //find('.list-ad-items')->find('li>a')->attrs('href');
    dd($data);
  }

  public function provinceToName()
  {
    set_time_limit(0);
    for ($i=1; $i < 2 ; $i++) { 
      $row = CollectDataModel::where('id', $i)->get();
      if ($row->isEmpty()) {
        continue;
      }
      $provinceId = $row->first()->province;
      $province = CityModel::where('id', $provinceId)->get();
      if ($province->isEmpty()) {
        continue;
      }
      $row->first()->update(['province' => $province->first()->name]);
      //$this->info("已处理完队列：".$i);
    }
    return 1;
  }
  
}
