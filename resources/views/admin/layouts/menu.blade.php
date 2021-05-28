        <!-- ============================================================== -->
        <!-- left sidebar -->
        <!-- ============================================================== -->
        <div class="nav-left-sidebar sidebar-dark">
            <div class="menu-list">
                <nav class="navbar navbar-expand-lg navbar-light">
                    <a class="d-xl-none d-lg-none" href="#">Dashboard</a>
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="navbar-nav flex-column">
                            <li class="nav-divider">
                                Menu
                            </li>
                            <li class="nav-item ">
                                <a class="nav-link @if(Route::is('admin_dashboard')) active @endif" href="#"><i class="fa fa-fw fa-user-circle"></i>Dashboard </a>
                            </li>
							
							 <li class="nav-item">
                                <a class="nav-link @if(Route::is('ownerList') || Route::is('investorList')) active @endif" href="#" data-toggle="collapse" aria-expanded="false" data-target="#submenu-2" aria-controls="submenu-2"><i class="fa fa-fw fa-user-circle"></i>User Mangement</a>
                                <div id="submenu-2" class="collapse submenu" style="">
                                    <ul class="nav flex-column">
                                        <li class="nav-item">
                                            <a class="nav-link" href="{{ route('ownerList') }}">Owner</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="{{ route('investorList') }}">Investor</a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
							
							<li class="nav-item ">
                                <a class="nav-link @if(Route::is('projectList')) active @endif" href="{{ route('projectList') }}"><i class="fa fa-tasks"></i>Project Mangement </a>
                            </li>
							<li class="nav-item ">
                                <a class="nav-link @if(Route::is('investmentList') || Route::is('investmentlist_show')) active @endif" href="{{ route('investmentList') }}"><i class="fa fa-tasks"></i>Investment Mangement </a>
                            </li>
                            <li class="nav-item ">
                                <a class="nav-link @if(Route::is('disbursementGetList') || Route::is('disbursementDetail')) active @endif" href="{{ route('disbursementGetList') }}"><i class="fa fa-tasks"></i>Disbursement Mangement </a>
                            </li>
                            <li class="nav-item ">
                                <a class="nav-link @if(Route::is('renderNewDisbursement')) active @endif" href="{{ route('renderNewDisbursement') }}"><i class="fa fa-tasks"></i>Add New Disbursement  </a>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
        </div>
        <!-- ============================================================== -->
        <!-- end left sidebar -->
        <!-- ============================================================== -->