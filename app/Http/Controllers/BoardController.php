<?php

namespace App\Http\Controllers;
use Auth;
Use Log;
use Illuminate\Http\Request;
use App\Events\UserChangedEvent;

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

        $event = $request->json("event");
        if($event['type'] == "user_change") {
            $username = $event['user']['name'];
            $status = $event['user']['profile']['status_text'];

            if($user = \App\User::where("slack_user", $username)->first()) {
                $user->message = $status;
                $user->save();
                event(new UserChangedEvent($request->board));
            }
        }

        return response()->json(["success"=>true]);
    }

    public function slackSlashEndpoint(Request $request, $board) {

        $token = $request->get("token");
        $callingUser = $request->get("user_id");
        $commandText = $request->get("text");

        $slackUsers = $request->board->getSlackUsers();

        if($commandText == "in" || $commandText == "out") {

            foreach ($slackUsers as $slackUser)
            {
                if($slackUser->id() == $callingUser) {
                    if($user = \App\User::where("slack_user", $callingUser)->first()) {
                        if($commandText == "in") {
                            $user->signIn();
                            $response = "Welcome!";
                        }
                        else {
                            $user->signOut();
                            $response = "Goodbye!";
                        }
                        return response()->json([
                            'text' => $response,
                        ]);
                    }
                }
            }
        }
        else {

            $user = preg_match('/@\w+/', $commandText, $matches);
            if($matches[0]) {

                $targetUser = $matches[0];
                foreach ($slackUsers as $slackUser)
                {
                    if($slackUser->id() == $targetUser) {
                        if($user = \App\User::where("slack_user", $callingUser)->first()) {

                            $response = $user->first_name . " " . $user->last_name . " is " . $user->signedIn()?"*in*":"*out*.\n";
                            if($user->message() && strlen($user->message)> 1) {
                                $response .= "Status: " . $user->message . "\n";
                            }

                            $repsonse .="Contact Info: " . $user->email . ", " . $user->phone . "\n";
                            $response .= $user->office;


                            return response()->json([
                                'text' => $response,
                            ]);


                        }
                    }
                }
            }
        }
        return response()->json([
            'text' => "Err, sorry.  I got confused.",
        ]);


    }
}
