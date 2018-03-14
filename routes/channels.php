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

// Broadcast::channel('App.User.{id}', function ($user, $id) {
//     return (int) $user->id === (int) $id;
// });
Route::group(['middleware' => 'web'], function () {
	Route::post('/broadcasting/auth', function(Illuminate\Http\Request $req) {
		$bc = new \Illuminate\Broadcasting\BroadcastController;
		Auth::login(new \App\User);

		return $bc->authenticate($req);
	});
});

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
