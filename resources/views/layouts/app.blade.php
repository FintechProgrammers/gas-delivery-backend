<!DOCTYPE html>
<html lang="en" dir="ltr" data-startbar="dark" data-bs-theme="light">

<head>
    <!-- Meta Data -->
    @include('partials._meta')

    @include('partials._styles')
    @include('partials._dashboard_styles')
    @stack('styles')

</head>

<body>
    @include('partials.headers')
    @include('partials._sidebar')
    <div class="startbar-overlay d-print-none"></div>
    <div class="page-wrapper">
        <div class="page-content">
            <div class="container-fluid">
                @yield('content')
            </div>
        </div>
    </div>

    @include('partials._modal')

    @include('partials._js')

    @include('partials._dashbaord-js')

    @stack('scripts')

</body>

</html>
