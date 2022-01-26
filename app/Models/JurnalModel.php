<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class JurnalModel extends Model
{
    use HasFactory;
    public function index()
    {
        return DB::table('jurnal')
            ->join('akun', "jurnal.kode_akun", '=', 'akun.kode_akun')
            ->orderBy('id_jurnal', "ASC")
            ->orderBy('tgl_jurnal', "DESC")
            ->get();
    }
}
