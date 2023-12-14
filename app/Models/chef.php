<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class chef extends Model
{
    use HasFactory;

    protected $fillable=[
        'user_id',
        'item_id'
        
    ];

    function user(){
        return $this->belongsTo(User::class);
    }
    function item(){
        return $this->belongsTo(item::class);
    }

}
