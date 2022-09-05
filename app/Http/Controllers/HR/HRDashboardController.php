<?php

namespace App\Http\Controllers\HR;
use App\Http\Controllers\Controller;



class HRDashboardController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Admin Dashboard Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles  admin users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    /**
     * Show admin dashboard
     *
     * @return view
     */
    public function index()
    {
        return view('admin.dashboard');
    }
}
