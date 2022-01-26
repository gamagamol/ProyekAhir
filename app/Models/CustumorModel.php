<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class CustumorModel extends Model
{
    use HasFactory;
    public function index($kode = null)
    {
        if ($kode) {
            return DB::table('pelanggan')->where('nama_pelanggan', 'like', "%$kode%")->get();
        }
        return DB::table('pelanggan')->paginate(5);
    }
    public function insert($data)
    {
        DB::table('pelanggan')->insert($data);
    }
    public function show($kode)
    {
        return DB::table('pelanggan')->where('id_pelanggan', $kode)->first();
    }
    public function updt($data)
    {
        $id_pelanggan = $data['id_pelanggan'];
        DB::table('pelanggan')->where('id_pelanggan', $id_pelanggan)->update($data);
    }
}
