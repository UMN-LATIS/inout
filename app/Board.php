<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use CoryKeane\Slack\Client;

class Board extends Model
{

	protected $fillable = [
        'unit','public_title','announcement_text', 'anyone_can_edit', 'public', 'push_to_slack', 'slack_token'
    ];



    public function users() {
        return $this->belongsToMany("App\User")->withPivot("is_admin","winner", "early_bird")->using('\App\BoardUserPivot')->orderBy("last_name")->orderBy("first_name");
    }

    public function setWinner() {
    	$haveWinner = false;
    	foreach($this->users as $user) {
    		if($user->pivot->winner && $user->pivot->winner->isToday()) {
    			$haveWinner = true;
    		}
    	}

    	if(!$haveWinner && count($this->users) > 0) {
    		$winner = $this->users->random();
    		$winner->pivot->winner = Carbon::now();
    		$winner->pivot->save();
    	}
    }

    public function setEarlyBird() {
        $haveEarlyBird = false;
        foreach($this->users as $user) {
            if($user->pivot->early_bird && $user->pivot->early_bird->isToday()) {
                $haveEarlyBird = true;
            }
        }

        $earliestUser = null;
        if(!$haveEarlyBird) {
            foreach($this->users as $user) {
                if(!$user->sign_in || !$user->sign_in->isToday()) {
                    continue;
                }
                if(!$earliestUser) {
                    $earliestUser = $user;
                    continue;
                }
                if($user->sign_in < $earliestUser->sign_in) {
                    $earliestUser = $user;
                }

            }

            $earliestUser->pivot->early_bird = Carbon::now();
            $earliestUser->pivot->save();
        }
    }


    public function getEarlyBird() {
        $earliestUser = null;
        foreach($this->users as $user) {
            if(!$user->pivot->early_bird || !$user->pivot->early_bird->isToday()) {
                return $user;
            }
        }
        return false;
    }

    public function slackConnector() {
        $config = [
        'token' => $this->slack_token,
        'team' => 'latis-team',
        'username' => 'BOT-NAME',
        'icon' => 'ICON', // Auto detects if it's an icon_url or icon_emoji
        'parse' => '', // __construct function in Client.php calls for the parse parameter 
        ];
        $slack = new Client($config);
        return $slack;
    }

    public function getSlackUsers() {
        $slackUsers = $this->slackConnector()->users();
        return $slackUsers;
    }

    public $timestamps = false;
}
