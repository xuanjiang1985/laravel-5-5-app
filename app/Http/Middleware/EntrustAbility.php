<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class EntrustAbility
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $roles, $permissions, $validateAll = false)
    {
        if (Auth::guard('admin')->guest() || !Auth::guard('admin')->user()->ability(explode('|', $roles), explode('|', $permissions), array('validate_all' => $validateAll))) {
            abort(403);
        }

        return $next($request);
    }

}
