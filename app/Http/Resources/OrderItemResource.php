<?php

namespace App\Http\Resources;

use App\Models\item;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $item=item::where('id',$this->item_id)->get();
        // return parent::toArray($request);
        return[
            'id'=>$this->id,
            'name'=>$item[0]->name,
             'price'=>$item[0]->price,
             'image'=>$item[0]->image,
             'preparation_time'=>$item[0]->preparation_time,
            'quantity'=>$this->quantity,
        ];
    }
}
