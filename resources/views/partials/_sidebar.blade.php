<aside class="app-sidebar sticky" id="sidebar">

    <!-- Start::main-sidebar-header -->
    <div class="main-sidebar-header">
        <a href="index.html" class="header-logo">
            <img src="{{ asset('assets/images/logo.png') }}" alt="logo" class="desktop-logo">
            <img src="{{ asset('assets/images/favicon.png') }}" alt="logo" class="toggle-logo">
            <img src="{{ asset('assets/images/logo.png') }}" alt="logo" class="desktop-dark">
            <img src="{{ asset('assets/images/favicon.png') }}" alt="logo" class="toggle-dark">
            <img src="{{ asset('assets/images/logo.png') }}" alt="logo" class="desktop-white">
            <img src="{{ asset('assets/images/favicon.png') }}" alt="logo" class="toggle-white">
        </a>
    </div>
    <!-- End::main-sidebar-header -->

    <!-- Start::main-sidebar -->
    <div class="main-sidebar" id="sidebar-scroll">

        <div class="main-sidebar" id="sidebar-scroll">

            <!-- Start::nav -->
            <nav class="main-menu-container nav nav-pills flex-column sub-open">
                <div class="slide-left" id="slide-left">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24"
                        viewBox="0 0 24 24">
                        <path d="M13.293 6.293 7.586 12l5.707 5.707 1.414-1.414L10.414 12l4.293-4.293z"></path>
                    </svg>
                </div>
                <ul class="main-menu">
                    <!-- Start::nav -->
                    <nav class="main-menu-container nav nav-pills flex-column sub-open">
                        <div class="slide-left" id="slide-left">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24"
                                viewBox="0 0 24 24">
                                <path d="M13.293 6.293 7.586 12l5.707 5.707 1.414-1.414L10.414 12l4.293-4.293z"></path>
                            </svg>
                        </div>
                        <ul class="main-menu">
                            @forelse (App\Services\Navigation::adminNavigation() as $key => $navigation)
                                @if (!isset($navigation->subMenu))
                                    <li class="slide  {{ Route::currentRouteNamed($navigation->route) ? 'active' : '' }}"
                                        style="display: {{ $navigation->hasPermission ? 'block' : 'none' }}">
                                        <a href="{{ !empty($navigation->route) ? route($navigation->route) : '' }}"
                                            class="side-menu__item">
                                            <i class="{{ $navigation->icon }} side-menu__icon"></i>
                                            <span class="side-menu__label">{{ $navigation->name }}</span>
                                        </a>
                                    </li>
                                @else
                                    <li class="slide has-sub"
                                        style="display: {{ $navigation->hasPermission ? 'block' : 'none' }}">
                                        <a href="javascript:void(0);" class="side-menu__item">
                                            <i class="{{ $navigation->icon }} side-menu__icon"></i>
                                            <span class="side-menu__label">{{ $navigation->name }}</span>
                                            <i class="fe fe-chevron-right side-menu__angle"></i>
                                        </a>
                                        <ul class="slide-menu child1">
                                            @forelse ($navigation->subMenu as $sub)
                                                <li class="slide" style="display: {{ $sub->hasPermission ? 'block' : 'none' }}">
                                                    <a
                                                        href="{{ !empty($sub->route) ? route($sub->route) : '' }}" class="side-menu__item">{{ $sub->name }}</a>
                                                </li>
                                            @empty
                                            @endforelse
                                        </ul>
                                    </li>
                                @endif
                            @empty
                            @endforelse

                            <!-- End::slide -->
                        </ul>
                        <div class="slide-right" id="slide-right"><svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191"
                                width="24" height="24" viewBox="0 0 24 24">
                                <path d="M10.707 17.707 16.414 12l-5.707-5.707-1.414 1.414L13.586 12l-4.293 4.293z">
                                </path>
                            </svg>
                        </div>
                    </nav>
                    <!-- End::nav -->

                </ul>
                <div class="slide-right" id="slide-right"><svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191"
                        width="24" height="24" viewBox="0 0 24 24">
                        <path d="M10.707 17.707 16.414 12l-5.707-5.707-1.414 1.414L13.586 12l-4.293 4.293z"></path>
                    </svg></div>
            </nav>
            <!-- End::nav -->

        </div>

    </div>
    <!-- End::main-sidebar -->



</aside>
