<!-- ============================================================== -->
<!-- login head file -->
<!-- ============================================================== -->

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title') | Admin {{ config('app.name') }}</title>
<link rel="preconnect" href="https://fonts.gstatic.com">
<link rel="icon" type="image/png" sizes="16x16" href="{{ url('/admin-assets/images/logo/favicon.png') }}">
<link
    href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap"
    rel="stylesheet">
<link rel="stylesheet" href="{{ url('css/style.min.css') }}">
<link rel="stylesheet" href="{{ url('css/style.css') }}">
@yield('css')

<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
