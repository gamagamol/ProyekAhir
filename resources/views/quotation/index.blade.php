@extends('template.index')
@section('content')
    <div class="container">
        @if (session()->has('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif
        <div class="card shadow mb-4">
            <div class="card-header py-3 mt-2">
                <h6 class="m-0 font-weight-bold text-primary">Quotation</h6>
            </div>


            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 d-flex justify-content-start">

                        <a href="{{ url('quotation/create') }}" class="btn btn-primary ml-1 mt-3 mb-3"> <i
                                class="fas fa-plus-circle me-1  " style="letter-spacing: 2px"></i> Add Item</a>
                    </div>

                    <div class="col-md-6 d-flex justify-content-end">

                        <button type="button" class="btn btn-success ml-1 mt-3 mb-3" id="btn-import">
                            <i class="fas fa-file-excel" style="letter-spacing: 2px"></i> Import
                        </button>
                    </div>
                </div>
                <div class="table-responsive text-center">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <td>No</td>
                                <td>Date</td>
                                <td>No Quotation </td>
                                <td>Nomor Transaction</td>
                                <td>Customer</td>
                                <td>Prepared</td>
                                <td>Sales</td>
                                <td>Action</td>
                                <td>Document</td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1; ?>
                            @foreach ($data as $d)
                                <tr>
                                    <td> {{ $loop->iteration }}</td>
                                    <td style="min-width:120px">{{ $d->tgl_penawaran }}</td>
                                    <td>{{ $d->no_penawaran }}</td>
                                    <td>{{ $d->nomor_transaksi }}</td>

                                    <td>{{ $d->nama_pelanggan }}</td>
                                    <td>{{ $d->nama_pengguna }}</td>
                                    <td>{{ $d->nama_pegawai }}</td>
                                    <td>


                                        <a href="{{ url('sales', $d->kode_transaksi) }}" class="btn btn-primary mt-1"
                                            @if ($d->status_transaksi != 'quotation') {{ 'hidden' }} @endif>
                                            Sales </a>

                                        <a href="{{ url('quotation', $d->kode_transaksi) }}" class="btn btn-info mt-1">
                                            Detail </a>
                                    </td>
                                    <td>
                                        <a href={{ url('quotation/print', $d->kode_transaksi) }} class="btn btn-primary">
                                            Print</a>
                                    </td>

                                </tr>
                            @endforeach
                        </tbody>

                    </table>
                    {{-- {{ $data->links() }} --}}
                </div>
            </div>
        </div>
    </div>


    <div id="modal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Import</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                {{-- <form action="/quotation/import" method="post" enctype="multipart/form-data"> --}}
                <div class="modal-body">

                    @csrf
                    <input type="file" class="form form-control" name="quotation-import" id="quotation-import">
                    {{-- <input type="text" name="asd"> --}}
                    <a href="assets/template_import/import_quotation_format.xlsx" download>
                        <u>
                            <i>click link for download Import File</i>
                        </u>
                    </a>


                    <div class="row mt-2" id="row-error-note" hidden>
                        <div class="col">
                            <h6>Error Note:</h6>
                            <div id="error_note">

                            </div>
                        </div>
                    </div>


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="btn-import-submit">Submit</button>
                </div>
                {{-- </form> --}}
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            let table = new DataTable('#dataTable', {
                "scrollY": "300px",
                "scrollX": "300px",
            });

            $("#btn-import").click(() => {
                $("#modal").modal("show")
            })



            $("#btn-import-submit").click(() => {
                var formData = new FormData();
                formData.append('quotation-import', $('#quotation-import')[0].files[0]);




                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name=csrf-token]').attr('content')
                    },
                    url: '/quotation/import', // Replace with your server-side endpoint
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {

                        if (response.message == "fail") {

                            let html = '<ul>'
                            for (let i = 0; i < response.errors.length; i++) {
                                html += `<li>${response.errors[i]}</li>`
                            }
                            html += '</ul>'
                            $("#row-error-note").removeAttr('hidden');
                            $("#error_note").html(html)
                        } else {
                            window.location.href = "/quotation/create"
                        }


                    },



                });
            })

        })
    </script>
@endsection()
