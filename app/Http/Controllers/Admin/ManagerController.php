<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Validator;

class ManagerController extends Controller
{
    public function __construct()
    {
    	$this->middleware('role:superAdmin');
    }

    public function index() 
    {
    	$managers = User::whereNotin('id',[ 1 ])->where('admin',1)->get();
    	return view('admin.manager.index',['managers' => $managers]);
    }

    public function delete($id) 
    {
    	//if superAdmin
    	if ($id == 1 ) {
    		abort(403);
    	}
    	User::findOrFail($id)->delete();
    	return json_encode(['status' => 1]);
    }

    public function show($id) 
    {
        $manager = User::findOrFail($id);
        return view('admin.manager.show',['manager' => $manager]);
    }

    public function update(Request $request, $id) 
    {

        $validator = Validator::make($request->all(),[
            '昵称' => 'required|max:14',
            '新密码' => 'sometimes|min:6'
            ], [
            'required' => ':attribute 不能为空；',
            'max' => ':attribute 不能多于 :max 个字符；',
            'min' => ':attribute 不能小于 :min 个字符；'
            ]);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
    
        $manager = User::findOrFail($id)->update(['name' => $request->input('昵称')]);
        return back()->with('status','修改成功。');
    }
}
