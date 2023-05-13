 <div class="table-responsive text-center">
     <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
         <thead>
             <tr>
                 <th>Customer</th>
                 <th>Sales Name</th>
                 <th>QTN</th>
                 <th>PO</th>
                 <th>Loss</th>

             </tr>
         </thead>
         <tbody id="Tbody">
             @php
                 $total_penawaran = 0;
                 $total_penawaran_loss = 0;
                 $total_penjualan = 0;
             @endphp
             @foreach ($data as $d)
                 <tr>
                     <td>{{ $d->nama_pelanggan }}</td>
                     <td>{{ $d->nama_pegawai }}</td>
                     <td>{{ number_format($d->total_penawaran) }}</td>
                     <?php
                     $penjualan = $d->total_penjualan;
                     
                     ?>
                     <td>{{ number_format($d->total_penawaran - $d->total_penawaran_loss) }}</td>
                     <td>{{ number_format($d->total_penawaran_loss) }}</td>


                 </tr>
                 <?php
                 
                 $total_penawaran += $d->total_penawaran;
                 $total_penjualan += $penjualan;
                 $total_penawaran_loss += $d->total_penawaran_loss;
                 
                 ?>
             @endforeach
             <tr>
                 <td colspan="2" class="text-center"> Grand Total</td>
                 <td>{{ $total_penawaran }}</td>
                 <td>{{ $total_penjualan }}</td>
                 <td>{{ $total_penawaran_loss }}</td>
             </tr>
         </tbody>



     </table>
 </div>
