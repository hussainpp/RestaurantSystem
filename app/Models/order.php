<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class order extends Model
{
    use HasFactory;
    protected function priceDiscount(): Attribute
    {
        $code=promoCode::where('id',$this->promo_code_id)->first();
        return new Attribute(
            get: fn () => $code==null?$this->total_price
            :$this->total_price-($this->total_price/$code->discount),
        );
    }

    // public function scopeOf(Builder $query,string $oper,string|array|null $type): void
    // {
    //     $type!=null?$query->whereBetween("$oper in ($type[0],)",):0;
        
    // }
    protected $appends = ['price_discount'];
    protected $fillable=[
        'name',
        'address',
        'phone',
        'note',
        'user_id',
        'type_order_id',
        'state_order_id',
        'promo_code_id',
        // 'total_preparation_time',
        // 'total_price'
    ];

     
    function getTotalPreparationTimeAttribute(){
        $out= orderItem::where('order_id',$this->id)->
        selectRaw('SUM(preparation_time*quantity) as total')->
        join('items','item_id','=','items.id')->get();
        return (int) $out[0]->total;
        
    }  
    function getTotalPriceAttribute(){
        $out= orderItem::where('order_id',$this->id)->
        selectRaw('SUM(price*quantity) as total')->
        join('items','item_id','=','items.id')->get();
        return (int)$out[0]->total;
    }

    function user(){
        return $this->belongsTo(User::class);
    }

    function typeOrder(){
        return $this->belongsTo(typeOrder::class);
    }
    function stateOrder(){
        return $this->belongsTo(stateOrder::class);
    }
    function promoCode(){
        return $this->belongsTo(promoCode::class);
    }

    function orderItem(){
        return $this->hasMany(orderItem::class);
    }
}
