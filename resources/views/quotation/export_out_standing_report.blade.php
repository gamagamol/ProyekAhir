 <div class="table-responsive text-center">
     <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
         <thead>
             <tr>
                 <th>Date Quotation</th>
                 <th>No Quotation</th>
                 <th>Date Sales</th>
                 <th>No Sales</th>
                 <th>Thickness</th>
                 <th>Length</th>
                 <th>Width</th>
                 <th>Weight</th>
                 <th>Qty</th>
                 <th>Price</th>
                 <th>Total</th>
                 <th>Processing</th>
                 <th>Supplier</th>
                 <th>Status</th>
             </tr>
         </thead>
         <tbody id="Tbody">
             @foreach ($data as $d)
                 <tr>
                     <td>{{ $d->tgl_penjualan }}</td>
                     <td>{{ $d->tgl_pengiriman ? $d->tgl_pengiriman : '-' }}</td>
                     <td>{{ $d->no_penjualan }}</td>
                     <td>{{ $d->no_pengiriman ? $d->no_pengiriman : '-' }}</td>
                     <td>{{ $d->nama_pegawai }}</td>
                     <td>{{ $d->nama_produk }}</td>
                     <td>{{ $d->panjang_transaksi }}</td>
                     <td>{{ $d->lebar_transaksi }}</td>
                     <td>{{ $d->tebal_transaksi }}</td>
                     <td>{{ $d->berat }}</td>
                     <td>{{ $d->jumlah }}</td>
                     <td>{{ $d->layanan }}</td>
                     <td>{{ $d->nama_pelanggan }}</td>
                     <td>{{ $d->nama_pemasok }}</td>
             @endforeach
         </tbody>



     </table>
 </div>
