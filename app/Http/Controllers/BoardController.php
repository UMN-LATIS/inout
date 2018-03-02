<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BoardController extends Controller
{

    public function index(Request $request)
    {
    	return view('board.index', ['board'=>$request->board->unit]);
    }
}
