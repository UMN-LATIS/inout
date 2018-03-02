<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Auth;

class InoutResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        $users = $request->board->users;
        
        $isWinner = false;
        if($this->pivot->winner && $this->pivot->winner->isToday()) {
            $isWinner = true;
        }


        $canEdit = false;
        $anyoneCanEdit = false;
        
        if($request->board->anyone_can_edit) {
            $anyoneCanEdit = true;
        }

        if(Auth::user()) {
            if(Auth::user()->global_admin) {
                $canEdit = true;
            }
            if(Auth::user()->id == $this->id) {
                $canEdit = true;
            }

            if(Auth::user()->boards->find($request->board->id) && Auth::user()->boards->find($request->board->id)->pivot->is_admin) {
                $canEdit = true;
            }

        }
        



        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'office' => $this->office,
            'phone' => $this->phone,
            'birthday' => $this->birthday,
            'message' => $this->message,
            'winner' => $isWinner,
            'canEdit' => $canEdit,
            'anyoneCanEdit' => $anyoneCanEdit,
            'status' => $this->signedIn()?true:false

        ];

    }
}
