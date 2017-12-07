<?php

namespace App\TestModel;

use Illuminate\Database\Eloquent\Model;

class DistrictModel extends Model
{
	protected $table = 't_address_district';
    public $timestamps = false;
    protected $guarded = ['id'];
}
