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
                <h6 class="m-0 font-weight-bold text-primary">Chart Of Account's</h6>
            </div>

            <form action="{{ url('COA') }}" method="get">
                @csrf
                <div class="form-group col-md-6 ml-2 mt-2">
                 <select name="cari" id="" class="form-control">
                    @foreach ($cari as $c )
                        <option value={{$c->kode_akun}}>{{$c->nama_akun}}</option>
                    @endforeach
                 </select>
                </div>
                <button type=submit name=submit class="btn btn-primary ml-4">submit</button>
            </form>



            <div class="card-body">
                <a href="{{ url('COA/create') }}" class="btn btn-primary ml-1 mt-3 mb-3"> <i
                        class="fas fa-plus-circle me-1  " style="letter-spacing: 2px"></i> Add Item</a>
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <tr>
                            <td>No</td>
                            <td>Account Code</td>
                            <td>Account Name</td>
                            <td>Account Header</td>
                        </tr>
                        @foreach ($data as $d)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $d->kode_akun }}</td>
                                <td>{{ $d->nama_akun }}</td>
                                <td>{{ $d->header_akun }}</td>
                                
                            </tr>
                        @endforeach
                    </table>

                    {{ $data->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection()
