<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\User;

class AlgController extends Controller
{
    public function index()
    {
    	// array_walk();
  //   	$num = [12,32,43];
  //   	$fruits = ['d' => 'lemon', 'a' => 'orange', 'b' => 'banana', 'c' => 'candy', 'e' => 'elestic'];
  //   	$a = array(
		//   array(
		//     'id' => 5698,
		//     'first_name' => 'Bill',
		//     'last_name' => 'Gates',
		//   ),
		//   array(
		//     'id' => 4767,
		//     'first_name' => 'Steve',
		//     'last_name' => 'Jobs',
		//   ),
		//   array(
		//     'id' => 3809,
		//     'first_name' => 'Mark',
		//     'last_name' => 'Zuckerberg',
		//   )
		// );
  //   	//dd($fruits);
  //   	list($a, $b, $c) = $num;
  //   	dd(touch('test.txt'));
    	// $file = fopen(public_path()."/test.txt","a") or die("not found");
    	// fwrite($file, "zhougang1\n");
    	// fclose($file);
    	User::find(5)->update(['name' => 'zhouzhou3']);
    	$str = "Hello world";
		$str2 = "world";
		dd(connection_status());
	}
}
