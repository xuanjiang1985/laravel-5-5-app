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

    function unescape($str) {
      return json_decode('"'.str_replace('%', '\\', $str).'"');
    }
    for ($i=1; $i <= 1; $i++) {
      $city = CityModel::where('id',$i)->where('pid','>',0)->get();
      if($city->isEmpty()) {
        continue;
      }

      $url = 'http://www.go007.com/'.$city->first()->address_baixing.'/jiaoyou/';
      //$this->info('正在采集编号-----------------------：'.$i);
      $data = QueryList::get($url)->find('.left_box')->find('dl dd')->find('.list a')->attrs('href');
      $arr = $data->all();
      // if (count($arr) == 0) {
      //   continue
      // }
      //$this->info('采集到列表数量：'.count($arr));
      //数组去重
      $arr = array_unique($arr);
      $this->collect($arr);
       //$this->info('采集完毕编号-----------------------：'.$i);
    }
    return 1;
  }

  public function localService()
  {
      // $city = 'gz';
      // $host = $city.'.ganji.com/jiazheng/';
      // $list = QueryList::get($host)->rules([
      //                                       'titel' => ['.leftBox .list ul li .txt .t a', 'text'],
      //                                       'link' => ['.leftBox .list ul li .txt .t a', 'href'],
      //                                       'shouji' => ['.leftBox .list ul li .list-r-area p a', 'data-phone']
      //                                     ])->query()->getData();
      $str = "/fuwu_dian/755965713x/";
      $arr = explode("/", $str);
      $count = count($arr);
      if($count < 3) {
        return 0;
      }
      //dd($arr);
      $link = $arr[$count-3].'/'.$arr[$count-2].'/';
      dd($link);
  }

  public function collect($arr) {
          // 采集每个用户的信息
        foreach ($arr as $key => $value) {
          //$this->info('暂停5秒...');
          sleep(6);
          //$this->info('开始采集 '.$value);
          $friends =  QueryList::get($value)->rules([
                                                  'jyxuqiu' => ['.details_top .detail_view dd .view_box', 'html'],
                                                  'height' => ['.details_top .viewad_box ul:eq(0) li:eq(2) span', 'text'],
                                                  'title' => ['.details_top .viewad_header h1', 'text'],
                                                  'age' => ['.details_top .viewad_box ul:eq(0) li:eq(1) span', 'text'],
                                                  'zhaopian' => ['.details_top .pic_box img', 'src'],
                                                  'lianxiren' => ['.details_top .viewad_box ul:eq(1) li:eq(0) span', 'text'],
                                                  'dianhua' => ['.details_top .viewad_box ul:eq(1) li:eq(1) span', 'text'],
                                                  'sex' => ['.details_top .viewad_box ul:eq(0) li:eq(0) span', 'text']
                                                  ])->query()->getData();
          $friendsArr = $friends->all();
          if (count($friendsArr) == 0) {
            //$this->info('没有采集到数据，进入下一个url');
             continue;
           } 
          $friendsArr = $friendsArr[0];
          //解析联系人 电话 性别
          $lianxirenArr = explode('"', $friendsArr['lianxiren']);
          if(count($lianxirenArr) < 2) {
            $friendsArr['lianxiren'] = '';
          } else {
            $friendsArr['lianxiren'] = unescape($lianxirenArr[1]);
          }

          $dianhuaArr = explode('"', $friendsArr['dianhua']);
          if(count($dianhuaArr) < 2) {
            $friendsArr['dianhua'] = '';
          } else {
            $friendsArr['dianhua'] = unescape($dianhuaArr[1]);
          }

          $sexArr = explode(' ', $friendsArr['sex']);
          if(count($sexArr) < 2) {
            $friendsArr['sex'] = '';
          } else {
            $sex = $sexArr[1];
            $friendsArr['sex'] = mb_substr($sex, 0, 1);
          }
          // 添加重量
          $friendsArr['weight'] = '';
          if(!array_key_exists('height', $friendsArr)) {
            $friendsArr['height'] = '';
          }
          // 处理编码
          foreach ($friendsArr as $key => $value) {
            $friendsArr[$key] = urlencode($value);
          }
          CollectData1Model::create(['item' => 28, 'province' => $city->first()->pid, 'city' => $city->first()->name,
                                      'content' => urldecode(json_encode($friendsArr))]);

        }
    }

    public function friendstore()
    {
      for ($i=54785; $i < 54786 ; $i++) { 
          $row = CollectData1Model::where('id', $i)->get();
          if ($row->isEmpty()) {
            continue;
          }
          $provinceId = $row->first()->province; //pid
          $province = CityModel::where('id', $provinceId)->get();
          if ($province->isEmpty()) {
            continue;
          }
          //处理json中的age
          $contentJson = $row->first()->content;
          $contentArr = json_decode($contentJson);
          dd($contentArr);
          if (isset($contentArr['age']) && strlen($contentArr['age']) > 3) {
            $contentArr['age'] = '';
          }
          //dd($contentArr['age']);
          // 处理编码
          if(count($contentArr) == 0){
            return 2;
          }
          foreach ($contentArr as $key => $value) {
            $contentArr[$key] = urlencode($value);
          }
          CollectDataModel::create(['province' => $province->first()->name,
                                    'item' => 28,
                                    'city' => $row->first()->city,
                                    'content' => urldecode(json_encode($contentArr))
                                    ]);
          //$this->info("已处理完队列：".$i);
        }
        return 1;
        //$this->info("done-----------------------------------------------");
    }

    public function ganji()
    {
       $data = CityModel::all();
       foreach ($data as $key => $value) {
         $value->update(['address_ganji' => $value->address_baixing]);
       }

       return 1;
    }
  
}
