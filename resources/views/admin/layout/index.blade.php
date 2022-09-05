<!DOCTYPE html>
<html lang="en">

<head>
    @include('admin.common.head')


</head>

<body class="">
    <div class="d-flex flex-column flex-md-row">
        <!-- ============================================================== -->
        <!-- Main wrapper - style you can find in pages.scss -->
        <!-- ============================================================== -->


        <!-- ============================================================== -->
        <!-- Topbar header - style you can find in pages.scss -->
        <!-- ============================================================== -->

        @include('admin.common.header')

        <!-- ============================================================== -->
        <!-- End Topbar header -->
        <!-- ============================================================== -->

        <!-- ============================================================== -->
        <!-- Left Sidebar - style you can find in sidebar.scss  -->

        @include('admin.common.sidebar')

        <!-- ============================================================== -->
        <!-- End Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->

        <!-- ============================================================== -->
        <!-- Page wrapper  -->
        <!-- ============================================================== -->
        @php
             $currentUserGuard =   \Auth::getDefaultDriver();

             $admin = \Auth::guard($currentUserGuard)->user()->profileImage;
             $adminImage = url(Storage::disk('local')->url(config('constant.profiles') . $admin ?? 'default.jpg'));

            // $admin = \Auth::guard('admin')->user()->profileImage;
            // $adminImage = url(Storage::disk('local')->url(config('constant.profiles') . $admin ?? 'default.jpg'));
        @endphp
        @yield('content')

        <!-- ============================================================== -->
        <!-- End Page wrapper  -->
        <!-- ============================================================== -->

    </div>
    <!-- ============================================================== -->
    <!-- End Wrapper -->
    <!-- ============================================================== -->
    @include('admin.common.footer')
</body>

</html>
