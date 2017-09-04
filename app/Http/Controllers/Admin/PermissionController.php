<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Role;
use App\Permission;
use App\User;

class PermissionController extends Controller
{
    public function roleIndex()
    {
    	$managers = User::where('admin',1)->orderBy('id')->get();
    	return view('admin.permission.role',['managers' => $managers]);
    }

    //$id 是 user->id
    public function roleDispatch($id)
    {	
    	//排除指定超级管理员$id=1
    	if ($id == 1) {
    		abort(403);
    	}
    	$user = User::findOrFail($id);
    	$userRoles = $user->Roles->pluck('id'); //array
    	$restRoles = Role::whereNotin('id',$userRoles)->get();
    	if ($restRoles->isEmpty()) {
    		return back()->withInput()->withErrors('已无角色分配给该管理员');
    	}
    	return view('admin.permission.roleDispatch',['restRoles' => $restRoles,
    													'user' => $user
    												]);
    }
    //$id 是 user->id
    public function roleDispatched(Request $request, $id)
    {	
    	//排除指定超级管理员$id=1
    	if ($id == 1) {
    		abort(403);
    	}
    	$user = User::findOrFail($id);
    	$user->attachRoles($request->input('checkbox'));
    	return redirect()->route('admin.role');
    }

    //$id 是 user->id
    public function roleDelete($id)
    {	
    	//排除指定超级管理员$id = 1
    	if ($id == 1) {
    		abort(403);
    	}
    	$user = User::findOrFail($id);
    	return view('admin.permission.roleDelete',['userRoles' => $user->Roles,
    													'user' => $user
    												]);
    }

    //$id 是 user->id
    public function roleDeleted(Request $request, $id)
    {	
    	//排除指定超级管理员$id=1
    	if ($id == 1) {
    		abort(403);
    	}
    	$user = User::findOrFail($id);
    	$user->detachRoles($request->input('checkbox'));
    	return redirect()->route('admin.role');
    }

    public function permissionIndex()
    {
    	return view('admin.permission.permission');
    }
}
