<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SupplierModel extends Model
{
    use HasFactory;
    public function index($kode = null)
    {
        if ($kode) {
            return DB::table('pemasok')->where('nama_pemasok', 'like', "%$kode%")->get();
        }
        return DB::table('pemasok')->get();
    }
    public function insert($data)
    {
        DB::table('pemasok')->insert($data);
    }
    public function show($kode)
    {
        return DB::table('pemasok')->where('id_pemasok', $kode)->first();
    }
    public function updt($data)
    {
        $id_pemasok = $data['id_pemasok'];
        DB::table('pemasok')->where('id_pemasok', $id_pemasok)->update($data);
    }
}
