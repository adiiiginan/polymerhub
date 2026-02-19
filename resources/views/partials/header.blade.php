<div id="kt_header" class="header align-items-stretch">
    <div class="container-fluid d-flex align-items-stretch justify-content-between">
        <div class="d-flex align-items-center flex-grow-1 flex-lg-grow-0 me-lg-15">
            <a href="{{ route('home') }}">
                <img alt="Logo" src="{{ asset('images/logo.png') }}" class="h-20px h-lg-30px" />
            </a>
        </div>
        <div class="d-flex align-items-stretch justify-content-between flex-lg-grow-1">
            <div class="d-flex align-items-stretch" id="kt_header_nav">
                <div class="header-menu align-items-stretch" data-kt-drawer="true" data-kt-drawer-name="header-menu"
                    data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true"
                    data-kt-drawer-width="{default:'200px', '300px': '250px'}" data-kt-drawer-direction="end"
                    data-kt-drawer-toggle="#kt_header_menu_mobile_toggle" data-kt-swapper="true"
                    data-kt-swapper-mode="prepend" data-kt-swapper-parent="{default: '#kt_body', lg: '#kt_header_nav'}">
                    <div class="menu menu-lg-rounded menu-column menu-lg-row menu-state-bg menu-title-gray-700 menu-state-title-primary menu-state-icon-primary menu-state-bullet-primary menu-arrow-gray-400 fw-bold my-5 my-lg-0 align-items-stretch"
                        id="#kt_header_menu" data-kt-menu="true">
                        <div class="menu-item me-lg-1">
                            <a class="menu-link py-3" href="{{ route('en.home') }}">
                                <span class="menu-title">Home</span>
                            </a>
                        </div>
                        <div class="menu-item me-lg-1">
                            <a class="menu-link py-3" href="{{ route('en.brochure') }}">
                                <span class="menu-title">Brochure</span>
                            </a>
                        </div>
                        <div class="menu-item me-lg-1">
                            <a class="menu-link py-3" href="{{ route('en.contact') }}">
                                <span class="menu-title">Contact</span>
                            </a>
                        </div>
                        @auth('customer')
                            <div class="menu-item me-lg-1">
                                <a class="menu-link py-3" href="{{ route('en.customer.dashboard') }}">
                                    <span class="menu-title">Dashboard</span>
                                </a>
                            </div>
                        @endauth
                    </div>
                </div>
            </div>
            <div class="d-flex align-items-stretch flex-shrink-0">
                <div class="d-flex align-items-center ms-1 ms-lg-3">
                    @auth('customer')
                        <div class="cursor-pointer symbol symbol-30px symbol-md-40px" data-kt-menu-trigger="click"
                            data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end">
                            <img src="{{ asset('backend/assets/media/avatars/blank.png') }}" alt="user" />
                        </div>
                        <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-primary fw-bold py-4 fs-6 w-275px"
                            data-kt-menu="true">
                            <div class="menu-item px-3">
                                <div class="menu-content d-flex align-items-center px-3">
                                    <div class="symbol symbol-50px me-5">
                                        <img alt="Logo" src="{{ asset('backend/assets/media/avatars/blank.png') }}" />
                                    </div>
                                    <div class="d-flex flex-column">
                                        <div class="fw-bolder d-flex align-items-center fs-5">
                                            {{ auth('customer')->user()->detail->nama }}
                                        </div>
                                        <a href="#" class="fw-bold text-muted text-hover-primary fs-7">
                                            {{ auth('customer')->user()->email }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="separator my-2"></div>
                            <div class="menu-item px-5">
                                <a href="{{ route('en.customer.profile') }}" class="menu-link px-5">My
                                    Profile</a>
                            </div>
                            <div class="separator my-2"></div>
                            <div class="menu-item px-5">
                                <a href="#"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                                    class="menu-link px-5">
                                    Logout
                                </a>
                                <form id="logout-form" action="{{ route('en.customer.logout') }}" method="POST"
                                    style="display: none;">
                                    @csrf
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('en.customer.login') }}" class="btn btn-sm btn-primary">Login</a>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</div>
