<?php

namespace App\TestModel;

use Illuminate\Database\Eloquent\Model;

class CollectCityModel extends Model
{	
	// 用户常用采集城市
	protected $table = 't_collect_city';
    public $timestamps = false;
    protected $guarded = ['id'];
}