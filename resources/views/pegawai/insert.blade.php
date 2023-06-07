@extends('template/index')
@section('content')
    <div class="container  ">
        <div class="card shadow mb-4 ml-4 mr-4">
            <div class="card-header py-3 mb-2 ">
                <h6 class="m-0 font-weight-bold text-primary">Tambah Master Data Pegawai</h6>
            </div>
            <div class="container mt-2">
                <form action="{{ url('pegawai') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col">
                            <div class="form-group mt-2 rounded">
                                <label for="example1" class="mt-2">Kode Pegawai</label>
                                <input type="TEXT" class="form-control @error('kode_pegawai') is-invalid @enderror "
                                    name="kode_pegawai" value="{{ old('kode_pegawai') }}" style="text-transform:uppercase">
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
                                    name="nama_pegawai" value="{{ old('nama_pegawai') }}">
                                @error('nama_pegawai')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-10">
                            <div class="form-group mt-2 rounded">
                                <label for="example1" class="mt-2">Jabatan Pegawai</label>

                                <input type="TEXT"
                                    class="form-control @error('jabatan_pegawai_input') is-invalid @enderror "
                                    name="jabatan_pegawai_input" value="{{ old('jabatan_pegawai_input') }}"
                                    style="text-transform:uppercase" id="jabatan_pegawai_input" hidden>

                                <select name="jabatan_pegawai"
                                    class="form-control @error('jabatan_pegawai_input') is-invalid @enderror " id="position">
                                    @foreach ($position as $p)
                                        <option value="{{ $p->jabatan_pegawai }}"  > {{ strtolower($p->jabatan_pegawai) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('jabatan_pegawai_input')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-2 d-flex justify-content-center align-items-center">
                            <div class="form-check mt-4">
                                <input class="form-check-input" type="checkbox" id="other" name="other"value="other">
                                <label class="form-check-label">New Position</label>
                            </div>
                        </div>
                    </div>




                    <button type=submit name=submit class="btn btn-primary mt-2 mb-4">Submit</button>
                    <a href="{{ url('pegawai') }}" class="btn btn-primary mt-2 mb-4">Back</a>
                </form>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#other').change(function() {
                if ($('#other').is(':checked')) {
                    $('#jabatan_pegawai_input').removeAttr('hidden')
                    $('#position').attr('hidden', true)
                } else {
                    $('#jabatan_pegawai_input').val('')
                    $('#jabatan_pegawai_input').attr('hidden', true)
                    $('#position').removeAttr('hidden')
                }
            })
        })
    </script>
@endsection
