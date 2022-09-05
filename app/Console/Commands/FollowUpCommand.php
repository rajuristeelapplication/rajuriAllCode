<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\FollowUp;
use Illuminate\Console\Command;
use App\Helpers\NotificationHelper;


class FollowUpCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'followUp:command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Follow UP Reminder';

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
        $dateTime = Carbon::now()->format('Y-m-d H:i');

        // echo $dateTime;

        // $followUps = FollowUp::selectRaw("DATE_FORMAT(fReminder, '" . config('constant.follow_up_time_format') . "')")->get();
        $followUps = FollowUp::getSelectQuery()->whereRaw("DATE_FORMAT(fReminder, '" . config('constant.follow_up_time_format') . "') = ?", [$dateTime])->where('fIsDone',0)->get();


        if(count($followUps) > 0)
        {
            foreach($followUps as $key=>$value){

                // $value->fIsDone = 1;
                $value->save();
                NotificationHelper::followUpNotification($value);
            }
        }

    }
}
