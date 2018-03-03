<?php

namespace App\Http\Controllers;
use Auth;
use Illuminate\Http\Request;

class BoardController extends Controller
{

    public function index(Request $request)
    {
    	$boardAdmin = false;
    	if(Auth::user()->boards->find($request->board->id) && Auth::user()->boards->find($request->board->id)->pivot->is_admin) {
    		$boardAdmin = true;
    	}
    	return view('board.index', ['board'=>$request->board->unit, 'boardAdmin'=>$boardAdmin]);
    }
}
