<?php

namespace App\Http\Middleware;

use Closure;

class SetBoard
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

        config(['app.targetboard' => $request->board]);
        $board = \App\Board::where("unit",$request->board)->get();
        if($board->count() > 0) {
            $request->merge(["board" => $board->first()]);
        }
        else {
            // abort(403, 'Invalid Board');
        }

        return $next($request);
    }
}
