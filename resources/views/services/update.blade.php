@extends('template/index')
@section('content')
<div class="container  ">
    <div class="card shadow mb-4 ml-4 mr-4">
    <div class="card-header py-3 mb-2 ">
        <h6 class="m-0 font-weight-bold text-primary">{{$tittle}}</h6>
    </div>
    <div class="container mt-2">
        <form action="{{ route('services.update',$data['id_layanan']) }}" method="POST" enctype="multipart/form-data">
           @csrf
           @method('PUT')
         
            <div class="row">
               <input type="text" name="id_layanan" value= {{$data['id_layanan']}} hidden>
                  <div class="col">
                    <div class="form-group mt-2 ">
                        <label for="example1" class="mt-2">Services Name</label>
                        <input type="TEXT" class="form-control @error('nama_layanan')is-invalid @enderror" name='nama_layanan' value="{{$data['nama_layanan']}}">
                        @error('nama_layanan')
                        <div class="invalid-feedback">
                          {{$message}}      
                      </div>  
                      @enderror
                    </div>
                </div>
            </div>

         
            <button type=submit name=submit class="btn btn-primary mt-2 mb-4">Submit</button> 
            <a href="{{url('services')}}" class="btn btn-primary mt-2 mb-4">Back</a>
        </form>
    </div>
</div>
</div>
@endsection