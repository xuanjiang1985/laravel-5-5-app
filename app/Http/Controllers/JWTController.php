<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class JWTController extends Controller
{
    public function auth(Request $request)
    {
        // grab credentials from the request
        $credentials = $request->only('email', 'password');

        try {
            // attempt to verify the credentials and create a token for the user
            if (! $token = JWTAuth::attempt($credentials)) {
                return $this->error('用户名或密码错误');
            }
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return $this->error('token 创建失败');
        }

        // all good so return the token
        return $this->success(['token' => $token]);
    }
    public function user()
    {
    	$user = JWTAuth::parseToken()->authenticate();
    	return $this->success(['user' => $user->toArray()]);
    }

    public function refresh()
    {
    	return $this->success();
    }
}
