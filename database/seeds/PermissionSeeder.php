<?php

use Illuminate\Database\Seeder;
use App\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::insert([
        	[	
                'item' => 1,
        		'name' => 'create-post',
        		'display_name' => '创建邮箱',
        		'description' => '',
        	],
        	[	
        		'name' => 'create-article',
        		'display_name' => '创建文章',
        		'description' => '',
        	],
        	[	
        		'name' => 'update-article',
        		'display_name' => '更新文章',
        		'description' => '',
        	],
        	[	
                'item' => 1,
        		'name' => 'delete-article',
        		'display_name' => '删除文章',
        		'description' => '',
        	],
        ]);
    }
}
