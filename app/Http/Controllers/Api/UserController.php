<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\InOut;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Helpers\FirestoreHelper;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ChangePassword;
use App\Http\Requests\Api\ProfileUpdateValidation;


class UserController extends Controller
{
    /**
     * Get Current Login User details.
     *
     * @return json
     */
    public function getUserDetails(Request $request)
    {
        $user = \Auth::user();

        $where[] = ['users.id', $user->id];
        $userDetail = User::getUser($where);

        $unReadNotificationCount = Notification::where('userId',$userDetail->id)->where('isRead',0)->first();

        $status = InOut::checkStatus($user->id);

        return $this->toJson([
            'userInOutStatus' => $status['status'],
            'hasNotification' => !empty($unReadNotificationCount) ? 1 : 0,
            'userDetail' => $userDetail,
        ]);
    }

    /**
     * User Profile Update
     *
     * @param ProfileUpdateValidation $request
     *
     * @return json
     */

    public function updateProfile(ProfileUpdateValidation $request)
    {
        $user = \Auth::user();
        $user->fill($request->all());
        $user->fullName = $request->firstName . ' ' . $request->lastName;
        $user->countryCodeMobileNumber = $user->countryCode . $request->mobileNumber;

        if ($user->save()) {
            $where[] = ['users.id', $user->id];
            $userDetail = User::getUser($where);

            try {
                FirestoreHelper::firestoreUserCreate($userDetail);
               } catch (\Exception $e) {
                   return $this->toJson([], $e->getMessage(), 0);
               }

            return $this->toJson(['userDetail' => $userDetail], trans('api.profile.update'));
        }

        return $this->toJson([], trans('api.profile.error'), 0);
    }

    /**
     * Notification On Off
     *
     * @param tinyInt notificationOnOff (0,1)
     *
     * @return json
     */

    public function notificationOnOff(Request $request)
    {
        $this->validate($request, [
            'isNotification' => 'required|in:0,1',
        ]);

        $user = \Auth::user();
        $user->isNotification = $request->isNotification;

        if ($user->save()) {
            $where[] = ['users.id', $user->id];
            $userDetail = User::getUser($where);
            return $this->toJson(['userDetail' => $userDetail], trans('api.notification.status',['status' => ($userDetail->isNotification == 1 ) ? 'On' : 'Off'  ]));
        }

        return $this->toJson([], trans('api.profile.error'), 0);
    }

    /**
     * Change Password
     *
     * @param ChangePassword $request
     *
     * @return  Json
     *
     */
    public function changePassword(ChangePassword $request)
    {
        $user = \Auth::user();

        if (empty($user)) {
            return $this->toJson(null, trans('api.auth.user_not_found'), 0);
        }

        if (Hash::check($request->oldPassword, $user->password)) {
            $user->password = bcrypt($request->newPassword);
            $user->save();
            return $this->toJson(null, trans('api.auth.change_password'), 1);
        }
        return $this->toJson(null, trans('api.auth.invalid_current_password'), 0);
    }

    /**
     * get Users List
     *
     * @param  string $id
     *
     * @return json
     */

    public function getUsersList(Request $request)
    {
        $this->validate($request, [
            'id' => 'required',
        ]);

        $user = User::where(['id' => $request->id ])->first();

        if (empty($user)) {
            return $this->toJson(null, trans('api.auth.user_not_found'), 0);
        }

        if($user->roleId == config('constant.admin_id') || $user->roleId == config('constant.hr_id'))
        {
            $where = [config('constant.sales_executive_id'),config('constant.marketing_executive_id')];

        }else if($user->roleId == config('constant.ma_id'))
        {
            $where = [config('constant.marketing_executive_id')];
        }
        else{
            // mobile Devive

            $where = [config('constant.hr_id'),config('constant.admin_id')];

            if($user->roleId == config('constant.marketing_executive_id')){
                $where = [config('constant.hr_id'),config('constant.ma_id'),config('constant.admin_id')];
            }

            // $where = [config('constant.admin_id')];

        }

        // $where = [config('constant.sales_executive_id'),config('constant.marketing_executive_id')];

        // if($user->roleId == config('constant.ma_id')){
        //     $where = [config('constant.marketing_executive_id')];
        // }


        $pathProfile = config('constant.baseUrlS3') . config('constant.profile_image');
        $users = User::selectRaw('id,firstName,lastName,fullName,mobileNumber,email,IF(ISNULL(profileImage) or profileImage = "", "", CONCAT("'.$pathProfile.'","/",profileImage)) as profileImage')
        ->whereIn('roleId',$where)->get();
        return $this->toJson(['users' => $users],'', 1);
    }

    /**
     * get Chat  Users Details
     *
     * @param  string $id
     *
     * @return json
     */

    public function chatUser(Request $request)
    {
        $pathProfile = config('constant.baseUrlS3') . config('constant.profile_image');
        $users = User::selectRaw('id,firstName,lastName,fullName as name,mobileNumber,email,IF(ISNULL(profileImage) or profileImage = "", "", CONCAT("'.$pathProfile.'","/",profileImage)) as profilePicture')
                        ->where('id',$request->id)->first();
        if($users)
        {
            return $this->toJson(['users' => $users],'', 1);
        }
    }
}
