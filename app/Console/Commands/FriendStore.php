<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\TestModel\CollectDataModel;
use App\TestModel\CityModel;
use App\TestModel\CollectData1Model;

class FriendStore extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'friend:store';

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
        for ($i=57102; $i < 57103 ; $i++) { 
          $row = CollectData1Model::where('id', $i)->get();
          if ($row->isEmpty()) {
            continue;
          }
          // $provinceId = $row->first()->province; //pid
          // $province = CityModel::where('id', $provinceId)->get();
          // if ($province->isEmpty()) {
          //   continue;
          // }
          //处理json中的age
          $contentJson = $row->first()->content;
          $contentArr = json_decode($contentJson, true);
          
          if (isset($contentArr['age']) && strlen($contentArr['age']) > 3) {
            $contentArr['age'] = '';
          }
          // 处理编码
          if (count($contentArr) == 0) {
            $this->info("队列：".$i." 不能处理已跳过----------------------------");
            continue;
          }
          foreach ($contentArr as $key => $value) {
            $contentArr[$key] = urlencode($value);
          }
          CollectDataModel::create(['province' => $row->first()->province,
                                    'item' => 28,
                                    'city' => $row->first()->city,
                                    'content' => urldecode(json_encode($contentArr))
                                    ]);
          $this->info("已处理完队列：".$i);
        }
        //return 1;
        $this->info("done-----------------------------------------------");
    }
}
        