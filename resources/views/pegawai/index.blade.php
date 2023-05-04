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
                <h6 class="m-0 font-weight-bold text-primary">{{$tittle}}</h6>
            </div>
            {{-- @dd($data) --}}
            <form action="{{ url('pegawai') }}" method="get">
                @csrf
                <div class="form-group col-md-6 ml-2 mt-2">
                 <select name="cari" id="" class="form-control">
                    @foreach ($data as $pegawai )
                        <option value={{$pegawai->id_pegawai}}>{{$pegawai->nama_pegawai}}</option>
                    @endforeach
                 </select>
                </div>
                <button type=submit name=submit class="btn btn-primary ml-4">submit</button>
            </form>



            <div class="card-body">
                <a href="{{ url('pegawai/create') }}" class="btn btn-primary ml-1 mt-3 mb-3"> <i
                        class="fas fa-plus-circle me-1  " style="letter-spacing: 2px"></i> Add Item</a>
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <tr>
                            <td>No</td>
                            <td>Kode Pegawai</td>
                            <td>Nama Pegawai</td>
                            <td>Jabatan Pegawai</td>
                            <td>Aksi</td>
                        </tr>
                        @foreach ($data as $d)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $d->kode_pegawai }}</td>
                                <td>{{ $d->nama_pegawai }}</td>
                                <td>{{ $d->jabatan_pegawai }}</td>
                                <td>
                                    <a href='{{url("pegawai",$d->id_pegawai)}}' class="btn btn-warning">Edit</a>
                                    <a href='{{url("pegawai/delete/$d->id_pegawai")}}' class="btn btn-danger">Delete</a>
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
