<!DOCTYPE html>
<html lang="en" dir="ltr" data-nav-layout="vertical" data-vertical-style="overlay" data-theme-mode="light"
    data-header-styles="light" data-menu-styles="light" data-toggled="close">

<head>

    <!-- Meta Data -->
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ config('app.name', 'Laravel') }} - @yield('title')</title>
    <meta name="Description" content="">
    <meta name="Author" content="">
    <meta name="keywords" content="">

    @include('partials._styles')

    <!-- Main Theme Js -->
    <script src="{{ asset('assets/js/authentication-main.js') }}"></script>

</head>

<body>

    <div class="container">
        <div class="row justify-content-center align-items-center authentication authentication-basic h-100">
            <div class="col-xxl-4 col-xl-5 col-lg-5 col-md-6 col-sm-8 col-12">
                <div class="my-5 d-flex justify-content-center">
                    <a href="{{ route('login') }}">
                        <img src="{{ asset('assets/images/logo-white.webp') }}" alt="logo"
                            class="desktop-logo">
                        <img src="{{ asset('assets/images/logo.png') }}" alt="logo"
                            class="desktop-dark">
                    </a>
                </div>
                <div class="card custom-card">
                    <div class="card-body p-5">
                        @yield('content')
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('partials._js')


    @include('partials._alert_messages')

    <!-- Show Password JS -->
    <script src="{{ asset('assets/js/show-password.js') }}"></script>

    @stack('scripts')

</body>

</html>
