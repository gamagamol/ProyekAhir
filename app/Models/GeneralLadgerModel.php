<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class GeneralLadgerModel extends Model
{
    use HasFactory;
    protected $casts = [
        'permissions' => 'json',
    ];
    public function account()
    {
        return DB::table('jurnal')
            ->join('akun', "jurnal.kode_akun", '=', 'akun.kode_akun')
            ->groupBy('jurnal.kode_akun')
            ->get();
    }
    public function index()
    {
        return DB::table('jurnal')
            ->join('akun', "jurnal.kode_akun", "=", "akun.kode_akun")
            ->whereRaw(" YEAR(tgl_jurnal)=YEAR(CURDATE())")
            ->get();
    }
    public function saldo_awal()
    {
        return DB::table('jurnal')
            ->selectRaw("nama_akun,jurnal.kode_akun,sum(nominal)as saldo_awal")
            ->join('akun', "jurnal.kode_akun", "=", "akun.kode_akun")
            ->whereRaw(" YEAR(tgl_jurnal)=YEAR(CURDATE())-1 and jurnal.kode_akun!=112")
            ->groupBy('jurnal.kode_akun')
            ->get();
    }
    public function saldo_awal_piutang()
    {

        $debit = DB::table('jurnal')
            ->selectRaw("sum(nominal)as saldo_awal")
            ->whereRaw("YEAR(tgl_jurnal)=YEAR(CURDATE())-1 and jurnal.kode_akun=112 and posisi_db_cr='debit'")
            ->get();
        $kredit = DB::table('jurnal')
            ->selectRaw("sum(nominal)as saldo_awal")
            ->whereRaw("YEAR(tgl_jurnal)=YEAR(CURDATE())-1 and jurnal.kode_akun=112 and posisi_db_cr='kredit'")
            ->get();
        $hasil = $debit[0]->saldo_awal - $kredit[0]->saldo_awal;
        return $hasil;
    }
    public function show($array)
    {
        $kode_akun = $array['kode_akun'];
        $tgl1 = $array['tgl1'];
        $tgl2 = $array['tgl2'];
        return DB::table('jurnal')
            ->join("akun", "jurnal.kode_akun", "=", "akun.kode_akun")

            ->whereRaw("jurnal.kode_akun=$kode_akun AND tgl_jurnal BETWEEN '$tgl1' AND '$tgl2' ")
            ->get();
    }
}
