<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\TestModel\CityModel;
use App\TestModel\CollectCityModel;

class GetCurrentCityArray extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get:city';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '获取用户常用城市的数组';

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
        $this->info("开始处理...");
        $citys = CollectCityModel::all()->pluck('city')->toArray();
        //dd($city);
        $cityId = [];
        foreach ($citys as $key => $value) {
            $city = CityModel::where('name',$value)->where('pid', '>', 0)->get();
            if ($city->isEmpty()){
                continue;
            }
            $cityId[] = $city->first()->id;
        }
        // 写入文件
        $content = '[';
        $cityStr = implode(',', $cityId);
        $content .= $cityStr . ']';
        $fp = fopen('cityarr.txt','w+');
        fwrite($fp, $content);
        fclose($fp);
        $this->info("done");
    }
}
