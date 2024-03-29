<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>PRIM</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="Parental Relationship Information Management" name="description" />
        <meta content="UTeM" name="author" />
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <!-- App favicon -->
        <link rel="shortcut icon" href="{{ URL::asset('assets/images/logo/fav-logo-prim.png')}}">
        <!-- Web Application Manifest -->
        <link rel="manifest" href="/manifest.json">
        @include('layouts.head')
        
    </head>

    <body data-sidebar="dark">
    @section('body')
    @show
    <div id="preloader">
        <div id="status">
            <div class="spinner-chase">
                <div class="chase-dot"></div>
                <div class="chase-dot"></div>
                <div class="chase-dot"></div>
                <div class="chase-dot"></div>
                <div class="chase-dot"></div>
                <div class="chase-dot"></div>
            </div>
        </div>
    </div>
          <!-- Begin page -->
          <div id="layout-wrapper">
            @auth
                @include('layouts.topbar')
                @include('layouts.sidebar')
            @else
                @include('layouts.topbar-guest')
            @endauth
            <!-- ============================================================== -->
            <!-- Start right Content here -->
            <!-- ============================================================== -->
            @auth
                <div class="main-content">
            @endauth
            <div class="page-content">
                <div class="container-fluid">
                    @yield('content')
                </div>
                <!-- container-fluid -->
            </div>
            <!-- End Page-content -->
            @include('layouts.footer')
            </div>
            <!-- end main content-->
    </div>
    <!-- END layout-wrapper -->

    <!-- Right Sidebar -->
    {{-- @include('layouts.right-sidebar') --}}

    <!-- /Right-bar -->

    <!-- Right bar overlay-->
    {{-- <div class="rightbar-overlay"></div> --}}

    <!-- JAVASCRIPT -->
    @include('layouts.footer-script')    
    </body>
</html>