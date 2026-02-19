<!--begin::Sidebar-->
<div id="kt_app_sidebar" class="app-sidebar flex-column" data-kt-drawer="true" data-kt-drawer-name="app-sidebar"
    data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="250px"
    data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_app_sidebar_mobile_toggle"> <!--begin::Wrapper-->
    <div class="app-sidebar-wrapper">
        <div id="kt_app_sidebar_wrapper" class="hover-scroll-y my-5 my-lg-2 mx-4" data-kt-scroll="true"
            data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-height="auto"
            data-kt-scroll-dependencies="#kt_app_header" data-kt-scroll-wrappers="#kt_app_sidebar_wrapper"
            data-kt-scroll-offset="5px"> <!--begin::Sidebar menu-->
            <div data-kt-menu="true"
                class="app-sidebar-menu-primary menu menu-column menu-rounded menu-sub-indention menu-state-bullet-primary px-3 mb-5"
                style="font-weight: normal; font-size: 1rem;"> <!-- Dashboard -->
                <div class="menu-item"> <a href="" class="menu-link"> <span class="menu-icon"><i
                                class="ki-outline ki-home-2 fs-2"></i></span> <span
                            class="menu-title fw-bold">Dashboard</span> </a> </div> <?php if (auth()->check()): ?>
                <?php $id_priviladges = auth()->user()->id_priviladges; ?> {{-- IT (Super Admin) sees all --}} <?php if ($id_priviladges == 1 || Auth::user()->id_priviladges == 6):?> <!-- Produk -->
                <div data-kt-menu-trigger="click" class="menu-item menu-accordion">
                    <span class="menu-link">
                        <span class="menu-icon">
                            <i class="ki-outline ki-abstract-26 fs-2"></i></span>
                        <span class="menu-title fw-bold">Produk</span>
                        <span class="menu-arrow"></span>
                    </span>
                    <div class="menu-sub menu-sub-accordion" style="font-weight: normal; font-size: 1rem;"> <a
                            href="{{ route('admin.produk.index') }}" class="menu-link"> <span class="menu-bullet"><span
                                    class="bullet bullet-dot"></span></span> <span class="menu-title fw-bold">Daftar
                                Produk</span> </a> <a href="{{ route('admin.produk.create') }}" class="menu-link"> <span
                                class="menu-bullet"><span class="bullet bullet-dot"></span></span> <span
                                class="menu-title fw-bold">Tambah Produk</span> </a> </div>
                </div> <!-- Pesanan -->
                <div data-kt-menu-trigger="click" class="menu-item menu-accordion"> <span class="menu-link"> <span
                            class="menu-icon"> <i class="ki-outline ki-duotone ki-archive fs-2"></i> </span> <span
                            class="menu-title fw-bold">Stok Opname</span> <span class="menu-arrow"></span> </span>
                    <div class="menu-sub menu-sub-accordion"> <a href="{{ route('admin.stok-opname.create') }}"
                            class="menu-link"> <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                            <span class="menu-title">Buat Opname Baru</span> </a> <a
                            href="{{ route('admin.stok-opname.index') }}" class="menu-link"> <span
                                class="menu-bullet"><span class="bullet bullet-dot"></span></span> <span
                                class="menu-title">Daftar Opname</span> </a> <a href="#" class="menu-link"> <span
                                class="menu-bullet"><span class="bullet bullet-dot"></span></span> <span
                                class="menu-title">Laporan Opname</span> </a> </div>
                </div> <!-- Laporan -->
                <div data-kt-menu-trigger="click" class="menu-item menu-accordion"> <span class="menu-link"> <span
                            class="menu-icon"><i class="ki-outline ki-chart fs-2"></i></span> <span
                            class="menu-title fw-bold">Laporan</span> <span class="menu-arrow"></span> </span>
                    <div class="menu-sub menu-sub-accordion"> <a href="" class="menu-link"> <span
                                class="menu-bullet"><span class="bullet bullet-dot"></span></span> <span
                                class="menu-title">Penjualan</span> </a> </div>
                </div> <!-- Shipping -->
                <div data-kt-menu-trigger="click" class="menu-item menu-accordion"> <span class="menu-link"> <span
                            class="menu-icon"><i class="ki-outline ki-duotone ki-handcart fs-2"></i></span> <span
                            class="menu-title fw-bold">Shipping</span> <span class="menu-arrow"></span> </span>
                    <div class="menu-sub menu-sub-accordion" style="font-weight: normal; font-size: 1rem;"> <a
                            href="{{ route('admin.ship.index') }}" class="menu-link"> <span class="menu-bullet"><span
                                    class="bullet bullet-dot"></span></span> <span class="menu-title fw-bold">List
                                Shipping Proccess</span> </a> <a href="{{ route('admin.ship.shipment') }}"
                            class="menu-link"> <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                            <span class="menu-title fw-bold">Shiping Complete</span> </a> </div>
                </div> {{-- Keuangan (Finance) --}} <?php if (Auth::check() && (Auth::user()->id_priviladges == 5 || Auth::user()->id_priviladges == 1)): ?> <!-- Invoice -->
                <div data-kt-menu-trigger="click" class="menu-item menu-accordion"> <span class="menu-link"> <span
                            class="menu-icon"><i class="ki-outline ki-duotone ki-handcart fs-2"></i></span> <span
                            class="menu-title fw-bold">Invoice</span> <span class="menu-arrow"></span> </span>
                    <div class="menu-sub menu-sub-accordion" style="font-weight: normal; font-size: 1rem;"> <a
                            href="{{ route('admin.transaksi.invo.index') }}" class="menu-link"> <span
                                class="menu-bullet"><span class="bullet bullet-dot"></span></span> <span
                                class="menu-title fw-bold">List Invoice</span> </a> <a
                            href="{{ route('admin.transaksi.invoice_paid') }}" class="menu-link"> <span
                                class="menu-bullet"><span class="bullet bullet-dot"></span></span> <span
                                class="menu-title fw-bold">Invoice Paid</span> </a> </div>
                </div> <?php endif; ?> <!-- User Management --> <?php if (Auth::check() && (Auth::user()->id_priviladges == 1)): ?> <div
                    data-kt-menu-trigger="click" class="menu-item menu-accordion"> <span class="menu-link"> <span
                            class="menu-icon"><i class="ki-outline ki-user fs-2"></i></span> <span
                            class="menu-title fw-bold">User Management</span> <span class="menu-arrow"></span> </span>
                    <div class="menu-sub menu-sub-accordion" style="font-weight: normal; font-size: 1rem;"> <a
                            href="{{ route('user.index') }}" class="menu-link"> <span class="menu-bullet"><span
                                    class="bullet bullet-dot"></span></span> <span class="menu-title fw-bold">User
                                List</span> </a> <a href="{{ route('user.create') }}" class="menu-link"> <span
                                class="menu-bullet"><span class="bullet bullet-dot"></span></span> <span
                                class="menu-title fw-bold">Create User</span> </a> </div>
                </div> <?php endif; ?> {{-- Sales (Admin) --}} <?php if ($id_priviladges == 2 || $id_priviladges == 1 || $id_priviladges == 6): ?> <!-- Pesanan -->
                <div data-kt-menu-trigger="click" class="menu-item menu-accordion"> <span class="menu-link"> <span
                            class="menu-icon"><i class="ki-outline ki-duotone ki-handcart fs-2"></i></span> <span
                            class="menu-title fw-bold">Pesanan</span> <span class="menu-arrow"></span> </span>
                    <div class="menu-sub menu-sub-accordion" style="font-weight: normal; font-size: 1rem;"> <a
                            href="{{ route('admin.transaksi.requests') }}" class="menu-link"> <span
                                class="menu-bullet"><span class="bullet bullet-dot"></span></span> <span
                                class="menu-title fw-bold">Pesanan Request</span> </a> <a
                            href="{{ route('admin.transaksi.pending') }}" class="menu-link"> <span
                                class="menu-bullet"><span class="bullet bullet-dot"></span></span> <span
                                class="menu-title fw-bold">Pesanan Pending</span> </a> <a
                            href="{{ route('admin.transaksi.po') }}" class="menu-link"> <span
                                class="menu-bullet"><span class="bullet bullet-dot"></span></span> <span
                                class="menu-title fw-bold">Pesanan PO</span> </a> <a
                            href="{{ route('admin.ship.shipment') }}" class="menu-link"> <span
                                class="menu-bullet"><span class="bullet bullet-dot"></span></span> <span
                                class="menu-title fw-bold">Pesanan Kirim</span> </a> <a
                            href="{{ route('admin.transaksi.pending') }}" class="menu-link"> <span
                                class="menu-bullet"><span class="bullet bullet-dot"></span></span> <span
                                class="menu-title fw-bold">Pesanan Selesai</span> </a> </div>
                </div> <?php endif; ?> {{-- Customer (Manager) --}} <?php if ($id_priviladges == 1): ?> <!-- Pelanggan -->
                <div data-kt-menu-trigger="click" class="menu-item menu-accordion"> <span class="menu-link"> <span
                            class="menu-icon"><i class="ki-outline ki-user fs-2"></i></span> <span
                            class="menu-title fw-bold">Pelanggan</span> <span class="menu-arrow"></span> </span>
                    <div class="menu-sub menu-sub-accordion" style="font-weight: normal; font-size: 1rem;"> <a
                            href="{{ route('user.customer') }}" class="menu-link"> <span class="menu-bullet"><span
                                    class="bullet bullet-dot"></span></span> <span
                                class="menu-title fw-bold">Pelanggan Baru</span> </a> <a
                            href="{{ route('user.verified') }}" class="menu-link"> <span class="menu-bullet"><span
                                    class="bullet bullet-dot"></span></span> <span class="menu-title fw-bold">Daftar
                                Pelanggan</span> </a> </div>
                </div> <?php endif; ?> {{-- Gudang (User) --}} <?php if ($id_priviladges == 4): ?> <!-- Produk -->
                <div data-kt-menu-trigger="click" class="menu-item menu-accordion"> <span class="menu-link"> <span
                            class="menu-icon"><i class="ki-outline ki-abstract-26 fs-2"></i></span> <span
                            class="menu-title fw-bold">Produk</span> <span class="menu-arrow"></span> </span>
                    <div class="menu-sub menu-sub-accordion" style="font-weight: normal; font-size: 1rem;"> <a
                            href="{{ route('admin.produk.index') }}" class="menu-link"> <span
                                class="menu-bullet"><span class="bullet bullet-dot"></span></span> <span
                                class="menu-title fw-bold">Daftar Produk</span> </a> <a
                            href="{{ route('admin.produk.create') }}" class="menu-link"> <span
                                class="menu-bullet"><span class="bullet bullet-dot"></span></span> <span
                                class="menu-title fw-bold">Tambah Produk</span> </a> </div>
                </div> <!-- Shipping -->
                <div data-kt-menu-trigger="click" class="menu-item menu-accordion"> <span class="menu-link"> <span
                            class="menu-icon"><i class="ki-outline ki-duotone ki-handcart fs-2"></i></span> <span
                            class="menu-title fw-bold">Shipping</span> <span class="menu-arrow"></span> </span>
                    <div class="menu-sub menu-sub-accordion" style="font-weight: normal; font-size: 1rem;"> <a
                            href="{{ route('admin.ship.index') }}" class="menu-link"> <span
                                class="menu-bullet"><span class="bullet bullet-dot"></span></span> <span
                                class="menu-title fw-bold">List Shipping Proccess</span> </a> <a
                            href="{{ route('admin.ship.shipment') }}" class="menu-link"> <span
                                class="menu-bullet"><span class="bullet bullet-dot"></span></span> <span
                                class="menu-title fw-bold">Shiping Complete</span> </a> </div>
                </div> <?php endif; ?> <?php endif; ?>
            </div> <!--end::Sidebar menu-->
        </div> <!--end::Wrapper-->
    </div> <!--end::Sidebar-->
</div>
