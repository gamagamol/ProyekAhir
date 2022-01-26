@extends('template/index')
@section('content')
<div class="container  ">
    <div class="card shadow mb-4 ml-4 mr-4">
    <div class="card-header py-3 mb-2 ">
        <h6 class="m-0 font-weight-bold text-primary">Add Chart Of Account</h6>
    </div>
    <div class="container mt-2">
        <form action="{{ url('COA') }}" method="POST" enctype="multipart/form-data">
           @csrf
            <div class="row">
                <div class="col">
                    <div class="form-group mt-2 rounded">
                        <label for="example1" class="mt-2">Code COA</label>
                        <input type="TEXT" class="form-control @error('kode_akun') is-invalid @enderror " name="kode_akun" value="{{old('kode_akun')}}">
                        @error('kode_akun')
                          <div class="invalid-feedback">
                            {{$message}}      
                        </div>  
                        @enderror
                    </div>
                </div>
              
            </div>
            <div class="row">
                  <div class="col">
                    <div class="form-group mt-2 ">
                        <label for="example1" class="mt-2">Name COA</label>
                        <input type="TEXT" class="form-control @error('nama_akun')is-invalid @enderror" name='nama_akun' value="{{old('nama_akun')}}">
                        @error('nama_akun')
                        <div class="invalid-feedback">
                          {{$message}}      
                      </div>  
                      @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <div class="form-group mt-2 ">
                        <label for="example1" class="mt-2">Header COA</label>
                        <input type="TEXT" class="form-control @error('header_akun')is-invalid @enderror" name="header_akun" value="{{old('header_akun')}}">
                        @error('header_akun')
                        <div class="invalid-feedback">
                          {{$message}}      
                      </div>  
                      @enderror
                    </div>
                </div>                
            </div>
            <button type=submit name=submit class="btn btn-primary mt-2 mb-4">Submit</button> 
            <a href="{{url('COA')}}" class="btn btn-primary mt-2 mb-4">Back</a>
        </form>
    </div>
</div>
</div>
@endsection