<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class stateOrder extends Model
{
    use HasFactory;
    protected $fillable=[
        'name',
   
    ];

    function order(){
        return $this->belongsTo(order::class);
    }
}
