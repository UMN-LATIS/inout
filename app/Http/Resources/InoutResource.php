<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

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
        
        $isWinner = false;
        if($this->pivot->winner && $this->pivot->winner->isToday() && $this->signedIn()) {
            $isWinner = true;
        }
        $earlyBird = false;
        if($this->pivot->early_bird &&   $this->pivot->early_bird->isToday() && $this->signedIn()) {
            $earlyBird = true;
        }

        $happyBirthday = false;

        if($this->birthday) {
            $now = Carbon::now();
            if($this->birthday->month == $now->month && $this->birthday->day == $now->day) {
                $happyBirthday = true;
            }
        }


        $canEdit = false;
        $anyoneCanEdit = false;
        $isAdmin = false;

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
        


        $userHash = null;

        if($canEdit) {
            $userHash = substr(sha1($this->id . "hehaha"), 0, 5);
        }

        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'office' => $this->office,
            'phone' => $this->phone,
            'calendar_link' => $this->calendar_link,
            'birthday' => $this->birthday?$this->birthday->format("Y-m-d"):"",
            'message' => $this->message?$this->message:"",
            'winner' => $isWinner,
            'earlyBird' => $earlyBird,
            'happyBirthday' => $happyBirthday,
            'canEdit' => $canEdit,
            'anyoneCanEdit' => $anyoneCanEdit,
            'isAdmin' => $this->pivot->is_admin,
            "slack_user" => $this->slack_user,
            'lastUpdated' => $this->updated_at?$this->updated_at->format("n/j"):"",
            'status' => $this->signedIn()?true:false,
            'userHash' => $userHash
        ];

    }
}
