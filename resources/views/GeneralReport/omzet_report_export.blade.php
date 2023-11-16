 <div class="table-responsive text-center">
     <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
         <thead>
             <tr>
                 <th>NO QUOTATION</th>
                 <th>TGL QUOTATION</th>
                 <th>NO SALES</th>
                 <th>TGL SALES</th>
                 <th>NAMA CUSTOMER</th>
                 <th>NO PEMBELIAN</th>
                 <th>TGL PEMBELIAN</th>
                 <th>NAMA SUPPLIER</th>
                 <th>NO DELIVERY</th>
                 <th>TGL DELIVERY</th>
                 <th>NO INVOICE</th>
                 <th>TGL INVOICE</th>
                 <th>NO PEMBAYARAN</th>
                 <th>TGL PEMBAYARAN</th>
                 <th>SUBTOTAL</th>
                 <th>PPH11%</th>
                 <th>TOTAL</th>
                 <th>SUBTOTAL PEMBELIAN</th>
                 <th>PPH11% PEMBELIAN</th>
                 <th>TOTAL PEMBELIAN</th>
                 <th>TGL FAKTUR</th>
                 <th>TUKAR INVOICE</th>
                 <th>FAKTUR PAJAK</th>
                 <th>OMZET</th>

             </tr>
         </thead>
         <tbody id="Tbody">
             @php
                 $total_omzet = 0;
                 
             @endphp
             @foreach ($data as $d)
                 <tr>
                     <td>{{ $d->no_penawaran ? $d->no_penawaran : '-' }}</td>
                     <td>{{ $d->tgl_penawaran ? $d->tgl_penawaran : '-' }}</td>
                     <td>{{ $d->no_penjualan ? $d->no_penjualan : '-' }}</td>
                     <td>{{ $d->tgl_penjualan ? $d->tgl_penjualan : '-' }}</td>
                     <td>{{ $d->nama_pelanggan ? $d->nama_pelanggan : '-' }}</td>
                     <td>{{ $d->no_pembelian ? $d->no_pembelian : '-' }}</td>
                     <td>{{ $d->tgl_pembelian ? $d->tgl_pembelian : '-' }}</td>
                     <td>{{ $d->nama_pemasok ? $d->nama_pemasok : '-' }}</td>
                     <td>{{ $d->no_pengiriman ? $d->no_pengiriman : '-' }}</td>
                     <td>{{ $d->tgl_pengiriman ? $d->tgl_pengiriman : '-' }}</td>
                     <td>{{ $d->no_tagihan ? $d->no_tagihan : '-' }}</td>
                     <td>{{ $d->tgl_tagihan ? $d->tgl_tagihan : '-' }}</td>
                     <td>{{ $d->no_pembayaran ? $d->no_pembayaran : '-' }}</td>
                     <td>{{ $d->tgl_pembayaran ? $d->tgl_pembayaran : '-' }}</td>
                     <td>{{ $d->subtotal ? number_format($d->subtotal,0,',','.') : 0 }}</td>
                     <td>{{ $d->ppn ? number_format($d->ppn,0,',','.') : 0 }}</td>
                     <td>{{ $d->total ? number_format($d->total,0,',','.') : 0 }}</td>
                     <td>{{ $d->subtotal_detail_pembelian ? number_format($d->subtotal_detail_pembelian,0,',','.') : 0 }}
                     </td>
                     <td>{{ $d->subtotal_detail_pembelian ? number_format($d->ppn_detail_pembelian,0,',','.') : 0 }}</td>
                     <td>{{ $d->subtotal_detail_pembelian ? number_format($d->total_detail_pembelian,0,',','.') : 0 }}</td>

                     @php
                         $total = $d->total ? $d->total : 0;
                         $total_detail_pembelian = $d->total_detail_pembelian ? $d->total_detail_pembelian : 0;
                         $omzet = $total - $total_detail_pembelian;
                     @endphp

                     <td>{{ '-' }}</td>
                     <td>{{ '-' }}</td>
                     <td>{{ '-' }}</td>
                     <td>{{ $d->subtotal_detail_pembelian ? number_format($omzet,0,',','.') : 0 }}</td>
                     @php
                         $total_omzet += $omzet;
                     @endphp

                 </tr>
             @endforeach
         </tbody>
         <tbody>
             <tr>
                 <td colspan='23' class='text-center'>Total omzet </td>
                 <td> {{ number_format($total_omzet) }} </td>
             </tr>
         </tbody>
     </table>
 </div>
