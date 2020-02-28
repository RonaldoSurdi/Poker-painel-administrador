<?php

namespace App\Models\Poker;

use App\Models\City;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Club extends Model
{
    //
    protected $connection = 'poker';
    //Marcar como excluido quando fazer um delete()
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    public function Status(){
        if ($this->status==0) return "Bloqueado";
        elseif ($this->status==1) return "Liberado";
    }

    public function city(){
        if (!$this->city_id) return '';

        $cad = DB::table('cities')
            ->selectRaw("id, concat(name,' / ',uf)".' "text" ')
            ->where('id',$this->city_id)
            ->first();
        if (!$cad) return '';
        return $cad->text;
    }

    public function cidade(){
        if (!$this->city_id) return new City();

        $cad = City::find($this->city_id);
        if (!$cad) return new City();

        return $cad;
    }

    public function obs(){
        $cad = ClubObs::whereclub_id($this->id)->first();
        if (!$cad) return '';
        return $cad->obs;
    }

    public function premium(){
        $cad = License::whereclub_id($this->id)
            ->wherestatus(1)
            ->first();
        if (!$cad)
            return false;
        else
            return true;
    }

    public function User_email(){
        $cad = ClubUser::whereclub_id($this->id)->first();
        if (!$cad) return '';
        return $cad->email;
    }

    public function licenca(){
        $lic = License::wherestatus(1)->whereclub_id($this->id)->first();
        if (!$lic){
            $lic = License::whereclub_id($this->id)->first();
            if (!$lic)
                return '---';
            else
                return 'Vencida';
        }

        return $lic->Type();
    }

    public function license(){
        return $this->hasMany(License::class,'club_id','id');
    }
}
