@extends('template.index')
@section('content')
    {{-- @dump($data) --}}

    <style>
        .tab-content {
            border-left: 1px solid #ddd;
            padding-left: 15px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 15px;
        }
    </style>
    @if (session()->has('success'))
        <div class="alert alert-success" role="alert">
            {{ session('success') }}
        </div>
    @else
        <div class="alert alert-failed" role="alert">
            {{ session('failed') }}
        </div>
    @endif





    <div class="container  ">
        <div class="card shadow mb-4 ml-4 mr-4">
            <div class="card-header py-3 mb-2 ">
                <h6 class="m-0 font-weight-bold text-primary">Show Data Purchase</h6>
            </div>


            <div class="container mt-4">
                <ul class="nav nav-tabs" id="myTabs">
                    <li class="nav-item">
                        <a class="nav-link active" id="tab1-tab" data-toggle="tab" href="#tab1">Goods</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="tab2-tab" data-toggle="tab" href="#tab2">Service</a>
                    </li>

                </ul>

                <div class="tab-content mt-2">
                    <div class="tab-pane fade show active" id="tab1">
                        <div id="table_atas">
                            <div class="table-responsive text-center mt-2">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <tr>
                                        <td colspan="8">Quotation</td>
                                        <td colspan="14">Purchase</td>
                                    </tr>
                                    <tr>
                                        <td>Date</td>
                                        <td>No Transaction</td>
                                        <td>Job number</td>
                                        <td>Grade</td>
                                        <td colspan="3">Material Size</td>
                                        <td>QTY</td>
                                        <td>Grade</td>
                                        <td colspan="3">Material Size</td>
                                        <td>QTY</td>
                                        <td>Weight(Kg)</td>
                                        <td>Unit Price</td>
                                        <td>Amount</td>
                                        <td>VAT 11%</td>
                                        <td>Total Amount</td>
                                        <td>Processing</td>
                                        <td>Custumor</td>
                                        <td>Supplier</td>
                                        <td>Delete</td>

                                    </tr>
                                    <?php
                                    
                                    $i = 1;
                                    $subtotal = 0;
                                    $ppn = 0;
                                    $total = 0;
                                    ?>
                                    @foreach ($data as $p)
                                        @if ($p->type == 1)
                                            <tr id="row-{{ $p->id_pembelian }}">

                                                <td style="min-width:120px">
                                                    {{ $p->tgl_pembelian }}
                                                </td>
                                                <td>
                                                    {{ $p->no_pembelian }}
                                                </td>
                                                <td>
                                                    {{ $p->nomor_pekerjaan }}
                                                </td>
                                                <td>
                                                    {{ $p->nama_produk }}
                                                </td>
                                                <td>
                                                    {{ $p->tebal_penawaran }}
                                                </td>
                                                <td>
                                                    {{ $p->lebar_penawaran }}
                                                </td>
                                                <td>
                                                    {{ $p->panjang_penawaran }}
                                                </td>
                                                <td>
                                                    {{ $p->jumlah }}
                                                </td>

                                                <td>
                                                    {{ $p->nama_produk }}
                                                </td>


                                                <td>
                                                    {{ $p->tebal_detail_pembelian > 0 ? $p->tebal_detail_pembelian : $p->tebal_penawaran }}
                                                </td>
                                                <td>
                                                    {{ $p->lebar_detail_pembelian > 0 ? $p->lebar_detail_pembelian : $p->lebar_penawaran }}
                                                </td>

                                                <td>
                                                    {{ $p->panjang_detail_pembelian > 0 ? $p->panjang_detail_pembelian : $p->panjang_penawaran }}
                                                </td>
                                                <td>
                                                    {{ $p->jumlah_detail_pembelian }}
                                                </td>
                                                <td>
                                                    {{ $p->berat_detail_pembelian }}
                                                </td>
                                                <td>
                                                    {{ 'Rp' . number_format($p->harga_detail_pembelian) }}
                                                </td>

                                                <td>
                                                    {{ 'Rp' . number_format($p->subtotal_detail_pembelian) }}
                                                </td>
                                                <td>
                                                    {{ 'Rp' . number_format($p->ppn_detail_pembelian) }}

                                                </td>
                                                <td>
                                                    {{ 'Rp' . number_format($p->total_detail_pembelian) }}
                                                </td>
                                                <td>
                                                    {{ $p->layanan }}
                                                </td>
                                                <td>
                                                    {{ $p->nama_pelanggan }}
                                                </td>
                                                <td>
                                                    {{ $p->nama_pemasok }}
                                                </td>

                                                <td {{ $p->status_transaksi != 'purchase' ? 'hidden' : '' }}>
                                                    <i class="fa fa-trash" aria-hidden="true" style="color:red"
                                                        onclick="deleteItem({{ $p->id_pembelian }},'row-{{ $p->id_pembelian }}')"></i>
                                                </td>

                                            </tr>
                                            <?php
                                            $subtotal += $p->subtotal_detail_pembelian;
                                            $ppn += $p->ppn_detail_pembelian;
                                            $total += $p->total_detail_pembelian;
                                            
                                            ?>
                                        @endif
                                    @endforeach

                                    <tr>
                                        <td colspan='15'>TOTAL</td>
                                        <td>{{ 'Rp.' . number_format($subtotal) }}</td>
                                        <td>{{ 'Rp.' . number_format($ppn) }}</td>
                                        <td>{{ 'Rp.' . number_format($total + $data[0]->ongkir) }}</td>
                                    </tr>

                                </table>
                            </div>




                        </div>
                    </div>
                    <div class="tab-pane fade" id="tab2">

                        <div class="table-responsive text-center mt-2">
                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                <tr>
                                    <td colspan="8">Quotation</td>
                                    <td colspan="14">Purchase</td>
                                </tr>
                                <tr>
                                    <td>Date</td>
                                    <td>No Transaction</td>
                                    <td>Job number</td>
                                    <td>Grade</td>
                                    <td colspan="3">Material Size</td>
                                    <td>QTY</td>
                                    <td>Grade</td>
                                    <td colspan="3">Material Size</td>
                                    <td>QTY</td>
                                    <td>Weight(Kg)</td>
                                    <td>Unit Price</td>
                                    <td>Amount</td>
                                    <td>VAT 11%</td>
                                    <td>VAT 2%</td>
                                    <td>Total Amount</td>
                                    <td>Processing</td>
                                    <td>Custumor</td>
                                    <td>Supplier</td>
                                    <td>Delete</td>

                                </tr>
                                <?php
                                
                                $i = 1;
                                $subtotal = 0;
                                $ppn = 0;
                                $ppn12 = 0;
                                $total = 0;
                                ?>
                                @foreach ($data as $p)
                                    @if ($p->type == 2)
                                        <tr id="row-{{ $p->id_pembelian }}">

                                            <td style="min-width:120px">
                                                {{ $p->tgl_pembelian }}
                                            </td>
                                            <td>
                                                {{ $p->no_pembelian }}
                                            </td>
                                            <td>
                                                {{ $p->nomor_pekerjaan }}
                                            </td>
                                            <td>
                                                {{ $p->nama_produk }}
                                            </td>
                                            <td>
                                                {{ $p->tebal_penawaran }}
                                            </td>
                                            <td>
                                                {{ $p->lebar_penawaran }}
                                            </td>
                                            <td>
                                                {{ $p->panjang_penawaran }}
                                            </td>
                                            <td>
                                                {{ $p->jumlah }}
                                            </td>

                                            <td>
                                                {{ $p->nama_produk }}
                                            </td>


                                            <td>
                                                {{ $p->tebal_detail_pembelian > 0 ? $p->tebal_detail_pembelian : $p->tebal_penawaran }}
                                            </td>
                                            <td>
                                                {{ $p->lebar_detail_pembelian > 0 ? $p->lebar_detail_pembelian : $p->lebar_penawaran }}
                                            </td>

                                            <td>
                                                {{ $p->panjang_detail_pembelian > 0 ? $p->panjang_detail_pembelian : $p->panjang_penawaran }}
                                            </td>
                                            <td>
                                                {{ $p->jumlah_detail_pembelian }}
                                            </td>
                                            <td>
                                                {{ $p->berat_detail_pembelian }}
                                            </td>
                                            <td>
                                                {{ 'Rp' . number_format($p->harga_detail_pembelian) }}
                                            </td>

                                            <td>
                                                {{ 'Rp' . number_format($p->subtotal_detail_pembelian) }}
                                            </td>
                                            <td>
                                                {{ 'Rp' . number_format($p->ppn_detail_pembelian) }}

                                            </td>
                                            <td>
                                                {{ 'Rp' . number_format($p->subtotal_detail_pembelian * 0.02) }}

                                            </td>
                                            <td>
                                                {{ 'Rp' . number_format($p->total_detail_pembelian) }}
                                            </td>
                                            <td>
                                                {{ $p->layanan }}
                                            </td>
                                            <td>
                                                {{ $p->nama_pelanggan }}
                                            </td>
                                            <td>
                                                {{ $p->nama_pemasok }}
                                            </td>

                                            <td {{ $p->status_transaksi != 'purchase' ? 'hidden' : '' }}>
                                                <i class="fa fa-trash" aria-hidden="true" style="color:red"
                                                    onclick="deleteItem({{ $p->id_pembelian }},'row-{{ $p->id_pembelian }}')"></i>
                                            </td>

                                        </tr>
                                        <?php
                                        $subtotal += $p->subtotal_detail_pembelian;
                                        $ppn12 += $p->harga_detail_pembelian * 0.02;
                                        $ppn += $p->ppn_detail_pembelian;
                                        $total += $p->total_detail_pembelian;
                                        
                                        ?>
                                    @endif
                                @endforeach

                                <tr>
                                    <td colspan='15'>TOTAL</td>
                                    <td>{{ 'Rp.' . number_format($subtotal) }}</td>
                                    <td>{{ 'Rp.' . number_format($ppn) }}</td>
                                    <td>{{ 'Rp.' . number_format($ppn12) }}</td>
                                    <td>{{ 'Rp.' . number_format($total + $data[0]->ongkir) }}</td>
                                </tr>

                            </table>
                        </div>






                    </div>

                </div>

                <a href="{{ url()->previous() }}" class="btn btn-primary  mb-4 ml-3" style="margin-top: 30px">Back</a>
                <a href="{{ url('goods') }}" class="btn btn-primary  mb-4 ml-3" style="margin-top: 30px">Next</a>
            </div>



        </div>
    </div>





    </div>
    </div>
    </div>


    <div id="modal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Sales</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Are you sure to delete this item? </p>
                    <button type=button id=submit_delete class="btn btn-primary ">submit</button>


                </div>
            </div>
        </div>
    </div>

    <script>
        $('#harga').mask('000.000.000.000.000', {
            reverse: true
        });
        $('#ongkir').mask('000.000.000.000.000', {
            reverse: true
        });


        function selesai() {
            var table_atas = document.getElementById("table_atas");
            table_atas.setAttribute("hidden", true);
            var submit = document.getElementById("submit");
            submit.removeAttribute("hidden");

        }

        function drop() {
            // bikin form nya
            console.log("hello");
            let id_produk = document.getElementById('id_produk').value.split('|');

            let bentuk_produk = id_produk[1];

            let lebar = document.getElementById('lebar_transaksi');
            if (bentuk_produk == "CYLINDER") {

                lebar.setAttribute('value', 0);
                lebar.setAttribute('readonly', true);
            } else {
                lebar.removeAttribute('readonly');
            }



        }

        function deleteItem(id_pembelian, row) {
            $('#modal').modal('show')

            $('#submit_delete').click(() => {
                let baseUrl = `{{ url('/') }}`

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name=csrf-token]').attr('content')
                    },
                    url: `${baseUrl}/purchase/detail_delete`,
                    type: 'POST',
                    data: {
                        id_pembelian
                    },
                    dataType: 'json',
                    success: function(data) {
                        $('#modal').modal('hide')

                    }
                })

                $(`#${row}`).remove()
            })

        }
    </script>
@endsection
