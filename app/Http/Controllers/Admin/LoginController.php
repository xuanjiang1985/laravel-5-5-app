<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\AdminChangePassword;
use Auth, Log, Hash;

class LoginController extends Controller
{
    public function getLogin()
    {
    	return view('admin.login');
    }

    public function index()
    {
    	return view('admin.index');
    }

    public function postLogin(Request $request)
    {
    	$this->validate($request, ['email' => 'required', 'password' => 'required']);
        $ip = $_SERVER['REMOTE_ADDR'];
        if (Auth::guard('admin')->attempt(['email' => $request['email'], 'password' => $request['password'], 'admin' => 1])) {
            Log::info('管理员 '.$request['email'].' 登陆成功，IP为：'.$ip);
            return redirect()->route('admin');
        } else {
            Log::warning('管理员 '.$request['email'].' 尝试登陆失败，IP为：'.$ip);
            return back()->withInput()->withErrors('账号或密码错误！');
        }
    }

    public function getLogout()
    {
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login');
    }

    public function changePassword()
    {
        $user = Auth::guard('admin')->user();
        return view('admin.changePassword',['user' => $user]);
    }

    public function changedPassword(AdminChangePassword $request)
    {
        $user = Auth::guard('admin')->user();
        if (!Hash::check($request->input('旧密码'), $user->password)) {
            return back()->withErrors('验证旧密码不对。')->withInput();
        }
        $user->fill([
            'password' => Hash::make($request->input('新密码'))
            ])->save();
        Log::info($user->email.' 修改了自己的密码。');
        return back()->with('status','密码修改成功。');
    }

    public function demo1()
    {
        if(!Auth::guard('admin')->user()->hasRole('superAdmin')) { 
            abort(403);
        }
        return view('admin.demo1');
    }

    public function demo2()
    {
        if(!Auth::guard('admin')->user()->ability('superAdmin','create-post')) { 
            abort(403);
        }
        return view('admin.demo2');
    }


}
