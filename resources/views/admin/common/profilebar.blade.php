<!-- Account Menu -->
    <div class="intro-x dropdown w-8 h-8">
        <div class="dropdown-toggle w-8 h-8 rounded-circle overflow-hidden shadow-lg image-fit cursor-pointer"
            data-bs-toggle="dropdown">
            <img alt="{{ config('app.name') }} Admin panel" src="{{ url('images/profile-1.jpg') }}">
        </div>
        <div class="dropdown-box dropdown-menu dropdown-menu-end w-56">
            <div class="dropdown-box__content box text-white theme-bg">
                <div class="p-3 border-b border-theme-color">
                    <div class="font-medium">{{ ucfirst($admin->firstName) . ' ' . $admin->lastName }} &nbsp;</div>

                </div>
                <div class="p-2">
                    <a href="{{ route('EditAdminProfile') }}"
                        class="d-flex align-items-center p-2 transition duration-300 ease-in-out hover-theme-bg rounded-3">
                        <i class="feather-user w-4 h-4 me-2"></i> Profile </a>
                    <a href="{{ route('EditAdminChangePassword') }}"
                        class="d-flex align-items-center p-2 transition duration-300 ease-in-out hover-theme-bg rounded-3">
                        <i class="feather-lock w-4 h-4 me-2"></i> Reset Password </a>
                </div>
                <div class="p-2 border-t border-theme-color">
                    <a href="{{ route('AdminLogout') }}"
                        class="d-flex align-items-center block p-2 transition duration-300 ease-in-out hover-theme-bg rounded-3">
                        <i class="feather-toggle-right w-4 h-4 me-2"></i> Logout </a>
                </div>
            </div>
        </div>
    </div>
<!-- END: Account Menu -->