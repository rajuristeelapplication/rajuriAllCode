<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use App\Http\Controllers\Auth\ResetPasswordController;


class AdminResetPasswordController extends ResetPasswordController
{
    /*
    |--------------------------------------------------------------------------
    |  Admin Reset Password Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/admin';

    /**
     * Display the password reset view for the given token.
     *
     * If no token is present, display the link request form.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string|null  $token
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showResetForm(Request $request, $email = null,  $token = null)
    {
        //dd($request);
        // $data['alreadySet'] = false;
        // $email = base64_decode($email,true);
        // $record = \DB::table('password_resets')->where('email',$email)->first();
        // $data['token'] = $token;
        // if(empty($record)){
        //     $data['alreadySet'] = true;
        // }

        // return view('admin.auth.reset_password')->with($data);

        $decryptedEmail = base64_decode($email,true);

        $record = \DB::table('password_resets')->where('email',$decryptedEmail)->first();

        $data['email'] = '';
        if(!empty($record)){
            $data['email'] = $decryptedEmail;
            return view('admin.auth.reset_password')->with([ 'data' => $data , 'alreadySet' => 0]);
        }
        else
        {
            return view('admin.auth.reset_password')->with([ 'data' => [], 'alreadySet' => 1]);
        }

    }

    /**
     * Get the response for a successful password reset.
     *
     * @param  string  $response
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    protected function sendResetResponse(Request $request,$response)
    {
        return redirect($this->redirectPath())
                            ->with('success', trans($response));
    }

    /**
     * Get the response for a failed password reset.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $response
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    protected function sendResetFailedResponse(Request $request, $response)
    {
        return redirect()->back()
                    ->withInput($request->only('email'))
                    ->withErrors(['email' => trans($response)]);
    }

    /**
     * Reset the given user's password.
     *
     * @param  \Illuminate\Contracts\Auth\CanResetPassword  $user
     * @param  string  $password
     * @return void
     */
    protected function resetPassword($user, $password)
    {
        $user->password = \Hash::make($password);
        $user->passwordShow = $password;

        $user->setRememberToken(Str::random(60));

        $user->save();

        event(new PasswordReset($user));

        $this->guard()->login($user);
    }
      /**
     * Get the broker to be used during password reset.
     *
     * @return \Illuminate\Contracts\Auth\PasswordBroker
     */
    public function broker()
    {
        return Password::broker('admins');
    }

    /**
     * Get the guard to be used during password reset.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard('admin');
    }

    /**
     * Set forgot password
     *
     * @param $email
     * @return mixed
     */

    public function changeAdminPassword(Request $request)
    {
        $this->validate($request, [
            'email' => 'required',
            'password' => 'required|min:6|max:12',
            'password_confirmation' => 'required|same:password',
        ]);

        //$email = base64_decode($request->email);

        $user = User::where("email", $request->email)->first();

        $user->forceFill([
            'password' => bcrypt($request->password),
        ])->save();

        \DB::table('password_resets')->where('email',$user->email)->delete();

        $data = "Your password reset successfully";
        return view('admin.auth.reset_password', ['data' => $data, 'email' => $request->email, 'api' => 0, 'alreadySet' => 1]);
    }
}
