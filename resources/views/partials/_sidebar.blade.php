<div class="startbar d-print-none">
    <!--start brand-->
    <div class="brand">
        <a href="#" class="logo">
            <span>
                <img src="{{ asset('assets/images/logo-sm.png') }}" alt="logo-small" class="logo-sm">
            </span>
            <span class="">
                <img src="{{ asset('assets/images/logo-light.png') }}" alt="logo-large" class="logo-lg logo-light">
                <img src="{{ asset('assets/images/logo-dark.png') }}" alt="logo-large" class="logo-lg logo-dark">
            </span>
        </a>
    </div>
    <!--end brand-->
    <!--start startbar-menu-->
    <div class="startbar-menu">
        <div class="startbar-collapse" id="startbarCollapse" data-simplebar>
            <div class="d-flex align-items-start flex-column w-100">
                <!-- Navigation -->
                <ul class="navbar-nav mb-auto w-100">
                    @forelse (App\Services\Navigation::adminNavigation() as $key => $navigation)
                        @if (!isset($navigation->subMenu))
                            <li class="nav-item  {{ Route::currentRouteNamed($navigation->route) ? 'active' : '' }}"
                                style="display: {{ $navigation->hasPermission ? 'block' : 'none' }}">
                                <a class="nav-link"
                                    href="{{ !empty($navigation->route) ? route($navigation->route) : '' }}">
                                    <i class="{{ $navigation->icon }} menu-icon"></i>
                                    <span> {{ $navigation->name }}</span>
                                </a>
                            </li>
                        @else
                            <li class="nav-item" style="display: {{ $navigation->hasPermission ? 'block' : 'none' }}">
                                <a class="nav-link" href="#sidebarDashboards_{{ $key }}"
                                    data-bs-toggle="collapse" role="button" aria-expanded="false"
                                    aria-controls="sidebarDashboards">
                                    <i class="{{ $navigation->icon }} menu-icon"></i>
                                    <span>{{ $navigation->name }}</span>
                                </a>
                                <div class="collapse " id="sidebarDashboards_{{ $key }}">
                                    <ul class="nav flex-column">
                                        @forelse ($navigation->subMenu as $sub)
                                            <li class="nav-item"
                                                style="display: {{ $sub->hasPermission ? 'block' : 'none' }}">
                                                <a class="nav-link"
                                                    href="{{ !empty($sub->route) ? route($sub->route) : '' }}">{{ $sub->name }}</a>
                                            </li><!--end nav-item-->
                                        @empty
                                        @endforelse
                                    </ul><!--end nav-->
                                </div><!--end startbarDashboards-->
                            </li><!--end nav-item-->
                        @endif
                    @empty
                    @endforelse
                </ul><!--end navbar-nav--->

            </div>
        </div><!--end startbar-collapse-->
    </div><!--end startbar-menu-->
</div>
