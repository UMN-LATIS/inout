<?php

namespace App\Http\Controllers\Api;

Use Log;
use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Events\UserChangedEvent;
use ThreadMeUp\Slack\Client;

class InoutController extends Controller
{
	/*
	 * Get all the users for this board with their status
	 */
	
    public function index(Request $request)
    {
        
        $request->board->setWinner();
        
        $jsonResource = \App\Http\Resources\InoutResource::collection($request->board->users);
        return $jsonResource;
    }

    public function show(Request $request, \App\User $user)
    {
        return new InoutResource($user);
    }

    // don't understand why we need the board here???
    public function update(Request $request, $board, \App\User $user) {
        $user->fill($request->all());
        $user->save();

        if(Auth::user() && (Auth::user()->boards->find($request->board->id)->pivot->is_admin || Auth::user()->global_admin)) {
            $user->boards->find($request->board->id)->pivot->is_admin = $request->get("isAdmin");
            $user->boards->find($request->board->id)->pivot->save();
        }
        
        event(new UserChangedEvent($request->board));

        if($request->board->push_to_slack && $request->board->slack_token) {
            $config = [
                'token' => $request->board->slack_token,
                'team' => 'latis-team',
                'username' => 'BOT-NAME',
                'icon' => 'ICON', // Auto detects if it's an icon_url or icon_emoji
                'parse' => '', // __construct function in Client.php calls for the parse parameter 
            ];

            try {
                $slack = new Client($config);
                $slackUsers = $slack->users();
                foreach ($slackUsers as $slackUser)
                {
                    if($slackUser->handle() == $user->slack_user) {
                        $userId = $slackUser->id();
                        $status["status_text"] = $user->message;
                        $status["status_emoji"] = "";
                        $slack->request("users.profile.set", ["token"=>$request->board->slack_token,"profile"=>json_encode($status), "user"=>$userId])->send()->json();
                    }
                }
        
            }
            catch (Exception $e) {

            }
            
        }
        

        return response()->json(["success"=>true]);
    }

    public function destroy(Request $request, $board, \App\User $user) {
        $user->boards()->detach($request->board);
        $user->save();
        event(new UserChangedEvent($request->board));
        return response()->json(["success"=>true]);
    }

    public function toggleStatus(Request $request, $board, \App\User $user)
    {
        // check perms
        
        if($user->signedIn()) {
            $user->signOut();
        }
        else {
            $user->signIn();
        }

        $user->save();
        $request->board->setEarlyBird();

        event(new UserChangedEvent($request->board));
        return response()->json(["success"=>true]);
    }

    public function setStatus(Request $request, $board, \App\User $user, $status)
    {
        // check perms
        
        if($status == "in") {
            $user->signIn();
        }
        if($status == "out") {
            $user->signOut();
        }

        
        $user->save();

        event(new UserChangedEvent($request->board));
        return response()->json(["success"=>true]);
    }


    public function createUser(Request $request, $board)
    {
        if(!Auth::user()->boards->find($request->board->id)->pivot->is_admin && !Auth::user()->global_admin) {
            return response()->json(["success"=>false]);
        }

        $username = $request->get("newUser");
        $user = \App\User::where("umndid", $username);
        if($user->count() == 0) {
            $foundUser = new \App\User;
            putenv('LDAPTLS_REQCERT=never');
            $connect = ldap_connect( 'ldaps://ldap-dsee.oit.umn.edu', 636);
            $base_dn = array("o=University of Minnesota, c=US",);
            ldap_set_option($connect, LDAP_OPT_PROTOCOL_VERSION, 3);
            ldap_set_option($connect, LDAP_OPT_REFERRALS, 0);
            $r=ldap_bind($connect);

            $filter = "(cn=" . $username . ")";
            $search = ldap_search([$connect], $base_dn, $filter);
            $foundUser->umndid = $username;
            $foundUser->last_name = $username;
            $foundUser->first_name = $username;
            $foundUser->email = $username;

            foreach($search as $readItem) {

                $info = ldap_get_entries($connect, $readItem);
                $foundUser->umndid = $username;
                $foundUser->last_name = isset($info[0]["sn"])?$info[0]["sn"][0]:$username;
                $foundUser->first_name = isset($info[0]["givenname"])?$info[0]["givenname"][0]:$username;
                $foundUser->email =isset( $info[0]["umndisplaymail"])?$info[0]["umndisplaymail"][0]:$username;
                $foundUser->office = isset($info[0]["umnofficeaddress1"])?$info[0]["umnofficeaddress1"][0]:$username;
                break;
            }
            
            $foundUser->save();
        }
        else {
            $foundUser = $user->get();
        }
        
        $request->board->users()->attach($foundUser);

        broadcast(new UserChangedEvent($request->board));
        return response()->json(["success"=>true]);
    }

}
