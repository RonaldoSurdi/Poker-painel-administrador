<?php

namespace App\Models\Poker;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Audit extends Model
{
    //public $timestamps = false;
    //protected $connection = 'poker';
    //Marcar como excluido quando fazer um delete()
    protected $dates = ['created_at'];
}
