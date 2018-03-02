<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class AuthIfNecessary
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
        if($request->board && is_object($request->board) && !$request->board->public) {
            Auth::authenticate();
            if(!Auth::user()->boards->find($request->board->id)) {
                abort(403);
            }
        }
        return $next($request);
    }
}
