<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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
        return response()->json(["success"=>true]);
    }

    public function toggleStatus(Request $request, $board, \App\User $user)
    {
        // lookup user
        // 
        
        if($user->signedIn()) {
            $user->signOut();
        }
        else {
            $user->signIn();
        }

        $user->save();
        // verify access
        // 
        // 
        // set status

    	// return confirmation

        return response()->json(["success"=>true]);
    }

}
