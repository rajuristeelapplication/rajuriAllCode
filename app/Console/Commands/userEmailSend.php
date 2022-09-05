<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Mail\UserEmail;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class userEmailSend extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:userEmailSend';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'User send mail';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }


    public function handle()
    {



        ini_set('max_execution_time', '0');
        // $userId = $this->argument('userId');


        $users = User::where('status',0)->get();

        foreach($users as $value)
        {

            try {
                if (!empty($value)) {
                    \Log::debug($value->email);

                    Mail::to($value->email)->send(new UserEmail($value));
                }
            } catch (\Exception $e) {
                \Log::debug($e);
                continue;
            }
            \Log::debug($value->email ." Email Send");
            User::where('id', $value->id)->update(['status'=>1]);
      }
    }
}
