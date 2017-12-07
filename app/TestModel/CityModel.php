<?php

namespace App\TestModel;

use Illuminate\Database\Eloquent\Model;

class CityModel extends Model
{
	protected $table = 't_address_city';
    public $timestamps = false;
    protected $guarded = ['id'];
}
