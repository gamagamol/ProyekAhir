<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class DashboardModel extends Model
{
    use HasFactory;
    public function grafik()
    {
        return DB::table('transaksi')
            ->selectRaw(" 
                        round (CAST(((SELECT DISTINCT COUNT(kode_transaksi) FROM transaksi WHERE status_transaksi='quotation')/(SELECT DISTINCT COUNT(kode_transaksi) FROM transaksi)*100) as float)) as quotation, 
                        round (CAST(((SELECT DISTINCT COUNT(kode_transaksi) FROM transaksi WHERE status_transaksi='sales')/(SELECT DISTINCT COUNT(kode_transaksi) FROM transaksi)*100) as float)) as sales ,
                        round (CAST(((SELECT DISTINCT COUNT(kode_transaksi) FROM transaksi WHERE status_transaksi='purchase')/(SELECT DISTINCT COUNT(kode_transaksi) FROM transaksi)*100) as float)) as purchase ,
                        round (CAST(((SELECT DISTINCT COUNT(kode_transaksi) FROM transaksi WHERE status_transaksi='goods')/(SELECT DISTINCT COUNT(kode_transaksi) FROM transaksi)*100) as float)) as goods ,
                        round (CAST(((SELECT DISTINCT COUNT(kode_transaksi) FROM transaksi WHERE status_transaksi='delivery')/(SELECT DISTINCT COUNT(kode_transaksi) FROM transaksi)*100) as float)) as delivery ,
                        round (CAST(((SELECT DISTINCT COUNT(kode_transaksi) FROM transaksi WHERE status_transaksi='bill')/(SELECT DISTINCT COUNT(kode_transaksi) FROM transaksi)*100) as float)) as bill ,
                        round (CAST(((SELECT DISTINCT COUNT(kode_transaksi) FROM transaksi WHERE status_transaksi='payment')/(SELECT DISTINCT COUNT(kode_transaksi) FROM transaksi)*100) as float)) as payment 
                        ")
            ->first();
           
            
    }
}
