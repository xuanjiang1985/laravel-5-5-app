<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Role;
use App\Permission;
use App\User;


class TestController extends Controller
{
    public function test() 
    {
  		// $admin = new Role();
		// $admin->name         = 'admin22121';
		// $admin->display_name = '普通管理员'; // optional
		// $admin->description  = '具有部分权限'; // optional
		// $admin->save();
		// $createPost = new Permission();
		// $createPost->name         = 'create-postt';
		// $createPost->display_name = 'Create Posts'; // optional
		// // Allow a user to...
		// $createPost->description  = 'create new blog posts'; // optional
		// $createPost->save();

		// $admin = Role::where('name','admin')->first();
		// $admin->attachPermission($createPost);
		return 'success';
    }
}
