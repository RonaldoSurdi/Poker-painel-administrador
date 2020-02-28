<?php

namespace App\Models\Poker;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class UserApp extends Model
{
    //
    protected $connection = 'poker';
    protected $table = 'user_app';
}
