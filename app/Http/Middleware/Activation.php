<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class Activation
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

        if (Auth::user()->isActive == 0) {
            return response()->json([
                'status' => -1,
                'result' => new \stdClass(),
                'message' => trans('api.auth.inactive_user'),
            ]);
        }

        return $next($request);
    }
}
