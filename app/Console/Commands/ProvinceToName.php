<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\TestModel\CollectDataModel;
use App\TestModel\CityModel;

class ProvinceToName extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'province:set';

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
        for ($i=26151; $i < 26151 ; $i++) { 
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
          $this->info("已处理完队列：".$i);
        }
        $this->info("done-----------------------------------------------");
    }
}
