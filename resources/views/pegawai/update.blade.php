@extends('template/index')
@section('content')
    <div class="container  ">
        <div class="card shadow mb-4 ml-4 mr-4">
            <div class="card-header py-3 mb-2 ">
                <h6 class="m-0 font-weight-bold text-primary">Ubah Master Data Pegawai</h6>
            </div>
            <div class="container mt-2">
                <form action="{{ url('pegawai/update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col">
                            <input type="hidden" name="id" value="{{ $data->id_pegawai }} "">
                            <div class="form-group mt-2 rounded">
                                <label for="example1" class="mt-2">Kode Pegawai</label>
                                <input type="TEXT" class="form-control @error('kode_pegawai') is-invalid @enderror "
                                    name="kode_pegawai" value="{{ $data->kode_pegawai }}" style="text-transform:uppercase" readonly>
                                @error('kode_pegawai')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="form-group mt-2 rounded">
                                <label for="example1" class="mt-2">Nama Pegawai</label>
                                <input type="TEXT" class="form-control @error('nama_pegawai') is-invalid @enderror "
                                    name="nama_pegawai" value="{{ $data->nama_pegawai }}">
                                @error('nama_pegawai')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="form-group mt-2 rounded">
                                <label for="example1" class="mt-2">Jabatan Pegawai</label>
                                <input type="TEXT" class="form-control @error('jabatan_pegawai') is-invalid @enderror "
                                    name="jabatan_pegawai" value="{{ $data->nama_pegawai  }}"
                                    style="text-transform:uppercase">
                                @error('jabatan_pegawai')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <button type=submit name=submit class="btn btn-primary mt-2 mb-4">Submit</button>
                    <a href="{{ url('pegawai') }}" class="btn btn-primary mt-2 mb-4">Back</a>
                </form>
            </div>
        </div>
    </div>
@endsection
