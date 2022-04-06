<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use PHPUnit\TextUI\XmlConfiguration\Group;

class AgingScheduleModel extends Model
{
    use HasFactory;
    public function index($id_pelanggan = null)
    {
       

        return DB::table('transaksi')
            ->selectRaw("no_tagihan,tgl_tagihan, DATE_ADD(tgl_tagihan, INTERVAL 31 DAY) AS DUE_DATE,nama_pelanggan, sum(total) as total, Datediff( CURDATE(),tgl_tagihan) as selisih,tgl_tagihan, 
           sum( total) AS total_selisih")
            ->join('pelanggan', 'transaksi.id_pelanggan', "=", "pelanggan.id_pelanggan")
            ->join('penawaran', 'penawaran.id_transaksi', "=", "transaksi.id_transaksi")
            ->join('tagihan', 'tagihan.id_transaksi', "=", "transaksi.id_transaksi")
            ->join('pengiriman','tagihan.id_pengiriman','=','pengiriman.id_pengiriman')
            ->join('detail_transaksi_pengiriman', 'detail_transaksi_pengiriman.id_pengiriman', "=", "pengiriman.id_pengiriman")
            ->where('status_transaksi', "=", "bill")
            ->groupBy('no_tagihan','tgl_tagihan')
            ->get();
    }
}
