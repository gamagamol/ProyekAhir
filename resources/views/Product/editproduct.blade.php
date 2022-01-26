@extends('template.index')
@section('content')
<div class="container ">
    <div class="card shadow mb-4 ml-4 mr-4">
    <div class="card-header py-3 mb-2 ">
        <h6 class="m-0 font-weight-bold text-primary">Data Product</h6>
    </div>
    <div class="container mt-2">
        <form action="{{ url('product',$data->id_produk) }}" method="POST" enctype="multipart/form-data">

            @csrf
            @method('put')
            <input type="text" name="id_produk" value="{{ $data->id_produk }}" hidden>
            <div class="form-group mt-2 ">
                <label for="example1" class="mt-2">Name Product</label>
                <input type="TEXT" class="form-control  @error('nama_produk') is-invalid @enderror " name="nama_produk" value="{{ $data->nama_produk }}" >
                @error('nama_produk')
                 <div class="invalid-feedback">
                     {{ $message }}
                 </div>
                 @enderror
            </div>
            <div class="form-group">
                <label for="example1" class="mt-2">Type Product</label>
              
                <select class="form-control" aria-label="Default select example" name="jenis_produk" id="example1">
                    <option value="MACHINERY STEEL" selected>MACHINERY STEEL</option>
                    <option value="COLD WORK">COLD WORK</option>
                    <option value="HOT WORK">HOT WORK</option>
                    <option value="PLASTIC MOLD">PLASTIC MOLD</option>
                  </select>   
            </div>
            <div class="form-group">
                <label for="example1" class="mt-2">Form Product</label>
              
                <select class="form-control" aria-label="Default select example" name="bentuk_produk" id="example1">
                    <option value="FLAT" selected>Flat</option>
                    <option value="CYLINDER">Cylinder</option>
                  </select>               
            </div>

            <button type=submit name=submit class="btn btn-primary mt-2 mb-4">Submit</button> <a href="{{ url('product') }}" class="btn btn-primary mt-2 mb-4">Back</a>
        </form>
    </div>
</div>
</div>
@endsection