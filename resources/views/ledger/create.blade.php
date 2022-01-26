@extends('template.index')
@section('content')

    <!-- Begin Page Content -->
    <div class="container">


        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">General Ledger</h6>
            </div>
            <div class="card-body">
                <div class="container mt-2">
                    <?php $tgl = explode('-', date('Y-m-d')); ?>
                    <h3 class="text-center">PT.Ibaraki Kogyo Hanan Indonesia</h3>
                    <h4 class="text-center">General Ledger</h4>
                    <h5 class="text-center">{{ "Periode $tgl[0] Month $tgl[1]" }}</h5>
                    <br>
                    <form action={{ url('ledger') }} method="post">
                        @csrf
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="kode_akun">Name Account</label>
                                    <select class="form-control @error('kode_akun') is-invalid @enderror"
                                        id="kode_akun" name="kode_akun" onchange="myfunction()">
                                        <option value={{Null}}>Select Account</option>
                                        @foreach ($account as $c)
                                            <option value="{{ $c->kode_akun }}">{{ $c->nama_akun }}</option>
                                        @endforeach
                                    </select>
                                    @error('kode_akun')
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
                                    <label for="example1" class="mt-2"> Start Date </label>
                                    <input type="date" class="form-control @error('tgl1') is-invalid @enderror " name="tgl1">
                                     @error('tgl1')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group mt-2 rounded">
                                    <label for="example1" class="mt-2">Finish Date </label>
                                    <input type="date" class="form-control @error('tgl2') is-invalid @enderror " name="tgl2">
                                     @error('tgl2')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                        </div>
                        <button type=submit name=submit class="btn btn-primary mt-2 mb-4">Submit</button> <a
                            href="{{ url('ledger') }}" class="btn btn-primary mt-2 mb-4">Back</a>
                    </form>
                </div>
            </div>

        </div>
    </div>
@endsection()
