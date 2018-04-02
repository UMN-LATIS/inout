<?php

namespace App\Http\Controllers;
use Auth;
use Illuminate\Http\Request;

class BoardController extends Controller
{

    public function __construct() {
        $this->middleware('auth')->only(['loginRedirect']);
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
        if(Auth::user() && Auth::user()->guest_user) {
            Auth::logout();    
        }
        
        return redirect()->action('BoardController@loginRedirect', ['board'=>$request->board->unit]);
    }


    public function loginRedirect(Request $request) {
        return redirect()->action('BoardController@index', ['board'=>$request->board->unit]);
    }


    public function slackEndpoint(Request $request, $board) {
        if($request->json("type") == "url_verification") {
            return $request->json("challenge");
        }

        Log::error($request->json()->all());
        return response()->json(["success"=>true]);
    }


}
