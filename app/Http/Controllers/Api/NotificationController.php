<?php

namespace App\Http\Controllers\Api;

use App\Models\Notification;
use Illuminate\Http\Request;
use App\Helpers\UtilityHelper;
use App\Http\Controllers\Controller;

class NotificationController extends Controller
{
    /*
     |--------------------------------------------------------------------------
     | NotificationController Controller
     |--------------------------------------------------------------------------
     |
     | This controller handles notifications Related apis & features.
    */

    /**
     * Get Notification list
     *
     * @param Request $request
     *
     * @return Response Json
    */
    public function getNotificationList(Request $request)
    {
        $followUpPagination = config('constant.followUpPagination');

        $user = \Auth::user();

        $recents = $this->recentsNotification($user)->get();

        $earlier = $this->baseQueryNotification($user)->where('notifications.isRead', 1)->paginate($followUpPagination);


        $this->baseQueryNotification($user)->where('isRead', 0)->update(['isRead' => 1]);

        return $this->toJson([
            'hasMore' => $earlier->hasMorePages(),
            'notifications' => ['recents' => $recents ,'earlier' => $earlier->items()],

        ],trans('api.notifications.found'),1);
    }

    /**
     * Base query of notifications
     *
     * @return object query
     *
    */

    private function baseQueryNotification($user)
    {
        return Notification::where('userId', $user->id)
            ->selectRaw('notifications.id,notifications.userId,notifications.module,notifications.moduleId,
            notifications.title,
            notifications.content,
            notifications.isRead,
            notifications.media as mediaPic,
            createdOn,
        '. UtilityHelper::getDateFormatNotification('notifications.createdAt', 'notificationTime') .'')
            ->orderBy('notifications.createdAt', 'desc');
    }

    public function recentsNotification($user)
    {
        return Notification::where(['notifications.userId' => $user->id,'notifications.isRead'=> 0])
            ->selectRaw('notifications.id,notifications.userId,notifications.module,notifications.moduleId,
                notifications.title,
                notifications.content,
                notifications.isRead,
                notifications.media as mediaPic,
                createdOn,
        '. UtilityHelper::getDateFormatNotification('notifications.createdAt', 'notificationTime') .'')
            ->orderBy('notifications.createdAt', 'desc');
    }


    /**
     * Remove notifications
     *
     * @param Request $request
     *
     * @return Response Json
     *
     */
    public function removeNotification(Request $request)
    {
        $this->validate($request, [
            'notificationId' => 'required'
        ]);

        $notification = Notification::where('id', $request->notificationId)->first();

        if (empty($notification))
        {
            return $this->toJson(null, trans('api.notifications.not_found'), 0);
        }

        if($notification->delete())
        {
            return $this->toJson(null, trans('api.notifications.remove_success'), 1);
        }

        return $this->toJson(null, trans('api.notifications.remove_error'), 0);
    }
}
