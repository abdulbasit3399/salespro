<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentWithLoadCard extends Model
{
    use HasFactory;
    protected $table = 'payment_with_load_card';
    protected $fillable =[
        "payment_id", "load_card_id"
    ];
}
