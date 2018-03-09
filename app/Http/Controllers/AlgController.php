<?php

namespace App\Http\Controllers;

class AlgController extends Controller
{
    public function index()
    {
        // array_walk();
        //       $num = [12,32,43];
        //       $fruits = ['d' => 'lemon', 'a' => 'orange', 'b' => 'banana', 'c' => 'candy', 'e' => 'elestic'];
        //       $a = array(
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
        //       //dd($fruits);
        //       list($a, $b, $c) = $num;
        //       dd(touch('test.txt'));
        // try {
        //     $file = fopen(public_path() . "/test.txt", "a") or die("not found");
        //     fwrite($file, "zhougang1111\n");
        //     fclose($file);
        // } catch (\Exception $e) {
        //     return $e->getMessage();
        // }
        echo phpinfo();
    }
}
