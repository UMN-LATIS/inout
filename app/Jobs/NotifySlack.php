<?php

namespace App\Jobs;
use App\User;
use App\Board;
use Log;
use UMNLatis\Slack\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class NotifySlack implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;
    protected $board;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user, Board $board)
    {
        $this->user = $user;
        $this->board = $board;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        try {
            $slackUsers = $this->board->getSlackUsers();
            foreach ($slackUsers as $slackUser)
            {
                if($slackUser->handle() == $this->user->slack_user) {
                    $userId = $slackUser->id();
                    $status["status_text"] = $this->user->message;
                    // $status["status_emoji"] = "";
                    $this->board->slackConnector()->request("users.profile.set", ["token"=>$this->board->slack_token,"profile"=>json_encode($status), "user"=>$userId])->send()->json();
                }
            }
    
        }
        catch (Exception $e) {
        }
            
    }
}
