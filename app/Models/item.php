<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class item extends Model
{
    use HasFactory;
    protected $fillable=[
        'name',
        'price',
        'image',
        'details',
        'active',
        'preparation_time',
        'menu_id',
    ];
    function getImageAttribute($image){
        
        if($image!=null){
            return asset('upload/'.$image);
        }
        return null;
    }

    function menu(){
        return $this->belongsTo(menu::class);
    }
    function orderItem(){
        return $this->hasMany(orderItem::class);
    }
    function chef(){
        return $this->hasMany(chef::class);
    }
}
