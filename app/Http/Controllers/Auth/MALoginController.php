<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class MALoginController extends LoginController
{
    /*
    |--------------------------------------------------------------------------
    | Admin Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating admin users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    protected $redirectTo = '/common/dashboard';

    /**
     * Set current Guard.
     *
     * @var string
     */
    protected $guard = 'marketingAdmin';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //parent::__construct();
    }

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
        if (!Auth::guard('marketingAdmin')->check()) {
            return view('ma.auth.login');
        }

        return redirect(route('AdminDashboard'));
    }

    /**
     * Get the needed authorization credentials from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        return [
            'email' => $request->email,
            'password' => $request->password,
            'roleId' => config('constant.ma_id'),
        ];
    }



    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {

        if (Auth::guard('marketingAdmin')->check()) {
            if ($user->isActive == 0) {
                $this->guard('marketingAdmin')->logout();
                return redirect('marketingAdmin')->with('error', trans('auth.inactive'));
            } else {
                return redirect('common/dashboard');
            }
        } else {
            dd("You are in authenticate else");

            $this->guard('hr')->logout();
            return redirect('hr')->with('error', trans('auth.failed'));
        }
    }

    /**
     * Send the response after the user was authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    protected function sendLoginResponse(Request $request)
    {
        $request->session()->regenerate();
        $this->clearLoginAttempts($request);

        return $this->authenticated($request, $this->guard()->user())
            ?: redirect()->intended($this->redirectPath());
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        $this->guard('marketingAdmin')->logout();
        // $request->session()->invalidate();

        return redirect(route('maLogin'));
        // return $this->loggedOut($request) ? : redirect('/hr/login');
    }


     /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard('marketingAdmin');
    }
}
