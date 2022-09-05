<!-- BEGIN: Top Bar -->
<div class="top-bar">
    <!-- BEGIN: Breadcrumb -->
    <div class="-intro-x breadcrumb me-auto d-none d-sm-flex">
        <span class="">Dashboard</span>
        <i class="feather-chevron-right" class="breadcrumb__icon"></i>
        <span class="breadcrumb--active">Users</span>
    </div>
    <!-- END: Breadcrumb -->
    <!-- BEGIN: Notifications -->
    <div class="intro-x dropdown me-auto me-sm-4">
        <div class="dropdown-toggle notification notification--bullet cursor-pointer" data-bs-toggle="dropdown"
            id="dropdownNotification"> <i class="feather-bell notification__icon dark:text-gray-300"></i> </div>
        <div class="notification-content pt-2 dropdown-box dropdown-menu dropdown-menu-right"
            data-dropdown-in="slideInUp" data-dropdown-out="slideInDown" aria-labelledby="dropdownNotification">
            <div class="notification-content__box dropdown-box__content box">
                <div class="notification-content__title">Notifications</div>
                <div class="cursor-pointer position-relative d-flex align-items-center ">
                    <div class="w-12 h-12 flex-none image-fit me-1">
                        <img alt="{{ config('app.name') }} Admin panel" class="rounded-circle"
                            src="{{ url('images/profile-4.jpg') }}">
                        <div class="w-3 h-3 bg-theme-9 absolute right-0 bottom-0 rounded-circle border-2 border-white">
                        </div>
                    </div>
                    <div class="ms-2 overflow-hidden">
                        <div class="d-flex align-items-center">
                            <a href="javascript:;" class="font-medium truncate me-5">John Travolta</a>
                            <div class="text-xs text-gray-500 ms-auto text-nowrap">05:09 AM</div>
                        </div>
                        <div class="w-100 truncate text-gray-600">Lorem Ipsum is simply dummy text of the printing and
                            typesetting industry. Lorem Ipsum has been the industry&#039;s standard dummy text ever
                            since the 1500</div>
                    </div>
                </div>
                <div class="cursor-pointer relative d-flex align-items-center mt-3">
                    <div class="w-12 h-12 flex-none image-fit me-1">
                        <img alt="{{ config('app.name') }} Admin panel" class="rounded-circle"
                            src="{{ url('images/profile-11.jpg') }}">
                        <div class="w-3 h-3 bg-theme-9 absolute right-0 bottom-0 rounded-circle border-2 border-white">
                        </div>
                    </div>
                    <div class="ms-2 overflow-hidden">
                        <div class="d-flex align-items-center">
                            <a href="javascript:;" class="font-medium truncate mr-5">Tom Cruise</a>
                            <div class="text-xs text-gray-500 ms-auto text-nowrap">03:20 PM</div>
                        </div>
                        <div class="w-100 truncate text-gray-600">Lorem Ipsum is simply dummy text of the printing and
                            typesetting industry. Lorem Ipsum has been the industry&#039;s standard dummy text ever
                            since the 1500</div>
                    </div>
                </div>
                <div class="cursor-pointer relative d-flex align-items-center mt-3">
                    <div class="w-12 h-12 flex-none image-fit me-1">
                        <img alt="{{ config('app.name') }} Admin panel" class="rounded-circle"
                            src="{{ url('images/profile-6.jpg') }}">
                        <div class="w-3 h-3 bg-theme-9 absolute right-0 bottom-0 rounded-circle border-2 border-white">
                        </div>
                    </div>
                    <div class="ms-2 overflow-hidden">
                        <div class="d-flex align-items-center">
                            <a href="javascript:;" class="font-medium truncate me-5">John Travolta</a>
                            <div class="text-xs text-gray-500 ms-auto text-nowrap">06:05 AM</div>
                        </div>
                        <div class="w-100 truncate text-gray-600">Contrary to popular belief, Lorem Ipsum is not simply
                            random text. It has roots in a piece of classical Latin literature from 45 BC, making it
                            over 20</div>
                    </div>
                </div>
                <div class="pt-4 px-4 -ml-5 -mr-5 mt-5 border-t">
                    <a href="#!" class="text-theme-color">View All</a>
                </div>
            </div>

        </div>
    </div>
    <!-- END: Notifications -->
    <!-- BEGIN: Account Menu -->
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
</div>
<!-- END: Top Bar -->
