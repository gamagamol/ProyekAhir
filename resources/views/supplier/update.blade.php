@extends('template.index')
@section('content')
<div class="container  ">
    <div class="card shadow mb-4 ml-4 mr-4">
    <div class="card-header py-3 mb-2 ">
        <h6 class="m-0 font-weight-bold text-primary">Data Supplier</h6>
    </div>
    <div class="container mt-2">
        <form action="{{ url('supplier/update') }}" method="POST" enctype="multipart/form-data">
           @csrf
           @method('put')
            <div class="row">
                <div class="col">
                    <div class="form-group mt-2 rounded" hidden>
                        <label for="example1" class="mt-2">Code Company</label>
                        <input type="TEXT" class="form-control @error('id_pemasok') is-invalid @enderror " name="id_pemasok" value="{{ $data->id_pemasok }}" >                     
                        @error('id_pemasok') 
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
                        <input type="TEXT" class="form-control @error('nama_pemasok') is-invalid @enderror" name="nama_pemasok" value="{{ $data->nama_pemasok }}">
                        @error('nama_pemasok') 
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
                        <input type="TEXT" class="form-control @error('perwakilan_pemasok') is-invalid @enderror" name="perwakilan_pemasok" value="{{ $data->perwakilan_pemasok }}">
                        @error('perwakilan_pemasok') 
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
                    <textarea class="form-control @error('alamat_pemasok') is-invalid @enderror" id="exampleFormControlTextarea1" rows="3"name="alamat_pemasok" >{{ $data->alamat_pemasok }}</textarea>
                    @error('alamat_pemasok') 
                        <div class="invalid-feedback">
                        {{ $message }}
                        </div> 
                        @enderror
                </div>
                </div>
            </div>
            <button type=submit name=submit class="btn btn-primary mt-2 mb-4">Submit</button> <a href="{{ url('custumor') }}" class="btn btn-primary mt-2 mb-4">Back</a>
        </form>
    </div>
</div>
</div>
@endsection