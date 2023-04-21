@extends('template.index')
@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">




        <!-- Content Row -->
        <div class="row justify-content-center">

            <!-- Earnings (Monthly) Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Sales ( {{ date('Y') }} )</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ 'Rp.' . number_format($sales) }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Payable ( {{ date('Y') }} )</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ 'Rp.' . number_format($payable) }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Earnings (Monthly) Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Receivables ({{ date('Y') }})</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ 'Rp.' . number_format($recivable) }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-calendar fa-2x text-gray-300"></i>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Earnings (Monthly) Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Payment
                                </div>
                                <div class="row no-gutters align-items-center">
                                    <div class="col-auto">
                                        <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">{{ "$tagihan%" }}</div>
                                    </div>
                                    <div class="col-auto text-center">
                                        <div class="progress progress-sm mr-2">
                                            <div class="progress-bar bg-info" role="progressbar"
                                                style={{ 'width:' . $tagihan . 'px' }}></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </div>

        <!-- Content Row -->

        {{-- <div class="row">

            <!-- Area Chart -->
            <div class="col">
                <div class="card shadow mb-4">
                    <!-- Card Header - Dropdown -->
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Sales</h6>
                        <div class="dropdown no-arrow">
                        </div>
                    </div>
                    <!-- Card Body -->
                    <div class="card-body">
                        <div class="chart-area">
                            <canvas id="myAreaChart"></canvas>
                        </div>
                    </div>
                </div>
            </div> --}}

    </div>

    <!-- Content Row -->
    <div class="container">

        <div class="row">

            <!-- Content Column -->
            <div class="col-lg mb-4">

                <!-- Project Card Example -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Transaction</h6>
                    </div>
                    <div class="card-body">

                        @if (is_null($grafik))
                            <h4 class="small font-weight-bold">Quotation Order <span
                                    class="float-right">{{ 0 . '%' }}</span></h4>
                            <div class="progress mb-4">
                                <div class="progress-bar bg-danger" role="progressbar" style={{ 'width:0%' }}
                                    aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        @else
                            <h4 class="small font-weight-bold">Quotation <span
                                    class="float-right">{{ $grafik->quotation . '%' }}</span></h4>
                            <div class="progress mb-4">
                                <div class="progress-bar bg-danger" role="progressbar"
                                    style={{ "width:$grafik->quotation%" }} aria-valuenow="20" aria-valuemin="0"
                                    aria-valuemax="100"></div>
                            </div>
                        @endif

                        @if (is_null($grafik))
                            <h4 class="small font-weight-bold">Sales <span class="float-right">{{ 0 . '%' }}</span>
                            </h4>
                            <div class="progress mb-4">
                                <div class="progress-bar bg-warning" role="progressbar" style={{ 'width:0%' }}
                                    aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        @else
                            <h4 class="small font-weight-bold">Sales <span
                                    class="float-right">{{ $grafik->sales . '%' }}</span></h4>
                            <div class="progress mb-4">
                                <div class="progress-bar bg-warning" role="progressbar" style={{ "width:$grafik->sales%" }}
                                    aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        @endif

                        @if (is_null($grafik))
                            <h4 class="small font-weight-bold">Purchase <span
                                    class="float-right">{{ 0 . '%' }}</span></h4>
                            <div class="progress mb-4">
                                <div class="progress-bar" role="progressbar" style={{ 'width:0%' }} aria-valuenow="60"
                                    aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        @else
                            <h4 class="small font-weight-bold">Purchase <span
                                    class="float-right">{{ $grafik->purchase . '%' }}</span></h4>
                            <div class="progress mb-4">
                                <div class="progress-bar" role="progressbar" style={{ "width:$grafik->purchase%" }}
                                    aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        @endif
                        @if (is_null($grafik))
                            <h4 class="small font-weight-bold">Goods Receipt <span
                                    class="float-right">{{ 0 . '%' }}</span></h4>
                            <div class="progress mb-4">
                                <div class="progress-bar bg-info" role="progressbar" style={{ 'width:0%' }}
                                    aria-valuenow="80" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        @else
                            <h4 class="small font-weight-bold">Goods Receipt <span
                                    class="float-right">{{ $grafik->goods . '%' }}</span></h4>
                            <div class="progress mb-4">
                                <div class="progress-bar bg-info" role="progressbar" style={{ "width:$grafik->goods%" }}
                                    aria-valuenow="80" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        @endif

                        @if (is_null($grafik))
                            <h4 class="small font-weight-bold">Delivery <span
                                    class="float-right">{{ 0 . '%' }}</span></h4>
                            <div class="progress mb-4">
                                <div class="progress-bar bg-info" role="progressbar" style={{ 'width:0%' }}
                                    aria-valuenow="80" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        @else
                            <h4 class="small font-weight-bold">Delivery <span
                                    class="float-right">{{ $grafik->delivery . '%' }}</span></h4>
                            <div class="progress mb-4">
                                <div class="progress-bar bg-info" role="progressbar"
                                    style={{ "width:$grafik->delivery%" }} aria-valuenow="80" aria-valuemin="0"
                                    aria-valuemax="100"></div>
                            </div>
                        @endif

                        @if (is_null($grafik))
                            <h4 class="small font-weight-bold">Bill Payment <span
                                    class="float-right">{{ 0 . '%' }}</span></h4>
                            <div class="progress mb-4">
                                <div class="progress-bar bg-info" role="progressbar" style={{ 'width:0%' }}
                                    aria-valuenow="80" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        @else
                            <h4 class="small font-weight-bold">Bill Payment <span
                                    class="float-right">{{ $grafik->bill . '%' }}</span></h4>
                            <div class="progress mb-4">
                                <div class="progress-bar bg-info" role="progressbar" style={{ "width:$grafik->bill%" }}
                                    aria-valuenow="80" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        @endif
                        @if (is_null($grafik))
                            <h4 class="small font-weight-bold">Payment <span
                                    class="float-right">{{ 0 . '%' }}</span>
                            </h4>
                            <div class="progress">
                                <div class="progress-bar bg-success" role="progressbar" style={{ 'width:0%' }}
                                    aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        @else
                            <h4 class="small font-weight-bold">Payment <span
                                    class="float-right">{{ $grafik->payment . '%' }}</span>
                            </h4>
                            <div class="progress">
                                <div class="progress-bar bg-success" role="progressbar"
                                    style={{ "width:$grafik->payment%" }} aria-valuenow="100" aria-valuemin="0"
                                    aria-valuemax="100"></div>
                            </div>
                        @endif

                    </div>
                </div>



            </div>


        </div>

    </div>
    </div>

    <!-- /.container-fluid -->

    </div>
    <!-- End of Main Content -->



    </div>
    <!-- End of Content Wrapper -->

    
@endsection
