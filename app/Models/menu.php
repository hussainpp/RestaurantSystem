<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class menu extends Model
{
    use HasFactory;

    protected $fillable=[
        'name',
    ];
    function item(){
        return $this->hasMany(item::class)->where('active',1);
    }
    function itemAll(){
        return $this->hasMany(item::class);
    }
}
