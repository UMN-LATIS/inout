<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Board extends Model
{

	protected $fillable = [
        'unit','public_title','announcement_text', 'anyone_can_edit', 'public'
    ];



    public function users() {
        return $this->belongsToMany("App\User")->withPivot("is_admin","winner")->using('\App\BoardUserPivot');
    }

    public function setWinner() {
    	$haveWinner = false;
    	foreach($this->users as $user) {
    		if($user->pivot->winner && $user->pivot->winner->isToday()) {
    			$haveWinner = true;
    		}
    	}

    	if(!$haveWinner) {
    		$winner = $this->users->random();
    		$winner->pivot->winner = Carbon::now();
    		$winner->pivot->save();
    	}
    }

    public $timestamps = false;
}
