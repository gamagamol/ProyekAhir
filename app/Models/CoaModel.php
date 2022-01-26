<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;



class CoaModel extends Model
{
  
    public function index($kode = null)
    {
      
        if ($kode) {
           return DB::table('akun')->where('kode_akun',"=",$kode)->paginate(1);
        }
        return DB::table('akun')->paginate(5);
    }
    public function show($kode)
    {
        return DB::table('akun')->where('kode_akun', $kode)->first();
    }
    public function insert($data)
    {
        DB::table('akun')->insert($data);
    }
    public function updt($data)
    {
        $kode_akun = $data['kode_akun'];
        DB::table('akun')->where('kode_akun', $kode_akun)->update($data);
    }
}
