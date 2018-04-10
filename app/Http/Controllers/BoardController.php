<?php

namespace App\Http\Controllers;
use Auth;
Use Log;
use Illuminate\Http\Request;
use App\Events\UserChangedEvent;
Use App\Jobs\NotifySlack;

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
        $explodedCommand = explode(" ", $commandText);
        $command = strtolower(array_shift($explodedCommand));
        if($command == "in" || $command == "out") {
            foreach ($slackUsers as $slackUser)
            {
                if($slackUser->id() == $callingUser) {
                    if($user = \App\User::where("slack_user", $slackUser->handle())->first()) {
                        if($command == "in") {
                            $user->signIn();
                            $response = "Welcome!";
                        }
                        else {
                            $user->signOut();
                            $response = "Goodbye!";
                        }

                        if(isset($explodedCommand[0])) {
                            $message = implode(" ", $explodedCommand);
                            $user->message = $message;
                        }
                        $user->save();
                        $request->board->setEarlyBird();
                        event(new UserChangedEvent($request->board));
                        NotifySlack::dispatch($user, $request->board);
                        return response()->json([
                            'text' => $response,
                        ]);
                    }
                }
            }
        }
        elseif($command = "status") {
            $text = array();
            foreach($request->board->users as $user) {
                if($user->signedIn()) {
                    $slackInfo = null;
                    if($user->slack_user) {
                        $slackInfo = " (<@" . $user->slack_user . ">)";
                    }
                    $text[] = $user->first_name . " " . $user->last_name . $slackInfo;
                }
            }
            return response()->json([
                'text' => "The following members are currentl *in*:\n" . implode(" | ", $text),
                'attachments' => [
                    [
                        "text"=>"test|test|test\nTest2|test4|tesg4",
                    ]
                ]
            ]);
        }
        else {

            $user = preg_match('/@\w+/', $commandText, $matches);
            if($matches[0]) {

                $targetUser = str_replace("@", "", $matches[0]);
                foreach ($slackUsers as $slackUser)
                {
                    if(strcasecmp($slackUser->id(), $targetUser) == 0) {
                        if($user = \App\User::where("slack_user", $slackUser->handle())->first()) {
                            $status = $user->signedIn()?"*in*":"*out*";
                            $response = $user->first_name . " " . $user->last_name . " is " . $status . " \n";
                            if($user->message && strlen($user->message)> 1) {
                                $response .= "Status: " . $user->message . "\n";
                            }

                            $response .="Contact Info: " . $user->email . ", " . $user->phone . "\n";
                            if($user->office && strlen($user->office)> 1){
                                $response .= $user->office;    
                            }

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
