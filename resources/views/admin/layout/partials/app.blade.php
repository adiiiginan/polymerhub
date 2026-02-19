<!DOCTYPE html>
<html lang="en">


<head>
    @include('admin.Layout.partials.header')
    <link href="{{ asset('backend/assets/css/custom.css') }}" rel="stylesheet" type="text/css" />
    <title>@yield('title', 'Dashboard')</title>
</head>


<body id="kt_app_body" class="app-default">
    <div class="d-flex flex-column flex-root app-root" id="kt_app_root">
        <div class="app-page flex-column flex-column-fluid" id="kt_app_page">
            <!-- HEADER MULAI DI SINI -->
            <div id="kt_app_header" class="app-header">
                @include('admin.Layout.partials.navbar')
            </div>
            <!-- HEADER SELESAI -->
            <div class="app-wrapper d-flex flex-row flex-row-fluid" id="kt_app_wrapper">
                @include('admin.Layout.partials.sidebar')
                <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
                    @yield('content')

                    <!--begin::Footer-->
                    @include('admin.Layout.partials.footer')
                    <!--end::Footer-->
                </div>
            </div>
        </div>
    </div>
    @include('admin.Layout.partials.scripts')
    @stack('scripts')
</body>

</html>
