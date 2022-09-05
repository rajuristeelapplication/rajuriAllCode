<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\UserDeviceDetails;
use App\Helpers\UtilityHelper;
use App\Helpers\FirestoreHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Login;
use App\Http\Requests\Api\Register;
use App\Http\Requests\Api\Reset;
use App\Http\Requests\Api\Deviceinfo;
use App\Http\Requests\Api\ForgotPassword;
use App\Http\Requests\Api\VerifyOTP;
use App\Jobs\SendRegisterUserOTPJob;
use App\Helpers\CurlCallHelper;

use function Ramsey\Uuid\v1;

class AuthController extends Controller
{
     /**
     * User able to register using this API
     *
     * @param  Register $request
     * @return json
     */

    public function register(Register $request)
    {
        $getRoleId = User::getRoleIdType($request->roleId);

        $user = new User();
        $user->fill($request->all());
        $user->roleId = $getRoleId;
        $user->fullName = $request->firstName . ' ' . $request->lastName;
        $user->countryCodeMobileNumber = $request->countryCode.$request->mobileNumber;
        $user->password = bcrypt(12345678);
        $user->userRandomId = UtilityHelper::generateUniqueCode('users', 'userRandomId');

        if($user->save())
        {
            try {
                $where[] = ['users.id', $user->id];
                $userDetail = User::getUser($where);
                FirestoreHelper::firestoreUserCreate($userDetail);
               } catch (\Exception $e) {
                   return $this->toJson([], $e->getMessage(), 0);
               }
            return $this->toJson(['title' => trans('api.register.title'),], trans('api.register.success'));
        }

        return $this->toJson([], trans('api.register.error'), 0);
    }

    /**
     * User able to login and resend using this API
     *
     * @param  Login $request
     * @return json
     */
    public function login(Login $request)
    {

        $checkEmailOrMobileNumber = User::checkEmailOrMobileNumber($request->userName);

        $userDetail = User::getUser($checkEmailOrMobileNumber);



        if (!empty($userDetail) && Hash::check($request->password, $userDetail->password)) {


            if ($userDetail->userStatus == 'Pending') {
                return $this->toJson(null, trans('api.login.user_status'), 0);
            }

            if ($userDetail->isActive == 0) {
                return $this->toJson(null, trans('api.login.inactive'), 0);
            }

            $user = Auth::loginUsingId($userDetail->id);

            $tokenResult = $user->createToken('Rajori-Steel')->accessToken;

            $where[] = ['users.id', $userDetail->id];
            $userDetail = User::getUser($where);

            return $this->toJson([
                'userDetail' => $userDetail,
                'accessToken' => $tokenResult,
            ], trans('api.login.success'), 1);

        }


        return $this->toJson(null, trans('api.login.invalid'), 0);
    }

     /**
     * Forgot Password api.
     *
     * @param ForgotPassword $request
     *
     * @return json
     */
    public function forgotPassword(ForgotPassword $request)
    {
        $checkEmailOrMobileNumber = User::checkEmailOrMobileNumber($request->userName);
        $userDetail = User::getUser($checkEmailOrMobileNumber);

        if (!empty($userDetail)) {

            if ($userDetail->userStatus == 'Pending') {
                return $this->toJson(null, trans('api.login.user_status'), 0);
            }

            if ($userDetail->isActive == 0) {
                return $this->toJson(null, trans('api.login.inactive'), 0);
            }

            $emailOrMobile = $checkEmailOrMobileNumber[0][0];

             $otp =  $this->sendOtp($userDetail,$emailOrMobile);

            // Send OTP in sms and email
            return $this->toJson([], trans('api.otp_verify.sent_otp', ['type' => $emailOrMobile == "email" ? "email" : "mobile number"]), 1);
        }
        return $this->toJson(null, trans('api.user.not_found'), 0);
    }

    /**
     * Verify OTP Api
     *
     * @param VerifyOTP $request
     *
     * @return Response Json
     *
     */

