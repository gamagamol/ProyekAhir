<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;



class ProductModel extends Model
{
    use HasFactory;
    public function index($kode = null)
    {
        if ($kode) {
            return DB::table('produk')->where('nama_produk', 'like', "%$kode%")->get();
        }
        return DB::table('produk')->get();
    }
    public function insert($data)
    {
        DB::table('produk')->insert($data);
    }
    public function show($kode)
    {
        return DB::table('produk')->where('id_produk', $kode)->first();
    }
    public function updt($data)
    {
        $id_produk = $data['id_produk'];
        DB::table('produk')->where('id_produk', $id_produk)->update($data);
    }
    public function getProductByCode($nama_produk)
    {
        return DB::table('produk')->where("nama_produk", $nama_produk)->first();
    }

  
}
