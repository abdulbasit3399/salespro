<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoadCardRecharge extends Model
{
    use HasFactory;
    protected $table = 'load_card_recharges';

    protected $fillable =[

        "load_card_id", "amount", "user_id"
    ];
}
