<!--begin::Sidebar-->
<div id="kt_app_sidebar" class="app-sidebar flex-column" data-kt-drawer="true" data-kt-drawer-name="app-sidebar"
    data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="250px"
    data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_app_sidebar_mobile_toggle">

    <!--begin::Wrapper-->
    <div class="app-sidebar-wrapper">
        <div id="kt_app_sidebar_wrapper" class="hover-scroll-y my-5 my-lg-2 mx-4" data-kt-scroll="true"
            data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-height="auto"
            data-kt-scroll-dependencies="#kt_app_header" data-kt-scroll-wrappers="#kt_app_sidebar_wrapper"
            data-kt-scroll-offset="5px">

            <!--begin::Sidebar menu-->
            <div data-kt-menu="true"
                class="app-sidebar-menu-primary menu menu-column menu-rounded menu-sub-indention menu-state-bullet-primary px-3 mb-5"
                style="font-weight: bold; font-size: 1.1rem;">

                <!-- Dashboard -->
                <div class="menu-item">
                    <a href="" class="menu-link">
                        <span class="menu-icon"><i class="ki-outline ki-home-2 fs-2"></i></span>
                        <span class="menu-title fw-bold">Dashboard</span>
                    </a>
                </div>

                @if (auth()->check())
                    @php
                        $id_priviladges = auth()->user()->id_priviladges;
                    @endphp

                    {{-- ===================================== --}}
                    {{-- IT / Admin (1 dan 6) --}}
                    {{-- ===================================== --}}
                    @if ($id_priviladges == 1 || $id_priviladges == 6)
                        <!-- Produk -->
                        <div data-kt-menu-trigger="click" class="menu-item menu-accordion">
                            <span class="menu-link">
                                <span class="menu-icon"><i class="ki-outline ki-abstract-26 fs-2"></i></span>
                                <span class="menu-title fw-bold">Produk</span>
                                <span class="menu-arrow"></span>
                            </span>
                            <div class="menu-sub menu-sub-accordion" style="font-size: 1.1rem;">
                                <a href="{{ route('admin.produk.index') }}" class="menu-link">
                                    <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                    <span class="menu-title fw-bold">Daftar Produk</span>
                                </a>
                                <a href="{{ route('admin.produk.create') }}" class="menu-link">
                                    <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                    <span class="menu-title fw-bold">Tambah Produk</span>
                                </a>
                            </div>
                        </div>

                        <!-- Laporan -->
                        <div data-kt-menu-trigger="click" class="menu-item menu-accordion">
                            <span class="menu-link">
                                <span class="menu-icon"><i class="ki-outline ki-document fs-2"></i></span>
                                <span class="menu-title fw-bold">Contact</span>
                                <span class="menu-arrow"></span>
                            </span>
                            <div class="menu-sub menu-sub-accordion" style="font-size: 1.1rem;">

                                @if ($id_priviladges == 1)
                                    <a href="{{ route('admin.contact.index') }}" class="menu-link">
                                        <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                        <span class="menu-title fw-bold">Contact</span>
                                    </a>
                                @endif
                            </div>
                        </div>

                        <!-- Shipping -->
                        <div data-kt-menu-trigger="click" class="menu-item menu-accordion">
                            <span class="menu-link">
                                <span class="menu-icon"><i class="ki-outline ki-handcart fs-2"></i></span>
                                <span class="menu-title fw-bold">Shipping</span>
                                <span class="menu-arrow"></span>
                            </span>
                            <div class="menu-sub menu-sub-accordion">
                                <a href="{{ route('admin.ship.index') }}" class="menu-link">
                                    <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                    <span class="menu-title">List Shipping Process</span>
                                </a>
                                <a href="{{ route('admin.ship.shipment') }}" class="menu-link">
                                    <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                    <span class="menu-title">Shipping Complete</span>
                                </a>
                            </div>
                        </div>
                    @endif

                    {{-- ===================================== --}}
                    {{-- Stok Opname (1, 6, 4) --}}
                    {{-- ===================================== --}}
                    @if ($id_priviladges == 1 || $id_priviladges == 6 || $id_priviladges == 4)
                        <!-- Stok Opname -->
                        <div data-kt-menu-trigger="click" class="menu-item menu-accordion">
                            <span class="menu-link">
                                <span class="menu-icon"><i class="ki-outline ki-archive fs-2"></i></span>
                                <span class="menu-title fw-bold">Stok Opname</span>
                                <span class="menu-arrow"></span>
                            </span>
                            <div class="menu-sub menu-sub-accordion">
                                <a href="{{ route('admin.stok-opname.create') }}" class="menu-link">
                                    <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                    <span class="menu-title">Buat Opname Baru</span>
                                </a>
                                <a href="{{ route('admin.stok-opname.index') }}" class="menu-link">
                                    <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                    <span class="menu-title">Daftar Opname</span>
                                </a>
                                @if ($id_priviladges == 1 || $id_priviladges == 6)
                                    <a href="{{ route('admin.stok-opname.selesai') }}" class="menu-link">
                                        <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                        <span class="menu-title">Laporan Stok Opname</span>
                                    </a>
                                @endif

                            </div>
                        </div>
                    @endif

                    {{-- ===================================== --}}
                    {{-- Finance --}}
                    {{-- ===================================== --}}
                    @if ($id_priviladges == 5 || $id_priviladges == 1)
                        <div data-kt-menu-trigger="click" class="menu-item menu-accordion">
                            <span class="menu-link">
                                <span class="menu-icon"><i class="ki-outline ki-dollar fs-2"></i></span>
                                <span class="menu-title fw-bold">Invoice</span>
                                <span class="menu-arrow"></span>
                            </span>
                            <div class="menu-sub menu-sub-accordion">
                                <a href="{{ route('admin.transaksi.invo.index') }}" class="menu-link">
                                    <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                    <span class="menu-title">List Invoice</span>
                                </a>
                                <a href="{{ route('admin.transaksi.invoice_paid') }}" class="menu-link">
                                    <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                    <span class="menu-title">Invoice Paid</span>
                                </a>
                            </div>
                        </div>
                    @endif

                    {{-- ===================================== --}}
                    {{-- Sales --}}
                    {{-- ===================================== --}}
                    @if ($id_priviladges == 2 || $id_priviladges == 1 || $id_priviladges == 6)
                        <div data-kt-menu-trigger="click" class="menu-item menu-accordion">
                            <span class="menu-link">
                                <span class="menu-icon"><i class="ki-outline ki-handcart fs-2"></i></span>
                                <span class="menu-title fw-bold">Order</span>
                                <span class="menu-arrow"></span>
                            </span>
                            <div class="menu-sub menu-sub-accordion">
                                <a href="" class="menu-link">
                                    <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                    <span class="menu-title">Order Request</span>

                                    <a href="{{ route('admin.transaksi.pending') }}" class="menu-link">
                                        <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                        <span class="menu-title">Order Confirmation</span>
                                    </a>


                            </div>
                        </div>
                    @endif


                    {{-- ===================================== --}}
                    {{-- Order Tracking --}}
                    {{-- ===================================== --}}
                    @if (Auth::check() && Auth::user()->id_priviladges == 7)
                        <div data-kt-menu-trigger="click" class="menu-item menu-accordion">
                            <span class="menu-link">
                                <span class="menu-icon"><i class="ki-outline ki-handcart fs-2"></i></span>
                                <span class="menu-title fw-bold">Order Tracking</span>
                                <span class="menu-arrow"></span>
                            </span>
                            <div class="menu-sub menu-sub-accordion">
                                <a href="{{ route('admin.transaksi.po') }}" class="menu-link">
                                    <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                    <span class="menu-title">Order Accepted</span>
                                </a>
                                <a href="{{ route('admin.ship.shipment') }}" class="menu-link">
                                    <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                    <span class="menu-title">Order On Progress</span>
                                </a>
                                <a href="{{ route('admin.transaksi.completed') }}" class="menu-link">
                                    <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                    <span class="menu-title">Order Completed</span>
                                </a>
                            </div>
                        </div>
                    @endif

                    {{-- ===================================== --}}
                    {{-- IT --}}
                    {{-- ===================================== --}}
                    @if ($id_priviladges == 1)
                        <div data-kt-menu-trigger="click" class="menu-item menu-accordion">
                            <span class="menu-link">
                                <span class="menu-icon"><i class="ki-outline ki-handcart fs-2"></i></span>
                                <span class="menu-title fw-bold">Pelanggan</span>
                                <span class="menu-arrow"></span>
                            </span>
                            <div class="menu-sub menu-sub-accordion">
                                <a href="{{ route('admin.user.customer') }}" class="menu-link">
                                    <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                    <span class="menu-title">Pelanggan Baru</span>
                                </a>
                                <a href="{{ route('admin.user.verified') }}" class="menu-link">
                                    <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                    <span class="menu-title">List Customer</span>
                                </a>

                            </div>
                        </div>

                        <div data-kt-menu-trigger="click" class="menu-item menu-accordion">
                            <span class="menu-link">
                                <span class="menu-icon"><i class="ki-outline ki-article fs-2"></i></span>
                                <span class="menu-title fw-bold">Articel</span>
                                <span class="menu-arrow"></span>
                            </span>
                            <div class="menu-sub menu-sub-accordion">
                                <a href="{{ route('admin.articles.create') }}" class="menu-link">
                                    <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                    <span class="menu-title">Create Article</span>
                                </a>
                                <a href="{{ route('admin.articles.index') }}" class="menu-link">
                                    <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                    <span class="menu-title">List Article</span>
                                </a>

                            </div>
                        </div>
                    @endif

                    <!-- User Management --> <?php if (Auth::check() && (Auth::user()->id_priviladges == 1)): ?> <div data-kt-menu-trigger="click"
                        class="menu-item menu-accordion"> <span class="menu-link"> <span class="menu-icon"><i
                                    class="ki-outline ki-user fs-2"></i></span> <span class="menu-title fw-bold">User
                                Management</span> <span class="menu-arrow"></span> </span>
                        <div class="menu-sub menu-sub-accordion" style="font-weight: normal; font-size: 1rem;"> <a
                                href="{{ route('admin.user.index') }}" class="menu-link"> <span
                                    class="menu-bullet"><span class="bullet bullet-dot"></span></span> <span
                                    class="menu-title fw-bold">User
                                    List</span> </a> <a href="{{ route('admin.user.create') }}" class="menu-link">
                                <span class="menu-bullet"><span class="bullet bullet-dot"></span></span> <span
                                    class="menu-title fw-bold">Create User</span> </a> </div>
                    </div> <?php endif; ?>


                    {{-- ===================================== --}}
                    {{-- Gudang --}}
                    {{-- ===================================== --}}
                    @if ($id_priviladges == 4)
                        <div data-kt-menu-trigger="click" class="menu-item menu-accordion">
                            <span class="menu-link">
                                <span class="menu-icon"><i class="ki-outline ki-archive fs-2"></i></span>
                                <span class="menu-title fw-bold">Gudang</span>
                                <span class="menu-arrow"></span>
                            </span>
                            <div class="menu-sub menu-sub-accordion">
                                <a href="{{ route('admin.ship.index') }}" class="menu-link"> <span
                                        class="menu-bullet"><span class="bullet bullet-dot"></span></span> <span
                                        class="menu-title fw-bold">List
                                        Shipping Proccess</span> </a>
                                <a href="{{ route('admin.ship.shipment') }}" class="menu-link"> <span
                                        class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                    <span class="menu-title fw-bold">Shiping Complete</span> </a>
                            </div>
                        </div>
                    @endif


                @endif
            </div>
            <!--end::Sidebar menu-->
        </div>
    </div>
    <!--end::Wrapper-->
</div>
<!--end::Sidebar-->
