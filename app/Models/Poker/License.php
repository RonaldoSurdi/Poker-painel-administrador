<?php

namespace App\Models\Poker;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class License extends Model
{
    //
    protected $connection = 'poker';
    //Marcar como excluido quando fazer um delete()
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    public function club(){
        return $this->hasOne(Club::class,'id','club_id');
    }

    public function Type(){
        if ($this->type==1)
            return "Anual";
        elseif ($this->type==2)
            return "30 dias";
        else
            return "--";
    }

    public function Status(){
        //0-pendente 1-ativa 2-vencida 9-bloqueada
        if ($this->status==1)
            return "Ativa";
        elseif ($this->status==2)
            return "Vencida";
        elseif ($this->status==9)
            return "Bloqueada";
        else
            return "Pendente";
    }

    public function Pay(){
        if ($this->type==2)
            return "-";

        $pay = LicensePay::wherelicense_id($this->id)->orderby('id','desc')->first();
        if (!$pay)
            return "Gerar pagamento";
        //
        return "Ver pagamentos";
//        return $pay->Type().' - '.$pay->Status();
    }

    public function payment(){
        return $this->hasMany(LicensePay::class,'license_id','id');
    }

    public function vencida(){
        $data_val = date('Ymd', strtotime($this->due_date));
        $data_now = date('Ymd');
        return ($data_val<$data_now);
    }

    public function dias(){
        if ($this->vencida())
            return 0;
        $data_val = date('Y-m-d', strtotime($this->due_date));

        $data_val = Carbon::createFromFormat('Y-m-d', $data_val);
        $data_now = Carbon::createFromFormat('Y-m-d', date('Y-m-d'));

        $dias = $data_val->diffInDays($data_now);
        return $dias;
    }


}
