<meta charset="utf-8">

<meta charset="UTF-8">
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="baseUrl" content="{{ url('/') }}">
<title>@yield('title') | Admin {{ config('app.name') }}</title>
<link rel="icon" type="image/png" sizes="16x16" href="{{ url('/admin-assets/images/logo/favicon.png') }}">

<link rel="preconnect" href="https://fonts.gstatic.com">
<link
    href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap"
    rel="stylesheet">

<link rel="stylesheet" href="{{ url('css/style.min.css') }}">
<link rel="stylesheet" href="{{ url('css/style.css') }}">
<!--Toaster Popup message CSS -->
<link href="{{ url('admin-assets/node_modules/toast-master/css/jquery.toast.css') }}" rel="stylesheet">
<link href="{{ url('admin-assets/icons/font-awesome/css/fontawesome-all.css') }}" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.css" media="screen">
<link href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.css" rel="stylesheet"/>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="{{ url('admin-assets/css/daterangepicker.min.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">


@yield('css')
