<!doctype html>
<html lang="en">

 
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS -->
	<meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('admin/assets/vendor/bootstrap/css/bootstrap.min.css') }}">

    <link href="{{ asset('admin/assets/vendor/fonts/circular-std/style.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('admin/assets/libs/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/assets/libs/css/payments.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/assets/vendor/fonts/fontawesome/css/fontawesome-all.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/assets/vendor/vector-map/jqvmap.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/assets/vendor/jvectormap/jquery-jvectormap-2.0.2.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/assets/vendor/fonts/flag-icon-css/flag-icon.min.css') }}">
	<link href="//cdn.datatables.net/1.10.23/css/jquery.dataTables.min.css" rel="stylesheet" />
    <script src="{{ asset('admin/assets/vendor/jquery/jquery-3.3.1.min.js') }}"></script>
    <script src="//cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>
    <script src="{{ asset('js/bootstrap-notify.js') }}"></script>

    <title>{{ucfirst(Route::currentRouteName())}}</title>
</head>

<body>
    <!-- ============================================================== -->
    <!-- main wrapper -->
    <!-- ============================================================== -->
    <div class="dashboard-main-wrapper">
        <!-- ============================================================== -->
        <!-- navbar -->
        <!-- ============================================================== -->
        <div class="dashboard-header">
            <nav class="navbar navbar-expand-lg bg-white fixed-top">
              <a href="#"><img class="logo-img" src="{{ asset('admin/assets/images/logo.svg') }}" alt="logo"></a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse " id="navbarSupportedContent">
                    <ul class="navbar-nav ml-auto navbar-right-top">
                        <li class="nav-item">
                            <div id="custom-search" class="top-search-bar">
                                <input class="form-control" type="text" placeholder="Search..">
                            </div>
                        </li>
                        
                    
                        <li class="nav-item dropdown nav-user">
                            <a class="nav-link nav-user-img" href="#" id="navbarDropdownMenuLink2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img src="{{asset('admin/assets/images/avatar-1.jpg')}}" alt="" class="user-avatar-md rounded-circle"></a>
                            <div class="dropdown-menu dropdown-menu-right nav-user-dropdown" aria-labelledby="navbarDropdownMenuLink2">
                                <div class="nav-user-info">
                                    <h5 class="mb-0 text-white nav-user-name">{{ Auth::guard('admin')->user()->name ?? ''}} </h5>
                                    <span class="status"></span><span class="ml-2">Available</span>
                                </div>
                               <!-- <a class="dropdown-item" href="#"><i class="fas fa-user mr-2"></i>Account</a>
                                <a class="dropdown-item" href="#"><i class="fas fa-cog mr-2"></i>Setting</a> -->
                                <a class="dropdown-item" href="logout"><i class="fas fa-power-off mr-2"></i>Logout</a>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
        <!-- ============================================================== -->
        <!-- end navbar -->
        <!-- ============================================================== -->
		@include('admin.layouts.menu')
        <!-- ============================================================== -->
        <!-- wrapper  -->
        <!-- ============================================================== -->
        <div class="dashboard-wrapper">
            <div class="container-fluid  dashboard-content">
              
              
          @yield('content')
              
            </div>
            <!-- ============================================================== -->
            <!-- footer -->
            <!-- ============================================================== -->
            <div class="footer">
                <div class="container-fluid">
                    <div class="row">
                      <!--  <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                            Copyright © 2021 Concept. All rights reserved. Dashboard by <a href="https://colorlib.com/wp/">iapp</a>.
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                            <div class="text-md-right footer-links d-none d-sm-block">
                                <a href="javascript: void(0);">About</a>
                                <a href="javascript: void(0);">Support</a>
                                <a href="javascript: void(0);">Contact Us</a>
                            </div>
                        </div> -->
                    </div>
                </div>
            </div>
            <!-- ============================================================== -->
            <!-- end footer -->
            <!-- ============================================================== -->
        </div>
        <!-- ============================================================== -->
        <!-- end wrapper  -->
        <!-- ============================================================== -->
    </div>
    <!-- ============================================================== -->
    <!-- end main wrapper  -->
    <!-- ============================================================== -->
    <!-- Optional JavaScript -->
    <!-- jquery 3.3.1 js-->
    
    <!-- bootstrap bundle js-->
    <script src="{{ asset('admin/assets/vendor/bootstrap/js/bootstrap.bundle.js') }}"></script>
    <!-- slimscroll js-->
    <script src="{{ asset('admin/assets/vendor/slimscroll/jquery.slimscroll.js') }}"></script>
    <!-- chartjs js-->
    <script src="{{ asset('admin/assets/vendor/charts/charts-bundle/Chart.bundle.js') }}"></script>
    <script src="{{ asset('admin/assets/vendor/charts/charts-bundle/chartjs.js') }}"></script>
   
    <!-- main js-->
    <script src="{{ asset('admin/assets/libs/js/main-js.js') }}"></script>
    <!-- jvactormap js-->
    <script src="{{ asset('admin/assets/vendor/jvectormap/jquery-jvectormap-2.0.2.min.js') }}"></script>
    <script src="{{ asset('admin/assets/vendor/jvectormap/jquery-jvectormap-world-mill-en.js') }}"></script>
    <!-- sparkline js-->
    <script src="{{ asset('admin/assets/vendor/charts/sparkline/jquery.sparkline.js') }}"></script>
    <script src="{{ asset('admin/assets/vendor/charts/sparkline/spark-js.js') }}"></script>
     <!-- dashboard sales js-->
	

    <script src="{{ asset('admin/assets/libs/js/dashboard-sales.js') }}"></script>
    <script src="{{ asset('admin/js/custom.js') }}"></script>
	<script src="{{ asset('js/admin.js') }}"></script>

</body>
 
</html>