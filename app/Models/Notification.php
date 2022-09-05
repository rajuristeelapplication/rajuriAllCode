<?php

namespace App\Models;

use App\Helpers\UtilityHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Notification extends CustomModel
{

    use HasFactory;

    protected $fillable = [
        'userId',
        'senderId',
        'module',
        'moduleId',
        'title',
        'content',
        'media'
    ];


    public static function notificationInsert($userId,$senderId,$module,$moduleId,$title,$content,$createdOn,$media,$isAdmin = 0)
    {
        $notification  = new Notification();
        $notification->userId =  $userId;
        $notification->senderId =  $senderId;
        $notification->module =  $module;
        $notification->moduleId =  $moduleId;
        $notification->title =  $title;
        $notification->content =  $content;
        $notification->createdOn =  $createdOn;
        $notification->media =  $media;
        $notification->isAdmin =  $isAdmin;
        if($notification->save()){
            $cmd = 'cd ' . base_path() . ' && php artisan SendNotificationType:send "' . $notification->module . '" "' . $notification->moduleId . '" ';
            exec($cmd . '> /dev/null &');
      }

      return true;
    }



          /**
     * Get notifications.
     *
     * @param $userId
     * @return object
     */
    public static function getSelectQuery()
    {
        return self::selectRaw('notifications.id,notifications.title,notifications.createdAt,
                                    CONCAT(users.firstName," ",users.lastName) as userName,
                                    CONCAT(sender.firstName," ",sender.lastName) as senderName,isAdmin,notifications.module,
                                    DATE_FORMAT(notifications.createdAt, "' . config('constant.schedule_date_format') . '") as createdOn')
                               ->leftjoin('users','users.id','notifications.userId')
                               ->leftjoin('users as sender','sender.id','notifications.senderId');
    }



        // sender Id Means Current user Info

/*        public static function notificationInsert($result)
        {
            $notification = new Notification();
            $notification->userId = $result['userId'] ?? 0;
            $notification->senderId = $result['senderId'] ?? 0;
            $notification->module = $result['module'] ?? NULL;
            $notification->moduleId = $result['moduleId'] ?? 0;
            $notification->title = $result['title'] ?? NULL;
            $notification->content = $result['content'] ?? NULL;
            $notification->media = $result['media'] ?? NULL;

            if($notification->save()){
                  $cmd = 'cd ' . base_path() . ' && php artisan SendNotificationType:send "' . $notification->module . '" "' . $notification->moduleId . '" ';
                  exec($cmd . '> /dev/null &');
            }

            return true;

            // $dbQuery = "INSERT INTO notifications(userId,senderId,module,moduleId, title, content,media)
            // SELECT DISTINCT
            // " . $userId . "',
            // '" . $senderId . "',
            // '" . $module . "',
            //  '" . $moduleId . "',
            // '" . $title . "',
            // '" . $content . "',
            // '" . $title . "',
            // '" . $media . "'
            // FROM user_game_history";

            // \DB::select($dbQuery);
        }

        */
}
