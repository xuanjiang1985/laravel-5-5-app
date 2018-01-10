<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\User;

class AlgController extends Controller
{
    public function index()
    {	
    	$data = DB::table('users')->orderBy('id','desc')->skip(1)->take(1)->get();
    	//dd($data);
    	//dd(DB::getQueryLog());
    }
}
