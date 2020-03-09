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

        if(stristr($request->path(), "slack")) {
            return $next($request);
        }
        if($request->board && is_object($request->board) && !$request->board->public) {       
            if($request->secret && $request->user) {
                if(substr(sha1($request->user->id . "hehaha"), 0, 5) != $request->secret) {
                    abort(403);
                }
            }
            else {
                if(Auth::user() && Auth::user()->guest_user) {
                    Auth::logout();    
                }
                Auth::authenticate();
                if(!Auth::user()->boards->find($request->board->id)) {
                    abort(403);
                }
            }
        }
        else if($request->board && is_object($request->board) && $request->board->public) {
            if(!Auth::user()) {
                $user = new \App\User;
                $user->first_name="Guest";
                $user->last_name="User";
                $user->guest_user = true;
                $user->save();
                Auth::login($user);
            }
        }
        return $next($request);
    }
}
