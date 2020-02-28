<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class People extends Model
{
    //Marcar como excluido quando fazer um delete()
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    //
    public function city(){
        $cad = DB::table('cities')->where('id',$this->city_id)->first();
        return $cad;
    }
    //
    public function city_(){
        if (!$this->city_id) return '';

        $cad = $this->city();

        if (!$cad) return '';
        return $cad->name.' / '.$cad->uf;
    }

}
