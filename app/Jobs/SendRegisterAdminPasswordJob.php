<?php

namespace App\Jobs;

use Mail;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Mail\SendRegisterAdminPassword;


class SendRegisterAdminPasswordJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $id;
    protected $password;
    protected $siteUrl;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($id, $password, $siteUrl)
    {
        $this->id = $id;
        $this->password = $password;
        $this->siteUrl = $siteUrl;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $id = $this->id;
        $password  = $this->password;
        $siteUrl  = $this->siteUrl;

        $userDetails = User::where(['id'=>$id])->first();

        $email = new SendRegisterAdminPassword($userDetails, $password, $siteUrl);

        Mail::to($userDetails->email)->send($email);
    }
}
