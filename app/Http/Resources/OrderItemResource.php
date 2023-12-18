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
        $item=item::find($this->item_id);
        // return parent::toArray($request);
        return[
            'id'=>$this->id,
            'name'=>$item->name??null,
             'price'=>$item->price??null,
             'image'=>$item->image??null,
             'preparation_time'=>$item->preparation_time??null,
            'quantity'=>$this->quantity,
        ];
    }
}
