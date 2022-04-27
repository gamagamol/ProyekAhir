@extends('template.index')
@section('content')

@if(session()->has('success'))
<div class="alert alert-success" role="alert">
    {{session('success')}}
  </div>
@endif

<div class="container"> 
<div class="card shadow mb-4">
    <div class="card-header py-3 mt-2">
        <h6 class="m-0 font-weight-bold text-primary" >Product</h6>
    </div>
  
        <form action="{{ url('product') }}">
       <div class="form-group col-md-6 ml-2 mt-2">
            <input type="text" name='cari' class="form-control " id="formGroupExampleInput" placeholder="Find Name Your Product" autocomplete="off" autofocus>
        </div>
        <button type=submit name=submit class="btn btn-primary ml-4">submit</button>
      

       </form>
  
   

    <div class="card-body">
        <a href="{{ url('product/create') }}" class="btn btn-primary ml-1 mt-3 mb-3"> <i class="fas fa-plus-circle me-1  " style="letter-spacing: 2px"></i>  Add Item</a>
        <div class="table-responsive">
            <table class="table table-bordered text-center" id="dataTable" width="100%" cellspacing="0">
                <tr>
                    <td>No</td>
                    <td>Name Product</td>
                    <td>Type Product</td>
                    <td>Form Product</td>
                    <td>Action</td>
                </tr>
                @foreach ($data as $d)
                    
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $d->nama_produk }}</td>
                    <td>{{ $d->jenis_produk }}</td>
                    <td>{{ $d->bentuk_produk }}</td>
                   
                    <td ><a href="{{ url('product',$d->id_produk) }}" class="btn btn-warning">Change</a></td>
                </tr>
               
                @endforeach
            </table>
                    {{$data->links() }} 

        </div>
    </div>
</div>
  </div> 
@endsection()


