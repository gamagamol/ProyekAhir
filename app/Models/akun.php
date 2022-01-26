<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;



class akun extends Model
{
    use HasFactory;
    public $fillable=['kode_akun','nama_akun','header_akun'];
   
    public function index($kode=null){
        if($kode){
            return DB::table('akun')->where('nama_akun','like',"%$kode%")->get();
        }
        return DB::table('akun')->get();
    }
   public function insert($validated){
       DB::table('akun')->insert($validated);
       
   }
  
}
