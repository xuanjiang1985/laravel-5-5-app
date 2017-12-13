<?php

namespace App\TestModel;

use Illuminate\Database\Eloquent\Model;

class CollectDataModel extends Model
{
	protected $table = 't_collect_data';
    public $timestamps = false;
    protected $guarded = ['id'];
}