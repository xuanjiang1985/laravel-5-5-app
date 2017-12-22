<?php

namespace App\TestModel;

use Illuminate\Database\Eloquent\Model;

class CollectData1Model extends Model
{
	protected $table = 't_collect_data2';
    public $timestamps = false;
    protected $guarded = ['id'];
}