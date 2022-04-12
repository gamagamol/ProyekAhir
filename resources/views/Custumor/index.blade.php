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
                <h6 class="m-0 font-weight-bold text-primary">Customer</h6>
            </div>

            <form action="{{ url('custumor') }}" method="get">
                <div class="form-group col-md-6 ml-2 mt-2">
                    <input type="text" name='cari' class="form-control " id="formGroupExampleInput"
                        placeholder="Find Name Your Customer" autocomplete="off" autofocus>
                </div>
                <button type=submit name=submit class="btn btn-primary ml-4">submit</button>
            </form>



            <div class="card-body">
                <a href="{{ url('custumor/create') }}" class="btn btn-primary ml-1 mt-3 mb-3"> <i
                        class="fas fa-plus-circle me-1  " style="letter-spacing: 2px"></i> Add Item</a>
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <tr>
                            <td>No</td>
                            <td>Name Company</td>
                            <td>Company Side</td>
                            <td>Email Company Side</td>
                            <td>Company's Address</td>
                            <td>Action</td>
                        </tr>
                        @foreach ($data as $d)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $d->nama_pelanggan }}</td>
                                <td>{{ $d->perwakilan }}</td>
                                <td>{{ $d->email }}</td>
                                <td>{{ $d->alamat_pelanggan }}</td>
                                <td><a href="{{ url('custumor', $d->id_pelanggan) }}" class="btn btn-warning">Change</a>
                                </td>
                            </tr>
                        @endforeach
                    </table>
                    @if ($type == 'true')
                        {{ $data->links() }}
                    @endif

                </div>
            </div>
        </div>
    </div>
@endsection()
