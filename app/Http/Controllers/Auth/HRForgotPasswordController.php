<?php

namespace App\Http\Controllers\Auth;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use App\Mail\AdminForgotPassword;
use App\Http\Controllers\Controller;

class HRForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Forgot Password Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */
    use SendsPasswordResetEmails;

    /**
     * Display login form.
     *
     * @return void
     */
    public function index()
    {
        return view('hr.auth.forgot_password');
    }
    /**
     * Get the broker to be used during password reset.
     *
     * @return \Illuminate\Contracts\Auth\PasswordBroker
     */
    public function broker()
    {
        return Password::broker('hr');
    }

    /**
     * Check email address is admin user
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function checkUserIsAdmin(Request $request)
    {

        $this->validateEmail($request);

        $user = User::where(['email' =>  $request->email,'roleId' => config('constant.hr_id')])->first();

        if (!empty($user)) {
            \DB::table('password_resets')->insert([
                'email' => $request->email,
                'token' => Str::random(60),
                'created_at' => Carbon::now()
            ]);

            session()->put('passwordBroker', 'hr');
            $result = Mail::to($user->email)->send(new AdminForgotPassword($user));

            return redirect()->back()->with('success', trans('auth.passwordlinksend'));
            //return $this->sendResetLinkEmail($request);
        }
        return redirect()->back()->with('error', trans('auth.mailnotexist'));
    }

    /**
     * Get the response for a successful password reset link.
     *
     * @param  string  $response
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    protected function sendResetLinkResponse(Request $request, $response)
    {
        return back()->with('success', trans($response));
    }

    /**
     * Get the response for a failed password reset link.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $response
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    protected function sendResetLinkFailedResponse(Request $request, $response)
    {
        return back()
            ->withInput($request->only('email'))
            ->with('error', trans($response));
    }
}
