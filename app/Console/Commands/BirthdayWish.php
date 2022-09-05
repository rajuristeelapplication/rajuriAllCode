<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Dealer;
use App\Models\SmsLog;


class BirthdayWish extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'birthdayWish:command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Dealer Birthday Wish';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $getUser   = User::whereDay('dob', date('d'))->whereMonth('dob', date('m'))->get();
        $getDealer = Dealer::whereDay('dob', date('d'))->whereMonth('dob', date('m'))->get();

        if (count($getDealer) > 0) {

            foreach ($getDealer as $dealerData) {

                $smsLogData = [
                    'model'    => 'Dealer',
                    'userId'   => $dealerData->id,
                    'senderId' => config('constant.admin_id'),
                    'title'    => 'Dear '.$dealerData->name.' '.config('constant.birthday_wish_message')
                ];

                SmsLog::create($smsLogData);
            }
        }

        if (count($getUser) > 0) {

            foreach ($getUser as $userData) {

                $smsLogData = [
                    'model'    => 'User',
                    'userId'   => $userData->id,
                    'senderId' => config('constant.admin_id'),
                    'title'    => 'Dear '.$userData->fullName.' '.config('constant.birthday_wish_message')
                ];

                SmsLog::create($smsLogData);
            }
        }

        // echo "<pre>";
        // print_r($getDealer->toArray());
        // exit;
    }
}
