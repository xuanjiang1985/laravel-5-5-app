<?php

use Illuminate\Database\Seeder;
use App\Role;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::insert([
        	[	
        		'name' => 'superAdmin',
        		'display_name' => '超级管理员',
        		'description' => '所有权限',
        	],
        	[	
        		'name' => 'admin',
        		'display_name' => '普通管理员',
        		'description' => '部分权限',
        	],
            [   
                'name' => 'editor',
                'display_name' => '文章管理员',
                'description' => '管理文章',
            ],
            [   
                'name' => 'editor',
                'display_name' => '非正式管理员',
                'description' => '管理文章',
            ],
        ]);
    }
}
