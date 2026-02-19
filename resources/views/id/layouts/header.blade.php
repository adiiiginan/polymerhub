<div id="kt_header" style="" class="header align-items-stretch">
    <div class="container-fluid d-flex align-items-stretch justify-content-between">
        <div class="d-flex align-items-center flex-grow-1 flex-lg-grow-0 me-lg-15">
            <a href="{{ route('id.home') }}">
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
                            <a class="menu-link py-3" href="{{ route('id.home') }}">
                                <span class="menu-title">Beranda</span>
                            </a>
                        </div>
                        <div class="menu-item me-lg-1">
                            <a class="menu-link py-3" href="{{ route('id.brochure') }}">
                                <span class="menu-title">Brosur</span>
                            </a>
                        </div>
                        <div class="menu-item me-lg-1">
                            <a class="menu-link py-3" href="{{ route('id.contact') }}">
                                <span class="menu-title">Kontak</span>
                            </a>
                        </div>
                        @auth('customer')
                            <div class="menu-item me-lg-1">
                                <a class="menu-link py-3" href="{{ route('id.customer.dashboard') }}">
                                    <span class="menu-title">Dasbor</span>
                                </a>
                            </div>
                        @endauth
                    </div>
                </div>
            </div>
            <div class="d-flex align-items-stretch flex-shrink-0">
                <div class="d-flex align-items-center ms-1 ms-lg-3">
                    @auth('customer')
                        <a href="{{ route('id.customer.profile') }}" class="btn btn-sm btn-primary">Akun Saya</a>
                        <form action="{{ route('id.customer.logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-light-primary ms-2">Keluar</button>
                        </form>
                    @else
                        <a href="{{ route('id.customer.login') }}" class="btn btn-sm btn-primary">Masuk</a>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</div>
