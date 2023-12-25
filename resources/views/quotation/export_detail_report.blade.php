 <div class="table-responsive text-center">
     <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
         <thead>
             <tr>
                 <th>Date Quotation</th>
                 <th>No Quotation</th>
                 <th>Customer</th>
                 <th>Job Number</th>
                 <th>Grade</th>
                 <th>Thikness</th>
                 <th>Width</th>
                 <th>Length</th>
                 <th>QTY</th>
                 <th>Grade</th>
                 <th>Thikness</th>
                 <th>Width</th>
                 <th>Length</th>
                 <th>QTY</th>
                 <th>Weight</th>
                 <th>Unit Price</th>
                 <th>Total</th>
                 <th>Process</th>
                 <th>Date Sales</th>
                 <th>No Sales</th>
                 <th>Supplier</th>
                 <th>Sales</th>
                 <th>Status</th>
             </tr>
         </thead>
         <tbody id="Tbody">
             @foreach ($data as $d)
                 <tr>
                     <td>{{ $d->tgl_penawaran }}</td>
                     <td>{{ $d->no_penawaran }}</td>
                     <td>{{ $d->nama_pelanggan }}</td>
                     <td>{{ $d->nomor_pekerjaan }}</td>
                     <td>{{ $d->nama_produk }}</td>
                     <td>{{ $d->tebal_transaksi }}</td>
                     <td>{{ $d->lebar_transaksi }}</td>
                     <td>{{ $d->panjang_transaksi }}</td>
                     <td>{{ $d->jumlah }}</td>
                     <td>{{ $d->nama_produk }}</td>
                     <td>{{ $d->tebal_penawaran }}</td>
                     <td>{{ $d->lebar_penawaran }}</td>
                     <td>{{ $d->panjang_penawaran }}</td>
                     <td>{{ $d->jumlah }}</td>
                     <td>{{ $d->berat }}</td>
                     <td>{{ number_format($d->harga) }}</td>
                     <td>{{ number_format($d->total) }}</td>
                     <td>{{ $d->layanan }}</td>
                     <td>{{ $d->tgl_penjualan ? $d->tgl_penjualan : '-' }}</td>
                     <td>{{ $d->no_penjualan ? $d->no_penjualan : '-' }}</td>
                     <td>{{ $d->nama_pemasok ? $d->nama_pemasok : '-' }}</td>
                     <td>{{ $d->nama_pegawai ? $d->nama_pegawai : '-' }}</td>

                     @if ($d->no_penjualan)
                         <td>CLOSE</td>
                     @else
                         <td> OPEN</td>
                     @endif

                 </tr>
             @endforeach
         </tbody>



     </table>
 </div>
