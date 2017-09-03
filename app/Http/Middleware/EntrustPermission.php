<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class EntrustPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $permissions)
    {
        if (Auth::guard('admin')->guest() || !Auth::guard('admin')->user()->hasRole(explode('|', $roles))) {
            abort(403);
        }

        return $next($request);
    }
}
