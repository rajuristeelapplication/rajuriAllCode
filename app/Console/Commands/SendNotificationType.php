<?php

namespace App\Console\Commands;

use App\Model\NotificationTemplate;
use App\Model\RestaurantCustomer;
use Illuminate\Console\Command;
use App\Models\Notification;
use App\Models\User;
use App\Helpers\PushNotificationHelper;

class SendNotificationType extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'SendNotificationType:send {module?} {moduleId?}';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send notification to all user by notification template';

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
     * @return mixed
     */

    public function handle()
    {
        $module = $this->argument('module');
        $moduleId = $this->argument('moduleId');

        // users.fcmToken,
        $user =   User::selectRaw('users.id,not.module,moduleId,not.title,not.content,not.isSend,not.id as notificationId,user_device_details.fcmToken')
                ->join('notifications as not', 'not.userId', 'users.id')
                ->join('user_device_details', 'user_device_details.userId', 'users.id')
                ->where(['users.isActive' => 1, 'users.isNotification' => 1,'not.module' => $module, 'isSend' => 0])
                ->where('user_device_details.fcmToken', '!=', null)
                ->orderBy('notificationId','desc');

            if (!empty($moduleId)) {
                 $user =  $user->where('not.moduleId', $moduleId);
            }

             $user = $user->chunkById(500, function ($users)   {

                $fcmTokens = $users->unique('fcmToken')->pluck('fcmToken')->all();
                $notificationId = $users->pluck('notificationId')->toArray();

                 Notification::whereIn('id', $notificationId)->update(['isSend' => 1]);

                    $notificationDetail = [
                        'deviceTokens' => $fcmTokens,
                        'title' => isset($users[0]->title) ? strip_tags($users[0]->title) : '',
                        'content' => isset($users[0]->content) ? strip_tags($users[0]->content) : ''
                    ];

                    //\Log::debug($notificationDetail);
                    //\Log::debug('sent notification Type' . $notificationType . '  ' . $gameTrip);
                     PushNotificationHelper::sendTemplateNotification($notificationDetail);
                  },'notificationId');
    }

  /*  public function handle()
    {
        $module = $this->argument('module');
        $moduleId = $this->argument('moduleId');


        $user =   User::selectRaw('users.id,users.fcmToken,not.module,moduleId,not.title,not.content,not.isSend,not.id as notificationId')
                ->join('notifications as not', 'not.userId', 'users.id')
                ->where(['users.isActive' => 1, 'not.module' => $module, 'isSend' => 0])
                ->where('users.fcmToken', '!=', null)
                ->orderBy('notificationId','desc');

            if (!empty($moduleId)) {
                 $user =  $user->where('not.moduleId', $moduleId);
            }

             $user = $user->chunkById(500, function ($users)   {
                $fcmTokens = $users->unique('fcmToken')->pluck('fcmToken')->all();
                    $notificationId = $users->pluck('notificationId')->toArray();
                     Notification::whereIn('id', $notificationId)->update(['isSend' => 1]);

                    $notificationDetail = [
                        'deviceTokens' => $fcmTokens,
                        'title' => isset($users[0]->title) ? strip_tags($users[0]->title) : '',
                        'content' => isset($users[0]->content) ? strip_tags($users[0]->content) : ''
                    ];


                     PushNotificationHelper::sendTemplateNotification($notificationDetail);
                  }, 'not.id');
    }*/

}
