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

            <form action="{{ url('supplier') }}" method="get">
                <div class="form-group col-md-6 ml-2 mt-2">
                    <input type="text" name='cari' class="form-control " id="formGroupExampleInput"
                        placeholder="Find Name Your supplier" autocomplete="off" autofocus>
                </div>
                <button type=submit name=submit class="btn btn-primary ml-4">submit</button>
            </form>



            <div class="card-body">
                <a href="{{ url('supplier/create') }}" class="btn btn-primary ml-1 mt-3 mb-3"> <i
                        class="fas fa-plus-circle me-1  " style="letter-spacing: 2px"></i> Add Item</a>
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <tr>
                            <td>No</td>
                            <td>Name Company</td>
                            <td>Company Side</td>
                            <td>Company's Address</td>
                            <td>Action</td>
                        </tr>
                        @foreach ($data as $d)

                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $d->nama_pemasok }}</td>
                                <td>{{ $d->perwakilan_pemasok}}</td>
                                <td>{{ $d->alamat_pemasok }}</td>
                                <td><a href="{{ url('supplier', $d->id_pemasok) }}" class="btn btn-warning">Change</a>
                                </td>
                            </tr>
                        @endforeach
                    </table>
                    {{ $data->links() }}

                </div>
            </div>
        </div>
    </div>
@endsection()
