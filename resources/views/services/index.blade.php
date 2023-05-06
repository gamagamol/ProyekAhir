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
                <h6 class="m-0 font-weight-bold text-primary">{{ $tittle }}</h6>
            </div>

            <form action="{{ url('services') }}" method="get">
                @csrf
                <div class="form-group col-md-6 ml-2 mt-2">
                    <select name="cari" id="" class="form-control">
                        @foreach ($services as $c)
                            <option value={{ $c->id_layanan }}>{{ $c->nama_layanan }}</option>
                        @endforeach
                    </select>
                </div>
                <button type=submit name=submit class="btn btn-primary ml-4">submit</button>
            </form>



            <div class="card-body">
                <a href="{{ url('services/create') }}" class="btn btn-primary ml-1 mt-3 mb-3"> <i
                        class="fas fa-plus-circle me-1  " style="letter-spacing: 2px"></i> Add Item</a>
                <table class="table table-bordered text-center" cellspacing="0">
                    <tr>
                        <td>No</td>
                        <td>Services Name</td>
                        <td>calulation</td>
                        <td>Action</td>
                    </tr>
                    @foreach ($data as $d)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $d->nama_layanan }}</td>
                            <td>{{ number_format($d->perhitungan, strlen((string) $d->perhitungan), ',') }}</td>
                            <td><a href={{ route('services.show', $d->id_layanan) }} class="btn btn-warning">Update</a>
                            </td>

                        </tr>
                    @endforeach
                </table>

                {{ $data->links() }}
            </div>
        </div>
    </div>
@endsection()
