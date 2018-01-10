<?php

namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Middleware\BaseMiddleware;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;

class ApiTokenRefresh extends BaseMiddleware
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
        //return $next($request);
        try {
            $newToken = $this->auth->setRequest($request)->parseToken()->refresh();
        } catch (TokenExpiredException $e) {
            return response()->json(['msg' => 'token 过期','code' => 401,'data' => []], 401);
        } catch (JWTException $e) {
            return response()->json(['msg' => 'token 非法','code' => 401,'data' => []], 401);
        }

        // send the refreshed token back to the client
        //return $response->headers->set('Authorization', 'Bearer '.$newToken);

        return response()->json(['msg' => '操作成功','code' => 0,'data' => ['token' => $newToken]]);
    }
}