    public function verifyOtp(VerifyOTP $request)
    {
        $checkEmailOrMobileNumber = User::checkEmailOrMobileNumber($request->userName);

        $userDetail = User::getUser($checkEmailOrMobileNumber);


        if (!empty($userDetail)) {

            if ($userDetail->otp == $request->otp) {
                $userDetail->otp = null;

                if ($userDetail->save()) {
                    return $this->toJson([], trans('api.otp_verify.success'));
                }
                return $this->toJson(null, trans('api.otp_verify.error'), 0);
            }
            return $this->toJson(null, trans('api.otp_verify.invalid'), 0);
        }
        return $this->toJson(null, trans('api.user.not_found'), 0);
    }

    /**
     * Reset Password
     *
     * @param Reset $request
     *
     * @return Response Json
     *
     */
    public function resetPassword(Reset $request)
    {
        $checkEmailOrMobileNumber = User::checkEmailOrMobileNumber($request->userName);

        $userDetail = User::getUser($checkEmailOrMobileNumber);

        if (!empty($userDetail)) {
            $userDetail->password = bcrypt($request->password);
            $userDetail->save();
            return $this->toJson(null, trans('api.reset_password.success'), 1);
        }

        return $this->toJson(null, trans('api.user.not_found'), 0);
    }

     /**
     * Send OTP and set OTP to user
     *
     * @param  user $user
     * @param  string $emailOrMobile (email or mobile number)
     * @return object
     */

    public function sendOtp($user,$emailOrMobile)
    {
        // $otp = ($emailOrMobile == "email") ? (string) rand(1000,9999)  : "1111";

        $otp = (string) rand(1000,9999);

        // $otp = rand(1000,9999);
        $user->otp = $otp;
        $user->save();

        dispatch(new SendRegisterUserOTPJob($user->id));

        try{

        $mobileNo = $user->mobileNumber;
        $smsKey =  'Zj5Aj6cX';

        $url = "https://api.onex-aura.com/api/sms?key=$smsKey&to=$mobileNo&from=RAJURI&body=Your%20OTP%20For%20Login%20Rajuri%20Steels%20CRM%20application%20is%20$otp%20Rajuri";

        // $url = "https://api.onex-aura.com/api/sms?key=$smsKey&to=$mobileNo&from=RAJURI&body=Your%20OTP%20For%20Login%20Rajuri%20Steels%20CRM%20application%20is%20%7b%23$otp%23%7d%5cr%5cnRajuri";

        $response = CurlCallHelper::commonCurlCall('GET',$url,null,null);

        }catch(\Exception $exception){

        }



        return $user;
    }

    /**
     * User able to logout from app using this API
     *
     * @param  Request $request
     *
     * @return json
     */
    public function logout(Request $request)
    {
        $userToken = \Auth::user()->token();

        $userToken->revoke();

        return $this->toJson(null, trans('api.logout.success'));
    }

    /**
     * update Device Info
     *
     * @param  Deviceinfo $request
     *
     * @return json
     */
    public function updateDeviceInfo(Deviceinfo $request)
    {
        $user = \Auth::guard('api')->user();
        $userId = !empty($user) ? $user->id : 0;

        $userDeviceDetails = UserDeviceDetails::where('deviceToken', $request->deviceToken)->first();
        if (empty($userDeviceDetails)) {
            $userDeviceDetails = new UserDeviceDetails();
        }
        $userDeviceDetails->userId = $userId;
        $userDeviceDetails->deviceType = $request->deviceType;
        $userDeviceDetails->deviceToken = $request->deviceToken;
        $userDeviceDetails->fcmToken = $request->fcmToken;

        if ($userDeviceDetails->save()) {
            return $this->toJson([
                            'userDeviceDetails' => $userDeviceDetails,
                            'IOSAppVersion' => config('constant.IOSAppVersion'),
                            'AndroidAppVersion' => config('constant.AndroidAppVersion'),
                            'chatUrl' => 'http://14.99.147.156:8888/rajuri-steel/public/chat',
                            'trackTimer' => config('constant.trackTimer'),
                            'gpsRadius' => config('constant.gpsRadius'),
                ], trans('api.userDeviceUpdated.success'), 1);
        }

        return $this->toJson(null, trans('api.userDeviceUpdated.error'), 0);
    }





}
