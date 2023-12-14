<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class promoCode extends Model
{
    use HasFactory;
    use HasFactory;
    protected $fillable=[
        'code',
        'discount',
    ];

    function promoCode(){
        return $this->belongsTo(promoCode::class);
    }
}
