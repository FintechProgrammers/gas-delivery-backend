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


</head>

<body>
    <div class="container-xxl">
        <div class="row vh-100 d-flex justify-content-center">
            <div class="col-12 align-self-center">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-4 mx-auto">
                            <div class="card">
                                <div class="card-body p-0  auth-header-box rounded-top">
                                    <div class="text-center">
                                        <a href="#" class="logo logo-admin">
                                            <img src="{{ asset('assets/images/logo-4.PNG') }}" height="200"
                                                width="300" alt="logo" class="auth-logo">
                                        </a>
                                    </div>
                                </div>
                                <div class="card-body pt-0">
                                    @yield('content')
                                </div><!--end card-body-->
                            </div><!--end card-->
                        </div><!--end col-->
                    </div><!--end row-->
                </div><!--end card-body-->
            </div><!--end col-->
        </div><!--end row-->
    </div>

    @include('partials._js')


    @include('partials._alert_messages')

    <!-- Show Password JS -->
    <script src="{{ asset('assets/js/show-password.js') }}"></script>

    @stack('scripts')

</body>

</html>
