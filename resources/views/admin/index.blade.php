<!DOCTYPE html>
<html lang="en">


<head>
    @include('Admin.layout.partials.header')
    <title>@yield('title', 'Dashboard')</title>
</head>


<body id="kt_app_body" class="app-default">
    <div class="d-flex flex-column flex-root app-root" id="kt_app_root">
        <div class="app-page flex-column flex-column-fluid" id="kt_app_page">
            <!-- HEADER MULAI DI SINI -->
            <div id="kt_app_header" class="app-header">
                @include('Admin.layout.partials.navbar')
            </div>
            <!-- HEADER SELESAI -->
            <div class="app-wrapper d-flex flex-row flex-row-fluid" id="kt_app_wrapper">
                @include('Admin.layout.partials.sidebar')
                <div class="app-main flex-column flex-row-fluid" id="kt_app_main">



                </div>


            </div>
        </div>
        @include('Admin.layout.partials.footer')
        @include('Admin.layout.partials.scripts')
</body>

</html>
