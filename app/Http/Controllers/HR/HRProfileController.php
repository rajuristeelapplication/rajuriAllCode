<?php

namespace App\Http\Controllers\HR;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;

class HRProfileController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Admin Profile Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles admin users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide it's functionality to your applications.
    |
    */

    /**
     * Validation rules for update profile
     *
     * @var array
     *
     */
    private $validationRules = [
        'firstName' => 'required|string|max:20|min:2',
        'lastName' => 'required|string|max:20|min:2',
        'email' => 'required|string|email|max:150',
    ];

    /**
     * View  admin profile
     *
     * @return view
     */
    public function showProfile()
    {
        $admin = Auth::guard('hr')->user();
        $adminImage = !empty($admin->profileImage) ? url('storage/images/profile/'. $admin->profileImage) : url('storage/images/profile/default.png');

        return view('hr/profile',
                [
                    'adminImage' => $adminImage,
                    'admin' => $admin,
                ]
        );
    }

    /**
     * Update admin profile
     *
     * @param Request $request
     *
     * @return Redirect
     */
    public function updateProfile(Request $request)
    {

        $this->validate($request, $this->validationRules);

        $admin = User::where('id', Auth::guard('hr')->user()->id)->first();
        $admin->fill($request->all());

        $profileImage = '';
        $profileImage1 = '';
        if ($request->hasFile('profileImage')) {
            $type = 'profile';
            $imageShortName = $type . '_' . time() . '_' . strtolower(\Str::random(6)) . '.' . $request->profileImage->getClientOriginalExtension();
            $imageName = 'images/' . $type . '/' . $imageShortName;
            $file = $request->file('profileImage');
            $putFile = Storage::disk('public')->put($imageName, file_get_contents($file));
            $imageName = Storage::disk('public')->url($imageName);
            $admin->profileImage = $imageShortName;

            $profileImage1 =  url('storage/images/profile/'. $imageShortName);
        }
        if ($admin->save()) {
            return $this->toJson(['admin' => $admin, 'image' => $profileImage1], trans('messages.msg.updated.success', ['module' => 'profile']), 1);
        }
        return $this->toJson([], trans('messages.msg.updated.error', ['module' => 'profile']), 0);
    }

    /**
     *
     * Delete admin Profile Photo
     *
     * @return Redirect
     */
    public function profileImageDelete()
    {
        //$admin = Auth::user();
        $admin = User::where('id', Auth::guard('hr')->user()->id)->first();

        $file = $admin->profileImage;

        if (!empty($file)) {
            $fileName = public_path('storage/images/profile/' . $admin->profileImage);
            if (file_exists($fileName)) {
                unlink($fileName);
            }
        }
        $admin->profileImage = '';
        $adminImage = url('storage/images/profile/default.png');
        if ($admin->save()) {
            return $this->toJson([
                'adminImage' => $adminImage,
                'admin' => $admin
            ], trans('messages.msg.deleted.success', ['module' => 'profile image']), 1);
        }
        return $this->toJson(['adminImage' => $adminImage, 'admin' => $admin], trans('messages.msg.deleted.error', ['module' => 'profile']), 0);
    }

    /**
     * Load change password
     *
     * @return view
     */
    public function EditAdminChangePassword()
    {
        return view('hr.change_password');
    }

    /**
     * Change admin password
     *
     * @param Request $request
     *
     * @return Redirect
     */
    public function updateAdminChangePassword(Request $request)
    {

        $this->validate($request, [
            'current_password' => 'required',
            'password' => 'required|confirmed',
        ]);

        //$admin = Auth::guard('admin')->user();
        $admin = User::where('id', Auth::guard('hr')->user()->id)->first();
        $currentPassword = $admin->password;

        // Check old password is current or not
        if (Hash::check($request->current_password, $currentPassword)) {

            $admin->password = bcrypt($request->password);
            $admin->save();
            return $this->toJson([], trans('messages.msg.wrong.success', ['module' => 'new password']), 1);
        }
        return $this->toJson([], trans('messages.msg.wrong.error', ['module' => 'current password']), 0);
    }
}
