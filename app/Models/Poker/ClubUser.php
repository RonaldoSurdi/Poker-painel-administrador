<?php

namespace App\Models\Poker;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class ClubUser extends Model
{
    //
    protected $connection = 'poker';
    protected $table = 'users';

    public function club(){
        return $this->hasOne(Club::class,'id','club_id');
    }
}
