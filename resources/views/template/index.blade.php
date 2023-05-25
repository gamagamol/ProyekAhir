<?php
use App\Http\Controllers\DashboardController;
$notif = new DashboardController();
$notif = $notif->notif();

?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>{{ $tittle }}</title>
    {{-- jQuery --}}
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous">
    </script>



    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.js"></script>


    {{-- masking --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.js"
        integrity="sha256-yE5LLp5HSQ/z+hJeCqkz9hdjNkk1jaiGG0tDCraumnA=" crossorigin="anonymous"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous">
    </script>




    <!-- Custom fonts for this template-->
    <link href="{{ asset('assets/') }}/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="{{ asset('assets/') }}/css/sb-admin-2.min.css" rel="stylesheet">

    {{-- data table  --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.css" />




</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ url('dashboard') }}">
                <div class="sidebar-brand-icon ">
                    <i class="fas fa-file-invoice-dollar"></i>
                </div>
                <div class="sidebar-brand-text mx-3">{{ str_replace('_', ' ', Auth::user()->status_pengguna) }}</div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">
            <!-- Nav Item - Dashboard -->
            <li class="nav-item ">
                <a class="nav-link" href={{ url('dashboard') }}>
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Collapse Menu -->

            {{-- MASTERDATA --}}
            <li class="nav-item" id="nav-masterdata">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities"
                    aria-expanded="true" aria-controls="collapseUtilities" id="masterdata-menu"
                    @if (Auth::user()->status_pengguna != 'SALES_ADMIN' &&
                            Auth::user()->status_pengguna != 'ACCOUNTING_ADMIN' &&
                            Auth::user()->status_pengguna != 'SUPER_ADMIN') hidden @endif>
                    <i class="fas fa-folder-open"></i>
                    <span>Master Data</span>
                </a>
                <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities"
                    data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Mater Data:</h6>
                        <a class="collapse-item" href="{{ url('product') }}"
                            @if (Auth::user()->status_pengguna != 'SALES_ADMIN' && Auth::user()->status_pengguna != 'SUPER_ADMIN') hidden @endif>Product</a>
                        <a class="collapse-item" href="{{ url('services') }}"
                            @if (Auth::user()->status_pengguna != 'SALES_ADMIN' && Auth::user()->status_pengguna != 'SUPER_ADMIN') hidden @endif>Services</a>

                        <a class="collapse-item" href="{{ url('custumor') }}"
                            @if (Auth::user()->status_pengguna != 'SALES_ADMIN' && Auth::user()->status_pengguna != 'SUPER_ADMIN') hidden @endif>Customer</a>
                        <a class="collapse-item" href="{{ url('COA') }}"
                            @if (Auth::user()->status_pengguna != 'ACCOUNTING_ADMIN' && Auth::user()->status_pengguna != 'SUPER_ADMIN') hidden @endif>Chart of Account</a>
                        <a class="collapse-item" href="{{ url('supplier') }}"
                            @if (Auth::user()->status_pengguna != 'SALES_ADMIN' && Auth::user()->status_pengguna != 'SUPER_ADMIN') hidden @endif>Supplier</a>
                        <a class="collapse-item" href="{{ url('pegawai') }}"
                            @if (Auth::user()->status_pengguna != 'SALES_ADMIN' && Auth::user()->status_pengguna != 'SUPER_ADMIN') hidden @endif>Pegawai</a>
                    </div>
                </div>
            </li>
            {{-- Transaction --}}
            <li class="nav-item " @if (Auth::user()->status_pengguna != 'ACCOUNTING_ADMIN' &&
                    Auth::user()->status_pengguna != 'SUPER_ADMIN' &&
                    Auth::user()->status_pengguna != 'SALES_ADMIN') hidden @endif>
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#Transaction"
                    aria-expanded="true" aria-controls="Transaction">
                    <i class="fas fa-hand-holding-usd"></i>
                    <span>Transaction</span>
                </a>
                <div id="Transaction" class="collapse" aria-labelledby="headingUtilities"
                    data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Transaction:</h6>


                        <a class="collapse-item" href="{{ url('quotation') }}">Quotation</a>
                        <a class="collapse-item" href="{{ url('sales') }}">Sales Order</a>
                        <a class="collapse-item" href="{{ url('purchase') }}"
                            @if (Auth::user()->status_pengguna == 'SALES_ADMIN') {{ 'hidden' }} @endif>Purchase Order</a>
                        <a class="collapse-item" href="{{ url('goods') }}"
                            @if (Auth::user()->status_pengguna == 'SALES_ADMIN') {{ 'hidden' }} @endif>Goods Receipt</a>
                        <a class="collapse-item" href="{{ url('delivery') }}">Delivery</a>
                        <a class="collapse-item" href="{{ url('bill') }}"
                            @if (Auth::user()->status_pengguna == 'SALES_ADMIN') {{ 'hidden' }} @endif>Bill Payment</a>
                        <a class="collapse-item" href="{{ url('payment') }}"
                            @if (Auth::user()->status_pengguna == 'SALES_ADMIN') {{ 'hidden' }} @endif>Payment</a>
                        <a class="collapse-item" href="{{ url('paymentvendor') }}"
                            @if (Auth::user()->status_pengguna == 'SALES_ADMIN') {{ 'hidden' }} @endif>Debt Payment </a>
                        <a class="collapse-item" href="{{ url('status_transaksi') }}">Transaction Status </a>



                    </div>
                </div>
            </li>
            {{-- report --}}
            <li class="nav-item" @if (Auth::user()->status_pengguna != 'OWNER' &&
                    Auth::user()->status_pengguna != 'SUPER_ADMIN' &&
                    Auth::user()->status_pengguna != 'ACCOUNTING_ADMIN') hidden @endif>
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#Report"
                    aria-expanded="true" aria-controls="Report">
                    <i class="fas fa-book"></i>
                    <span>Report</span>
                </a>
                <div id="Report" class="collapse" aria-labelledby="headingUtilities"
                    data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Report:</h6>
                        <a class="collapse-item" href="{{ url('journal') }}">Journal</a>
                        <a class="collapse-item" href="{{ url('ledger') }}">General Ledger</a>
                        <a class="collapse-item" href="{{ url('aging') }}">Aging Schedule</a>
                        <a class="collapse-item" href="{{ url('quotationReport') }}">Quotation VS PO
                            Report</a>
                        <a class="collapse-item" href="{{ url('quotationReportDetail') }}">Quotation Detail
                            Report</a>
                        <a class="collapse-item" href="{{ url('customerOmzetReport') }}">Customer Omzet Report</a>
                        <a class="collapse-item" href="{{ url('outStandingReport') }}">Out Standing Report</a>
                        <a class="collapse-item" href="{{ url('SDR') }}">Sales Detail Report</a>
                        <a class="collapse-item" href="{{ url('PCR') }}">Purchase Detail Report</a>
                        <a class="collapse-item" href="{{ url('paymentvendor/report/report') }}">Payment To Vendor
                            Report</a>



                    </div>
                </div>
            </li>



            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>



        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <!-- Topbar Search -->



                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        {{-- strat notif --}}
                        <li class="nav-item dropdown no-arrow mx-1">
                            <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-bell fa-fw"></i>
                                <!-- Counter - Alerts -->
                                <span class="badge badge-danger badge-counter">{{ $notif['length'] }}</span>

                            </a>
                            <!-- Dropdown - Alerts -->
                            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="alertsDropdown">
                                <h6 class="dropdown-header">
                                    Alerts Center
                                </h6>

                                @foreach ($notif['data'] as $n)
                                    @if (substr($n->no_transaksi, 0, 2) == 'PO')
                                        <a class="dropdown-item d-flex align-items-center"
                                            href="{{ url('PCR') }}">
                                            <div class="mr-3">
                                                <div class="icon-circle bg-danger">
                                                    <i class="fas fa-exclamation-triangle text-white"></i>
                                                </div>
                                            </div>
                                            <div>
                                                <div class="small text-gray-500">{{ date('M') . ' ' . date('d') }},
                                                    {{ date('Y') }}</div>
                                                <span class="font-weight-bold">Announcement Notice: You have a
                                                    notification regarding debt {{ $n->no_transaksi }} click for
                                                    more</span>
                                            </div>
                                        </a>
                                    @else
                                        <a class="dropdown-item d-flex align-items-center"
                                            href="{{ url('aging') }}">
                                            <div class="mr-3">
                                                <div class="icon-circle bg-danger">
                                                    <i class="fas fa-exclamation-triangle text-white"></i>
                                                </div>
                                            </div>
                                            <div>
                                                <div class="small text-gray-500">{{ date('M') . ' ' . date('d') }},
                                                    {{ date('Y') }}</div>
                                                <span class="font-weight-bold">Announcement Notice: You have a
                                                    notification regarding recivable {{ $n->no_transaksi }} click for
                                                    more</span>
                                            </div>
                                        </a>
                                    @endif
                                @endforeach

                                {{-- <a class="dropdown-item text-center small text-gray-500" href="#">Show All Alerts</a> --}}
                            </div>
                        </li>

                        {{-- end notif --}}


                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span
                                    class="mr-2 d-none d-lg-inline text-gray-600 small">{{ Auth::user()->nama_pengguna }}</span>
                                <img class="img-profile rounded-circle"
                                    src="{{ asset('assets/') }}/img/undraw_profile.svg">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="logout" data-toggle="modal"
                                    data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>

                    </ul>

                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                @yield('content')
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Prosamagi</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="logout">Logout</a>
                </div>
            </div>
        </div>
    </div>



    <!-- Bootstrap core JavaScript-->
    <script src="{{ asset('assets/') }}/vendor/jquery/jquery.min.js"></script>
    <script src="{{ asset('assets/') }}/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="{{ asset('assets/') }}/vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="{{ asset('assets/') }}/js/sb-admin-2.min.js"></script>

    {{-- <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.js"></script> --}}







</body>

</html>
