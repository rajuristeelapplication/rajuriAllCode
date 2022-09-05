<?php

namespace App\Helpers;
use Illuminate\Support\Facades\Storage;
use App\Models\Notification;
use App\Models\User;
use Carbon\Carbon;
use App\Models\Dealer;

class NotificationHelper
{

     /**
     * Knowledge Status Notification
     *
     * @param $knowledge Request

     * @return boolean
     */


    public static function knowledgeNotification($knowledge,$isFront = 0)
    {
        $createdOn = Carbon::now()->format('d M Y | g:i A');
        $pathProfile = config('constant.baseUrlS3') . config('constant.profile_image');

        if($isFront == 0)
        {
            $adminId =  \Auth::user()->id;
            $adminName =  \Auth::user()->fullName;
            $adminImage =  \Auth::user()->profileImage;

            $userId = $knowledge->userId;
            $senderId = $adminId;
            $module = 'Knowledge';
            $moduleId = $knowledge->id;
            $title = trans('messages.notification.knowledge_status_title_admin', ['name' => $adminName,'status' => $knowledge->kStatus ]);
            $content = trans('messages.notification.knowledge_status_content_admin', ['vehicleNumber' => $knowledge->vehicleNumber ]);

            // $media =  url('storage/images/profile/').$adminImage;

            $media =  $pathProfile.'/'.$adminImage;

            $isAdmin = 1;
        }
       else{

            $adminRoleId = config('constant.admin_id');

            $userDetail = User::where('id',$knowledge->userId)->first();

            $adminDetails = User::where('roleId',$adminRoleId)->first();

            $userId = $adminDetails->id ?? '';
            $senderId = $knowledge->userId;
            $module = 'Knowledge';
            $moduleId = $knowledge->id;
            $title = trans('messages.notification.knowledge_status_title', ['name' => $userDetail->fullName]);
            $content = trans('messages.notification.knowledge_status_content', ['name' => $userDetail->fullName]);
            $media =  $pathProfile.'/'.$userDetail->profileImage;
            $isAdmin = 0;
        }


        Notification::notificationInsert($userId,$senderId,$module,$moduleId,$title,$content,$createdOn,$media,$isAdmin);
    }

    public static function complainNotification($complaint,$isFront = 0)
    {
        $createdOn = Carbon::now()->format('d M Y | g:i A');

        $pathProfile = config('constant.baseUrlS3') . config('constant.profile_image');


        if($isFront == 0)
        {
            $adminId =  \Auth::user()->id;
            $adminName =  \Auth::user()->fullName;
            $adminImage =  \Auth::user()->profileImage;

            $userId = $complaint->userId;
            $senderId = $adminId;
            $module = 'Complain';
            $moduleId = $complaint->id;
            $title = trans('messages.notification.complain_status_title_admin', ['name' => $adminName,'status' => $complaint->cStatus ]);
            $content = '';

            $media =  $pathProfile.'/'.$adminImage;

            $isAdmin = 1;
        }
        else{

            $adminRoleId = config('constant.admin_id');

            $userDetail = User::where('id',$complaint->userId)->first();

            $adminDetails = User::where('roleId',$adminRoleId)->first();

            $userId = $adminDetails->id ?? '';
            $senderId = $complaint->userId;
            $module = 'Complain';
            $moduleId = $complaint->id;
            $title = trans('messages.notification.complain_status_title', ['name' => $userDetail->fullName,'cname' => $userDetail->cType]);
            $content = trans('messages.notification.complain_status_content', ['name' => $userDetail->fullName,'cname' => $userDetail->cType]);
            $media =  $pathProfile.'/'.$userDetail->profileImage;
            $isAdmin = 0;
        }

        Notification::notificationInsert($userId,$senderId,$module,$moduleId,$title,$content,$createdOn,$media,$isAdmin);
    }

  /**
     * Reimbursement Status Notification
     *
     * @param $reimbursement Request

     * @return boolean
     */

    public static function reimbursementNotification($reimbursement,$isFront = 0)
    {
        $createdOn = Carbon::now()->format('d M Y | g:i A');

        $pathProfile = config('constant.baseUrlS3') . config('constant.profile_image');


        if($isFront == 0)
        {
            $adminId =  \Auth::user()->id;
            $adminName =  \Auth::user()->fullName;
            $adminImage =  \Auth::user()->profileImage;

            $userId = $reimbursement->userId;
            $senderId = $adminId;
            $module = 'Reimbursement';
            $moduleId = $reimbursement->id;
            $title = trans('messages.notification.reimbursement_status_title_admin', ['name' => $adminName,'status' => $reimbursement->rStatus ]);
            $content = '';

            $media =  $pathProfile.'/'.$adminImage;

            $isAdmin = 1;
        }
        else{

            $adminRoleId = config('constant.admin_id');

            $userDetail = User::where('id',$reimbursement->userId)->first();

            $adminDetails = User::where('roleId',$adminRoleId)->first();

            $userId = $adminDetails->id ?? '';
            $senderId = $reimbursement->userId;
            $module = 'Reimbursement';
            $moduleId = $reimbursement->id;
            $title = trans('messages.notification.reimbursement_status_title', ['name' => $userDetail->fullName]);
            $content = trans('messages.notification.reimbursement_status_content', ['name' => $userDetail->fullName]);
            $media =  $pathProfile.'/'.$userDetail->profileImage;
            $isAdmin = 0;
        }


        Notification::notificationInsert($userId,$senderId,$module,$moduleId,$title,$content,$createdOn,$media,$isAdmin);
    }




