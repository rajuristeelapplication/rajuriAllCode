@php

$currentUserGuard =   \Auth::getDefaultDriver();
$pathProfile = config('constant.baseUrlS3') . config('constant.profile_image');
$admin = \Auth::guard($currentUserGuard)->user();
$adminImage = !empty($admin->profileImage) ? $pathProfile .'/'.$admin->profileImage : $pathProfile .'/default.png';

// $admin = \Auth::guard('admin')->user();
// $adminImage = !empty($admin->profileImage) ? url('storage/images/profile/'. $admin->profileImage) : url('storage/images/profile/default.png');
@endphp
<!-- BEGIN: Top Bar -->
<div class="top-bar">
    <!-- BEGIN: Breadcrumb -->
    <!-- ============================================================== -->
    <button type="button" class="navbar-toggle collapsed side-bar-button d-none d-md-block hide-menu"  data-toggle="collapse"
        data-target="#navbar" aria-expanded="false" aria-controls="navbar">
        <i class="feather-align-justify"></i>
    </button>
    @yield('navigation')
    <!-- END: Breadcrumb -->

    <!-- BEGIN: Notification -->
    <a href="{{route('notifications.index')}}" class="notification-bell">
        <i class="feather-bell"></i>
        {{--  @if($totalNotification != 0) counter  notification-counter--}}
          <span class="notificationCounterClass"></span>
        {{--  @endif  --}}
    </a>
<!-- END: Notification -->


    <!-- BEGIN: Account Menu -->
    <div class="intro-x dropdown w-8 h-8">
        <div class="dropdown-toggle w-8 h-8 rounded-circle overflow-hidden shadow-lg image-fit cursor-pointer"
            data-bs-toggle="dropdown">

            <img alt="{{ config('app.name') }} Admin panel" class="image1" src="{{ $adminImage ?? '' }}">
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
                        <i class="feather-lock w-4 h-4 me-2"></i> Change Password  </a>
                </div>
                <div class="p-2 border-t border-theme-color">

                    @php

                    $currentUserGuard =   \Auth::getDefaultDriver();

                      if($currentUserGuard == "hr")
                        {
                           $routeName = 'hrLogout';
                        }

                        if($currentUserGuard == "admin")
                        {
                            $routeName = 'AdminLogout';
                        }

                        if($currentUserGuard == "marketingAdmin")
                        {
                            $routeName = 'maLogout';
                        }

                @endphp

                    <a href="{{ route($routeName) }}"
                        class="d-flex align-items-center block p-2 transition duration-300 ease-in-out hover-theme-bg rounded-3">
                        <i class="feather-toggle-right w-4 h-4 me-2"></i> Logout </a>
                </div>
            </div>
        </div>
    </div>
    <!-- END: Account Menu -->
</div>
<!-- END: Top Bar -->
