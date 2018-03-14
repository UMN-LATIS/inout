<?php

namespace App\Http\Controllers;
use Auth;
use Illuminate\Http\Request;

class BoardController extends Controller
{

    public function __construct() {
        $this->middleware('auth')->only(['login']);
    }

    public function index(Request $request)
    {
    	$boardAdmin = false;
        if(Auth::user() ) {
            if(Auth::user()->boards->find($request->board->id) && Auth::user()->boards->find($request->board->id)->pivot->is_admin) {
                $boardAdmin = true;
            }
            if(Auth::user()->global_admin) {
                $boardAdmin = true;
            }    
        }

        return view('board.index', ['board'=>$request->board, 'boardAdmin'=>$boardAdmin]);
    }

    public function login(Request $request) {
        
        return redirect()->action('BoardController@index', ['board'=>$request->board->unit]);
    }
}
