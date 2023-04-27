<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoadCard extends Model
{
    use HasFactory;

    protected $table = 'load_cards';
    protected $fillable =[
        "card_no", "amount", "expense", "customer_id", "user_id", "expired_date", "created_by", "is_active"  
    ];
}
