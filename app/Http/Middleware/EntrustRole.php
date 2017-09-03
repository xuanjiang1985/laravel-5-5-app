<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class EntrustRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $roles)
    {
        if (Auth::guard('admin')->guest() || !Auth::guard('admin')->user()->hasRole(explode('|', $roles))) {
            //return redirect()->route('admin.login');
            abort(403);
        }
        return $next($request);
    }
}
