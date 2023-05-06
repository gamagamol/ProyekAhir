<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class pegawaiModel extends Model
{
    use HasFactory;

    protected $fillable = ['id_pegawai', 'nama_pegawai', 'jabatan_pegawai'];


    public function index($search = null)
    {

        if ($search) {
            return DB::table('pegawai')
                ->where('kode_pegawai', 'like', "%$search%")
                ->whereNull('deleted_at')
                ->paginate(5);
        } else {
            return DB::table('pegawai')
                ->whereNull('deleted_at')
                ->paginate(5);
        }
    }


    public function insert($data)
    {
        DB::table('pegawai')->insert($data);
    }

    public function updatePegawai($id, $data)
    {
        DB::table('pegawai')->where('id_pegawai', '=', $id)->update($data);
    }
    public function deletePegawai($id, $data)
    {
        DB::table('pegawai')->where('id_pegawai', '=', $id)->update($data);
    }

    public function getEmployeeById($id)
    {
        return DB::table('pegawai')->where('id_pegawai', '=', $id)->first();
    }


    public function getEmployee($jabatan = null)
    {
        if ($jabatan) {
            return DB::table('pegawai')
                ->where('jabatan_pegawai', '=', $jabatan)
                ->whereNull('deleted_at')
                ->get();
        }
        return DB::table('pegawai')
            ->whereNull('deleted_at')
            ->get();
    }
}
