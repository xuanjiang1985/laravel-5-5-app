<?php

use Illuminate\Database\Seeder;
use App\User;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::insert([
        	[	
        		'admin' => 1,
        		'name' => 'zhouzhou00',
        		'email' => 'zhouzhou00@163.com',
        		'password' => bcrypt('123456')
        	],
        	[
        		'admin' => 0,
        		'name' => 'zhouzhou01',
        		'email' => 'zhouzhou01@163.com',
        		'password' => bcrypt('123456')
        	]
        ]);
    }
}
