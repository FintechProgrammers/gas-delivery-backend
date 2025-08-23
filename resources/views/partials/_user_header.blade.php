<header class="app-header">

    <!-- Start::main-header-container -->
    <div class="main-header-container container-fluid">

        <!-- Start::header-content-left -->
        <div class="header-content-left">

            <!-- Start::header-element -->
            <div class="header-element">
                <div class="horizontal-logo">
                    <a href="#" class="header-logo">
                        <img src="{{ asset('assets/images/logo.png') }}" alt="logo" class="desktop-logo">
                        <img src="{{ asset('assets/images/favicon.png') }}" alt="logo" class="toggle-logo">
                        <img src="{{ asset('assets/images/logo.png') }}" alt="logo" class="desktop-dark">
                        <img src="{{ asset('assets/images/favicon.png') }}" alt="logo" class="toggle-dark">
                        <img src="{{ asset('assets/images/logo.png') }}" alt="logo" class="desktop-white">
                        <img src="{{ asset('assets/images/favicon.png') }}" alt="logo" class="toggle-white">
                    </a>
                </div>
            </div>
            <!-- End::header-element -->

            <!-- Start::header-element -->
            <div class="header-element">
                <!-- Start::header-link -->
                <a aria-label="Hide Sidebar"
                    class="sidemenu-toggle header-link animated-arrow hor-toggle horizontal-navtoggle"
                    data-bs-toggle="sidebar" href="javascript:void(0);"><span></span></a>
                <!-- End::header-link -->
            </div>
            <!-- End::header-element -->

        </div>
        <!-- End::header-content-left -->

        <!-- Start::header-content-right -->
        <div class="header-content-right">


            <!-- Start::header-element -->
            <div class="header-element country-selector">
                <!-- Start::header-link -->
                <a href="javascript:void(0);" class="header-link" data-bs-toggle="modal" data-bs-target="#countryModal">
                    <img src="{{ asset('assets/images/flags/us_flag.jpg') }}" alt="img"
                        class="rounded-circle header-link-icon">
                    <span class="fw-semibold mb-0 lh-1">EN</span>
                </a>
            </div>
            <!-- End::header-element -->

            <x-notification-component />

            <!-- Start::header-element -->
            <div class="header-element">
                <!-- Start::header-link|dropdown-toggle -->
                <a href="javascript:void(0);" class="header-link dropdown-toggle" id="mainHeaderProfile"
                    data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                    <div class="d-flex align-items-center">
                        <div class="me-sm-2 me-0">
                            <img src="{{ auth()->user()->profile_picture }}" alt="img" width="32"
                                height="32" class="rounded-circle">
                        </div>
                        <div class="d-sm-block d-none">
                            <p class="fw-semibold mb-0 lh-1">{{ auth()->user()->name }}</p>
                            <span class="op-7 fw-normal d-block fs-11"></span>
                        </div>
                    </div>
                </a>
                <!-- End::header-link|dropdown-toggle -->
                <ul class="main-header-dropdown dropdown-menu pt-0 overflow-hidden header-profile-dropdown dropdown-menu-end"
                    aria-labelledby="mainHeaderProfile">
                    <li><a class="dropdown-item d-flex" href="{{ route('profile.edit') }}"><i
                                class="ti ti-user-circle fs-18 me-2 op-7"></i>Profile</a></li>
                    <li>
                        <a class="dropdown-item d-flex" href="{{ route('logout') }}"><i
                                class="ti ti-logout fs-18 me-2 op-7"></i>Log Out</a>
                    </li>
                </ul>
            </div>
            <!-- End::header-element -->

        </div>
        <!-- End::header-content-right -->

    </div>
    <!-- End::main-header-container -->

</header>
