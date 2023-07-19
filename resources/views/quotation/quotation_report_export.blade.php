 <div class="table-responsive text-center">
     <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
         <thead>
              <tr>
                                <th>Date Quotation</th>
                                <th>No Quotation</th>
                                <th>Customer</th>
                                <th>Sales Name</th>
                                <th>Subtotal</th>
                                <th>VAT 11%</th>
                                <th>Shipment</th>
                                <th>Total</th>
                                <th>Date Sales</th>
                                <th>No Sales</th>
                                <th>Date Purchase</th>
                                <th>No Purchase</th>
                                <th>Total Sales</th>

                            </tr>
         </thead>
         <tbody id="Tbody">
             @foreach ($data as $d)
                 <tr>
                     <td>{{ $d->tgl_penawaran }}</td>
                     <td>{{ $d->no_penawaran }}</td>
                     <td>{{ $d->nama_pelanggan }}</td>
                     <td>{{ $d->nama_pegawai }}</td>
                     <td>{{ number_format($d->subtotal) }}</td>
                     <td>{{ number_format($d->ppn) }}</td>
                     <td>{{ number_format($d->ongkir) }}</td>
                     <td>{{ number_format($d->total_transaksi) }}</td>
                     <td>{{ $d->tgl_penjualan }}</td>
                     <td>{{ $d->no_penjualan }}</td>
                     <td>{{ $d->tgl_pembelian }}</td>
                     <td>{{ $d->no_pembelian }}</td>
                     <td>{{ number_format($d->total_penjualan) }}</td>

                 </tr>
             @endforeach
         </tbody>



     </table>
 </div>
