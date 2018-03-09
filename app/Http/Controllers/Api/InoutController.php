<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Events\UserChangedEvent;

class InoutController extends Controller
{
	/*
	 * Get all the users for this board with their status
	 */
	
    public function index(Request $request)
    {
        
        $request->board->setWinner();
        
        return \App\Http\Resources\InoutResource::collection($request->board->users);
    }

    public function show(Request $request, \App\User $user)
    {
        return new InoutResource($user);
    }

    // don't understand why we need the board here???
    public function update(Request $request, $board, \App\User $user) {
        $user->fill($request->all());
        $user->save();
        event(new UserChangedEvent($request->board));
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
        $username = $request->get("newUser");
        $user = \App\User::where("internet_id", $username);
        if($user->count() == 0) {
            $foundUser = new \App\User;
            $foundUser->internet_id = $username;
            $foundUser->last_name = $username;
            $foundUser->first_name = $username;
            $foundUser->email = $username;
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
