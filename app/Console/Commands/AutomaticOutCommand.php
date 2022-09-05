<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\InOut;


class AutomaticOutCommand extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */

    protected $signature = 'automaticOut:command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'automatic out command';

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

        $todayDate =  date('Y-m-d');

        $todayDateTime = date('Y-m-d H:i:s');

        $notOutRecords = InOut::whereNull('outDateTime')->whereDate('date',$todayDate)->get();


        if ($notOutRecords->isNotEmpty())
        {
            foreach($notOutRecords as $notOutRecord)
            {
                $notOutRecord->outDateTime = $todayDateTime;
                $notOutRecord->outAddress = 'Automatic Out';
                $notOutRecord->save();
            }
        }



    }
}
