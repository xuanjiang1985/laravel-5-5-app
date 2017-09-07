<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Validator, Hash;

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

    public function create() 
    {
        return view('admin.manager.create');
    }

    public function store(Request $request) 
    {
        $validator = Validator::make($request->all(),[
            '昵称' => 'required|max:14',
            '邮箱' => 'required|email',
            '密码' => 'required|min:6'
            ], [
            'required' => ':attribute 不能为空；',
            'min' => ':attribute 不能少于 :min 个字符；',
            'max' => ':attribute 不能多于 :max 个字符；',
            'email' =>  ':attribute 格式不对；'

            ]);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        User::create(['name' => $request->input('昵称'),
                        'admin' => 1,
                        'email' => $request->input('邮箱'),
                        'password' => Hash::make( $request->input('密码'))
                        ]);
        return redirect()->route('admin.manager')->with('status','添加管理员成功。');
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
        //if superAdmin
        if ($id == 1 ) {
            abort(403);
        }

        $validator = Validator::make($request->all(),[
            '昵称' => 'required|max:14'
            ], [
            'required' => ':attribute 不能为空；',
            'max' => ':attribute 不能多于 :max 个字符；'   
            ]);

        $validator2 = Validator::make($request->all(),[
            '新密码' => 'min:6'
            ], [
            'min' => ':attribute 不能小于 :min 个字符；'
            ]);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        if($request->input('新密码') != '') {
            if ($validator2->fails()) {
            return back()->withErrors($validator2)->withInput();
            }
            //updating
            $manager = User::findOrFail($id)->update(['name' => $request->input('昵称'),
                                                        'password' => Hash::make($request->input('新密码'))
                                                        ]);
            return back()->with('status','修改成功。');
        }
    
        $manager = User::findOrFail($id)->update(['name' => $request->input('昵称')]);
        return back()->with('status','修改成功。');
    }
}
