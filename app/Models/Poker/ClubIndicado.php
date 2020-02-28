<?php

namespace App\Models\Poker;

use Illuminate\Database\Eloquent\Model;

class ClubIndicado extends Model
{
    //
    protected $table = 'clubs_indicados';
    protected $connection = 'poker';

    public function Status(){
        if ($this->status==0) return "Pendente";
        elseif ($this->status==1) return "Cadastrado";
        elseif ($this->status==2) return "Não Encontrado";
        elseif ($this->status==3) return "Discartado";
        else return "Não Definido";
    }

    public function Club(){
        if (!$this->club_id) return "";
        $cad = Club::find($this->club_id);
        return $cad->name;
    }
}
