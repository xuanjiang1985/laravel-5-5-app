<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\TestModel\CollectDataModel;
use App\TestModel\CityModel;
use App\TestModel\CollectData1Model;
use QL\QueryList;

class ProvinceToName extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'set:province';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'province.id to name';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    { 
      // 招聘 写入主数据 fuli 转数组
      // for ($i=1; $i <= 6460; $i++) { 
      //     $row = CollectData1Model::where('id', $i)->get();
      //     if ($row->isEmpty()) {
      //       continue;
      //     }

      //     $provinceId = $row->first()->province;
      //     $province = CityModel::where('id', $provinceId)->get();
      //     if ($province->isEmpty()) {
      //       continue;
      //     }

      //     $content = $row->first()->content;
      //     $contentArr = json_decode($content, true);
      //     $fuli = $contentArr['fuli'];
      //     //$this->info($peizhi);
      //     $contentArr['fuli'] = explode("|", $fuli);
      //     $contentJson = json_encode($contentArr);
      //     CollectDataModel::create(['item' => $row->first()->item,
      //                               'province' => $province->first()->name,
      //                               'city' => $row->first()->city,
      //                               'content' => $contentJson
      //                             ]);
      //     $this->info('created');
            
      //     $this->info("done----->：".$i);
      //   }
       // neizhi1 租房配置 字符串->数组 49967-102775
      // for ($i=49967; $i <= 102775; $i++) { 
      //     $row = CollectDataModel::where('id', $i)->where('item','neizhi1')->get();
      //     if ($row->isEmpty()) {
      //       continue;
      //     }
      //     $content = $row->first()->content;
      //     $contentArr = json_decode($content, true);
      //     $contentArr['fllor'] = $contentArr['floor'];
      //     $contentArr['allfllor'] = $contentArr['allfloor'];
      //     $peizhi = $contentArr['peizhi'];
      //     //$this->info($peizhi);
      //     $contentArr['peizhi'] = explode("|", $peizhi);
      //     $contentJson = json_encode($contentArr);
      //     $row->first()->update(['content' => $contentJson]);
      //     $this->info('updated');
            
      //     $this->info("done----->：".$i);
      //   }

       // 宠物 微信->转手机
      // for ($i=1; $i <= 49749; $i++) { 
      //     $row = CollectDataModel::where('id', $i)->get();
      //     if ($row->isEmpty()) {
      //       continue;
      //     }
      //     $content = $row->first()->content;
      //     $contentArr = json_decode($content, true);
         
      //     foreach ($contentArr as $key => $value) {
      //       if ($key == 'tupian' || $key == 'weixin') {
      //         continue;
      //       }
      //       $contentArr[$key] = urlencode($value);
      //     }

      //     $weixin = $contentArr['weixin'];
      //     if(preg_match('/\d+/',$weixin,$arr)){
      //        $shouji = $arr[0];
      //        if (strlen($shouji) > 10) {
      //           $contentArr['shouji'] = $arr[0];
      //           $contentJson = json_encode($contentArr);
      //           $row->first()->update(['content' => urldecode($contentJson)]);
      //           $this->info('updated');
      //        }
      //     }
      //     $this->info("done----->：".$i);
      //   }
        // item 转 neizhi 
        // for ($i=1; $i < 10000 ; $i++) { 
        //   $row = CollectDataModel2::where('id', $i)->get();
        //   if ($row->isEmpty()) {
        //     continue;
        //   }
        //   $item = '';
        //   if ($row->first()->item == 5) {
        //     $item = 'neizhi1';
        //   } else if($row->first()->item == 28) {
        //     $item = 'neizhi2';
        //   } else if($row->first()->item == 12) {
        //     $item = 'neizhi11';
        //   } else {
        //     continue;
        //   }
          
        //   $row->first()->update(['item' => $item]);
        //   $this->info("已处理完队列：".$i.'---item: '.$item);
        // }
        // $this->info("done-----------------------------------------------");

        // 省份id 转 省份名称 集中存入数据库17109
        for ($i=1; $i <= 417; $i++) { 
          $row = CollectData1Model::where('id', $i)->get();
          if ($row->isEmpty()) {
            continue;
          }
          $provinceId = $row->first()->province;
          $province = CityModel::where('id', $provinceId)->get();
          if ($province->isEmpty()) {
            continue;
          }
          CollectDataModel::create(['item' => $row->first()->item,
                                    'province' => $province->first()->name,
                                    'city' => $row->first()->city,
                                    'area' => $row->first()->area,
                                    'content' => $row->first()->content
                                  ]);
          //$row->first()->update(['province' => $province->first()->name]);
          $this->info("done----->：".$i);
        }
        // $this->info("done all");
      // 同城交友
      //   //转义
      //   function unescape($str) {
      //     return json_decode('"'.str_replace('%', '\\', $str).'"');
      //   }
      //   for ($i=372; $i <= 380; $i++) {
      //       $city = CityModel::where('id',$i)->where('pid','>',0)->get();
      //       if($city->isEmpty()) {
      //         continue;
      //       }

      //       $url = 'http://www.go007.com/'.$city->first()->address_baixing.'/jiaoyou/';
      //       $this->info('正在采集编号-----------------------：'.$i);
      //       $data = QueryList::get($url)->find('.left_box')->find('dl dd')->find('.list a')->attrs('href');
      //       $arr = $data->all();
      //       if (count($arr) == 0) {
      //         continue;
      //       }
      //       $this->info('采集到列表数量：'.count($arr));
      //       //数组去重
      //       $arr = array_unique($arr);
      //       $this->info('开始采集城市：'.$city->first()->name);
      //       $this->collect($arr, $city);
      //       $this->info('采集完毕编号-----------------------：'.$i);
      //   }
       $this->info('完毕编号--------------------------------：'.$i);
    }

    public function collect($arr, $city) {
          // 采集每个用户的信息
        foreach ($arr as $key => $value) {
          $this->info('暂停3秒...');
          sleep(3);
          $this->info('开始采集 '.$value);
          if (!str_contains($value,'http')) {
            $this->info('url不含http，继续下个url');
            continue;
          }
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
            $this->info('没有采集到数据，进入下一个url');
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
}
