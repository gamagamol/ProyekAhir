 <div class="table-responsive text-center">
     <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
         <thead>
             <tr>
                 {{-- <th>Date Quotation</th>
                 <th>No Quotation</th> --}}
                 <th>Sales Date</th>
                 <th>Delivery Date</th>
                 <th>Sales Number</th>
                 <th>Delivery Number</th>
                 <th>Grade</th>
                 <th>Thickness</th>
                 <th>Width</th>
                 <th>Length</th>
                 <th>Weight</th>
                 <th>Qty</th>
                 <th>Price</th>
                 <th>subtotal</th>
                 <th>Processing</th>
                 <th>Customer</th>
                 <th>Supplier</th>
             
             </tr>
         </thead>
         <tbody id="Tbody">
             @foreach ($data as $d)
                 <tr>
                     <td>{{ $d->tgl_penjualan }}</td>
                     <td>{{ $d->tgl_pengiriman }}</td>
                     <td>{{ $d->no_penjualan }}</td>
                     <td>{{ $d->no_pengiriman }}</td>
                     <td>{{ $d->nama_produk }}</td>
                     <td>{{ $d->tebal_transaksi }}</td>
                     <td>{{ $d->lebar_transaksi }}</td>
                     <td>{{ $d->panjang_transaksi }}</td>
                     <td>{{ $d->berat }}</td>
                     <td>{{ $d->jumlah }}</td>
                     <td>{{ $d->harga }}</td>
                     <td>{{ $d->berat * $d->harga }}</td>
                     <td>{{ $d->layanan }}</td>
                     <td>{{ $d->nama_pelanggan }}</td>
                     <td>{{ $d->nama_pemasok }}</td>
             @endforeach
         </tbody>



     </table>
 </div>
