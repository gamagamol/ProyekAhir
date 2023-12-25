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
                <h6 class="m-0 font-weight-bold text-primary">Customer</h6>
            </div>




            <div class="card-body">
                <a href="{{ url('custumor/create') }}" class="btn btn-primary ml-1 mt-3 mb-3"> <i
                        class="fas fa-plus-circle me-1  " style="letter-spacing: 2px"></i> Add Item</a>
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>

                            <tr>
                                <td>No</td>
                                <td>Name Company</td>
                                <td>City Company</td>
                                <td>Company Side</td>
                                <td>Email Company Side</td>
                                <td>Contact Company Side</td>
                                <td>NB Company </td>
                                <td>NPWP Company </td>
                                <td>NPWP Company Address </td>
                                <td>Company's Address</td>
                                <td>Term Of Payment</td>
                                <td>Note Request Customer</td>
                                <td>Action</td>
                            </tr>
                        </thead>

                        <tbody>

                            @foreach ($data as $d)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $d->nama_pelanggan }}</td>
                                    <td>{{ $d->kota }}</td>
                                    <td>{{ $d->perwakilan }}</td>
                                    <td>{{ $d->email }}</td>
                                    <td>{{ $d->contact }}</td>
                                    <td>{{ $d->nb }}</td>
                                    <td>{{ $d->npwp }}</td>
                                    <td>{{ $d->alamat_npwp }}</td>
                                    <td>{{ $d->alamat_pelanggan }}</td>
                                    <td>{{ $d->top . ' ' . 'Days' }}</td>
                                    <td>{{ $d->note_khusus }}</td>
                                    <td><a href="{{ url('custumor', $d->id_pelanggan) }}"
                                            class="btn btn-warning">Change</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>

                    </table>


                </div>
            </div>
        </div>
    </div>


    <script>
        let datatable = new DataTable("#dataTable", {
            "scrollY": "300px",
            "scrollX": "300px",

        })
    </script>
@endsection()
