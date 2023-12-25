@extends('template.index')
@section('content')
    <div class="container  ">
        <div class="card shadow mb-4 ml-4 mr-4">
            <div class="card-header py-3 mb-2 ">
                <h6 class="m-0 font-weight-bold text-primary">Data Custumor</h6>
            </div>
            <div class="container mt-2">
                <form action="{{ url('custumor') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col" hidden>
                            <div class="form-group mt-2 rounded">
                                <label for="example1" class="mt-2">Code Company</label>
                                <input type="TEXT" class="form-control @error('id_pelanggan') is-invalid @enderror "
                                    name="id_pelanggan" value="{{ $id_pelanggan }}">
                                @error('id_pelanggan')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="form-group mt-2 ">
                                <label for="example1" class="mt-2">Name Company</label>
                                <input type="TEXT" class="form-control @error('nama_pelanggan') is-invalid @enderror"
                                    name="nama_pelanggan" value="{{ old('nam_pelanggan') }}">
                                @error('nama_pelanggan')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col">
                            <div class="form-group mt-2 ">
                                <label for="example1" class="mt-2">Company Side</label>
                                <input type="TEXT" class="form-control @error('perwakilan') is-invalid @enderror"
                                    name="perwakilan" value="{{ old('perwakilan') }}">
                                @error('perwakilan')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col">
                            <div class="form-group mt-2 ">
                                <label for="example1" class="mt-2">Email</label>
                                <input type="TEXT" class="form-control @error('email') is-invalid @enderror"
                                    name="email" value="{{ old('email') }}">
                                @error('email')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col">
                            <div class="form-group mt-2 ">
                                <label for="example1" class="mt-2">contact</label>
                                <input type="TEXT" class="form-control @error('contact') is-invalid @enderror"
                                    name="contact" value="{{ old('contact') }}">
                                @error('contact')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col">
                            <div class="form-group mt-2 ">
                                <label for="example1" class="mt-2">City Company</label>
                                <input type="TEXT" class="form-control @error('kota') is-invalid @enderror"
                                    name="kota" value="{{ old('kota') }}">
                                @error('kota')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="form-group mt-2 ">
                                <label for="example1" class="mt-2">NB</label>
                                <input type="TEXT" class="form-control @error('nb') is-invalid @enderror" name="nb"
                                    value="{{ old('nb') }}">
                                @error('nb')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="form-group mt-2 ">
                                <label for="example1" class="mt-2">NPWP</label>
                                <input type="TEXT" class="form-control @error('npwp') is-invalid @enderror"
                                    name="npwp" value="{{ old('npwp') }}">
                                @error('npwp')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="form-group mt-2 ">
                                <label for="example1" class="mt-2">Term Of Payment</label>
                                <select class="form-control @error('top') is-invalid @enderror" name="top">
                                    @foreach ($top as $t)
                                        <option value="{{ $t }}">{{ $t }}</option>
                                    @endforeach
                                </select>
                                @error('top')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>



                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="exampleFormControlTextarea1">Note Request Customer</label>
                                <textarea class="form-control @error('note_khusus') is-invalid @enderror" id="exampleFormControlTextarea1"
                                    rows="3" name="note_khusus" value="{{ old('note_khusus') }}"></textarea>
                                @error('note_khusus')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="exampleFormControlTextarea1">NPWP Address</label>
                                <textarea class="form-control @error('alamat_npwp') is-invalid @enderror" id="exampleFormControlTextarea1"
                                    rows="3" name="alamat_npwp" value="{{ old('alamat_npwp') }}"></textarea>
                                @error('alamat_npwp')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="exampleFormControlTextarea1">Company Address</label>
                                <textarea class="form-control @error('alamat_pelanggan') is-invalid @enderror" id="exampleFormControlTextarea1"
                                    rows="3" name="alamat_pelanggan" value="{{ old('alamat_pelanggan') }}"></textarea>
                                @error('alamat_pelanggan')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <button type=submit name=submit class="btn btn-primary mt-2 mb-4">Submit</button> <a
                        href="{{ url('custumor') }}" class="btn btn-primary mt-2 mb-4">Back</a>
                </form>
            </div>
        </div>
    </div>
@endsection