        /**
     * Knowledge Status Notification
     *
     * @param $knowledge Request

     * @return boolean
     */


    public static function leaveNotification($leave,$isFront = 0)
    {

        $createdOn = Carbon::now()->format('d M Y | g:i A');
        $pathProfile = config('constant.baseUrlS3') . config('constant.profile_image');

        if($isFront == 0)
        {
            $adminId =  \Auth::user()->id;
            $adminName =  \Auth::user()->fullName;
            $adminImage =  \Auth::user()->profileImage;

            $userId = $leave->userId;
            $senderId = $adminId;
            $module = 'Leave';
            $moduleId = $leave->id;
            $title = trans('messages.notification.leave_status_title_admin', ['name' => $adminName,'status' => $leave->lRStatus ]);
            $content = 'Leave Application';

            $media =  $pathProfile.'/'.$adminImage;

            $isAdmin = 1;
        }

       else{
            $adminRoleId = config('constant.admin_id');

            $userDetail = User::where('id',$leave->userId)->first();

            $adminDetails = User::where('roleId',$adminRoleId)->first();

            $userId = $adminDetails->id ?? '';
            $senderId = $userDetail->id;
            $module = 'Leave';
            $moduleId = $leave->id;
            $title = trans('messages.notification.leave_status_title', ['name' => $userDetail->fullName ]);
            $content = 'Leave Application';
            $media =  $pathProfile.'/'.$userDetail->profileImage;

            $isAdmin = 0;
        }

        Notification::notificationInsert($userId,$senderId,$module,$moduleId,$title,$content,$createdOn,$media,$isAdmin);
    }



    public static function orderPlace($merchandise,$isFront = 0)
    {
        $dealerInfo = Dealer::where(['id' => $merchandise->rjDealerId])->first();
        $ftype = $dealerInfo->fType ?? '';
        $name = $dealerInfo->name ?? '';
        $pathProfile = config('constant.baseUrlS3') . config('constant.profile_image');

        if($isFront == 0)
        {
            $adminId =  \Auth::user()->id;
            $adminName =  \Auth::user()->fullName;
            $adminImage =  \Auth::user()->profileImage;

            $userId = $merchandise->userId;
            $senderId = $adminId;
            $module = 'Order';
            $moduleId = $merchandise->id;
            $title = trans('messages.notification.order_status_title_admin', ['status' => $merchandise->mStatus ]);
            $content = "{$ftype} : {$name}";

            $media =  $pathProfile.'/'.$adminImage;

            $isAdmin = 1;
        }
        else{
            $adminRoleId = config('constant.admin_id');

            $userDetail = User::where('id',$merchandise->userId)->first();

            $adminDetails = User::where('roleId',$adminRoleId)->first();

            $userId = $adminDetails->id ?? '';
            $senderId = $userDetail->id;
            $module = 'Order';
            $moduleId = $merchandise->id;
            $title = trans('messages.notification.order_status_title', ['name' => $userDetail->fullName ]);
            $content = trans('messages.notification.order_status_content', ['name' => $userDetail->fullName ]);
            $media =  $pathProfile.'/'.$userDetail->profileImage;

            $isAdmin = 0;
        }
        $item = json_decode($merchandise->itemListShow);
        $createdOn = implode(",",$item) ?? '';

        Notification::notificationInsert($userId,$senderId,$module,$moduleId,$title,$content,$createdOn,$media,$isAdmin);
    }


    public static function followUpNotification($followUp,$isFront = 0)
    {
        $pathProfile = config('constant.baseUrlS3') . config('constant.profile_image');
        $createdOn = Carbon::now()->format('d M Y | g:i A');

        if($isFront == 0)
        {
            $adminId =  User::where('roleId',config('constant.admin_id'))->first();
            $adminName =  $adminId->fullName;
            $adminImage =  $adminId->profileImage;

            $userId = $followUp->userId;
            $senderId = $adminId->id;
            $module = 'Follow Up';
            $moduleId = $followUp->id;
            $title = "Follow Up Reminder";
            $content = $followUp->fPurpose ?? '';

            $media =  $pathProfile.'/'.$adminImage;
            $isAdmin = 1;
        }

        Notification::notificationInsert($userId,$senderId,$module,$moduleId,$title,$content,$createdOn,$media,$isAdmin);
    }

}
