<?php

namespace App\Jobs;

use App\User;
use DB;
use Mail;
use App\Mail\EmailVerification;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Password;

class SendInitialEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;

    /**
     * Create a new job instance.
     * @param User $user
     * @return void
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    public function build()
    {
        //

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $array = array(
            "email" => $this->user->email
        );
        $this->broker()->sendInitialResetLink($array);
        DB::table('users')->where('email','=',$this->user->email)->update(['activated' => '1']);

    }

    public function broker()
    {
        return Password::broker();
    }


}
