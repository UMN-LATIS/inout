<?php

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

use App\Board;
use Illuminate\Broadcasting;


// this is a hack to allow private channels with non-authed users
// when they make a request and they're not logged in, we make a temporary user which allows us to do
// token negoation with laravel-echo, etc.
// this doesn't get persisted
// 
// 
// Route::group(['middleware' => 'api'], function () {
// 	Route::post('/broadcasting/auth', function(Illuminate\Http\Request $req) {
// 		$bc = new \Illuminate\Broadcasting\BroadcastController;
// 		if(Auth::guest()) {
// 			Auth::loginUsingId(18);
// 		}
// 		return $bc->authenticate($req);
// 	});
// });

Broadcast::channel('{board}', function (App\User $user, $board) {

	$localBoard = App\Board::where("unit", $board)->get()->first();
	if(!$localBoard) {
		return false;

	}
	if($localBoard->public) {
		return true;
	}
	return $localBoard->users->contains($user);
});
