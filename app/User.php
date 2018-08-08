<?php

namespace App;

use Auth;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use StudentAffairsUwm\Shibboleth\Entitlement;
use Carbon\Carbon;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'email', 'umndid', 'office','phone','calendar_link','sign_in','sign_out','message', "birthday","slack_user", "team"
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
         'remember_token',
    ];

    protected $dates = [
        'sign_in',
        'sign_out',
        'birthday',
        "message_updated"
    ];

    public function boards() {
        return $this->belongsToMany("App\Board")->withPivot("is_admin","winner")->using('\App\BoardUserPivot');
    }

    public function signedIn() {
        if($this->sign_in && $this->sign_in->isToday()){
            if(!$this->sign_out || $this->sign_out < $this->sign_in) {
                return true;
            }
        }
        return false;
    }

    public function signedOut() {
        if($this->sign_out && $this->sign_out->isToday()){
            if(!$this->sign_in || $this->sign_in < $this->sign_out) {
                return true;
            }
        }
        return false;
    }

    public function signIn() {
        $this->sign_out = null;
        $this->sign_in = Carbon::now();
    }

    public function signOut() {
        $this->sign_in = null;
        $this->sign_out = Carbon::now();
    }

    public function setMessageAttribute($value) {
        $this->attributes['message'] = $value;
        $this->message_updated = Carbon::now();
    }

}
