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
                     <td>{{ $d->tgl_penawaran }}</td>
                     <td>{{ $d->no_penawaran }}</td>
                     <td>{{ $d->tgl_penjualan }}</td>
                     <td>{{ $d->no_penjualan }}</td>
                     <td>{{ $d->tebal_transaksi }}</td>
                     <td>{{ $d->panjang_transaksi }}</td>
                     <td>{{ $d->lebar_transaksi }}</td>
                     <td>{{ $d->berat }}</td>
                     <td>{{ $d->jumlah }}</td>
                     <td>{{ $d->harga }}</td>
                     <td>{{ $d->total }}</td>
                     <td>{{ $d->layanan }}</td>
                     <td>{{ $d->nama_pemasok }}</td>
                     {{-- html += `<td>${ (d.no_penjualan) ? 'CLOSE' :'OPEN'  }</td>` --}}

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
