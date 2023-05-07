<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class TransaksiModel extends Model
{
    use HasFactory;


    public function getTransaksi($id_transaksi = null)
    {

        if ($id_transaksi) {
            return DB::table('transaksi')->where('id_transaksi', '=', $id_transaksi)->first();
        }
        return DB::table('transaksi')->get();
    }
}
