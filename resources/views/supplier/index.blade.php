@extends('template.index')
@section('content')
    @if (session()->has('success'))
        <div class="alert alert-success" role="alert">
            {{ session('success') }}
        </div>
    @endif

    <div class="container">
        <div class="card shadow mb-4">
            <div class="card-header py-3 mt-2">
                <h6 class="m-0 font-weight-bold text-primary">Supplier</h6>
            </div>



            <div class="card-body">
                <a href="{{ url('supplier/create') }}" class="btn btn-primary ml-1 mt-3 mb-3"> <i
                        class="fas fa-plus-circle me-1  " style="letter-spacing: 2px"></i> Add Item</a>
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>

                            <tr>
                                <td>No</td>
                                <td>Name Company</td>
                                <td>Company Side</td>
                                <td>Company's Address</td>
                                <td>Action</td>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $d)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $d->nama_pemasok }}</td>
                                    <td>{{ $d->perwakilan_pemasok }}</td>
                                    <td>{{ $d->alamat_pemasok }}</td>
                                    <td><a href="{{ url('supplier', $d->id_pemasok) }}" class="btn btn-warning">Change</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>

                    </table>

                </div>
            </div>
        </div>
    </div>
    <script>
        let datatable = new DataTable("#dataTable", {
            "scrollY": "300px",
            "scrollX": "300px",
        })
    </script>
@endsection()
