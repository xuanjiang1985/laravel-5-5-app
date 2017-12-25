<?php

namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use JWTAuth;

class ApiTokenAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {   
        if(!$this->isLogin()) {
            return response()->json(['msg' => '未登录','code' => 401,'data' => []], 401);
        }
        return $next($request);
    }

    private function isLogin()
    {
        try {
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return false;
            }else{
                return true;
            }
        } catch (TokenExpiredException $e) {
            return false;
        } catch (TokenInvalidException $e) {
            return false;
        } catch (JWTException $e) {
            return false;
        }

    }
}
