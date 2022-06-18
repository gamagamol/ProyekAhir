<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Services extends Model
{
    use HasFactory;

   protected $table='layanan';
   protected $primarykey='id_layanan';
   protected $fillable=['nama_layanan'];

    public function index($id_layanan=null){
        if($id_layanan){
           return DB::table('layanan')->where('id_layanan',$id_layanan)->paginate(1);
        }else{
          return   DB::table('layanan')->paginate(5);

        }
    }

   


}
