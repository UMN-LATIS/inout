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

Broadcast::channel('App.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});


Broadcast::channel('{board}', function (App\User $user, $board) {
	$localBoard = App\Board::where("unit", $board)->get()->first();
	if(!$localBoard) {
		return false;

	}
	return $localBoard->users->contains($user);
});
