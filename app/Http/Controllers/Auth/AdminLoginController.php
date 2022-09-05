<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class AdminLoginController extends LoginController
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
    protected $guard = 'admin';

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
        if (!Auth::guard('admin')->check()) {

            return view('admin.auth.login');
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
            'roleId' => config('constant.admin_id'),
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

        if (Auth::guard('admin')->check()) {
            if ($user->isActive == 0) {
                $this->guard('admin')->logout();
                return redirect('admin')->with('error', trans('auth.inactive'));
            } else {
                return redirect('common/dashboard');
            }
        } else {
            dd("You are in authenticate else");

            $this->guard('admin')->logout();
            return redirect('admin')->with('error', trans('auth.failed'));
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

        // $currentUserGuard =   \Auth::getDefaultDriver();

        // $this->guard($currentUserGuard)->logout();

        // $request->session()->invalidate();


        // if($currentUserGuard == "hr")
        // {
        //     return redirect(route('hrLogin'));
        // }

        // if($currentUserGuard == "admin")
        // {
        //     return redirect(route('adminLogin'));
        // }

        // if($currentUserGuard == "marketingAdmin")
        // {
        //     return redirect(route('maLogin'));
        // }

        $this->guard('admin')->logout();

        // $request->session()->invalidate();

         return $this->loggedOut($request) ?: redirect('/admin/login');
    }


     /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard('admin');
    }
}
