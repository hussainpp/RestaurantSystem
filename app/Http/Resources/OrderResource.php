<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        return[
            'id'=>$this->id,
            'name'=>$this->name,
            'address'=>$this->address,
            'phone'=>$this->phone,
            'note'=>$this->note,
            'user'=>$this->user->name,
            'type_order'=>$this->typeOrder->name,
            'state_order'=>$this->stateOrder->name,
            // 'item_orderr'=>$this::order_item(),
            'total_preparation_time'=>$this->total_preparation_time,
            'total_price'=>$this->total_price,
            'item_order'=>OrderItemResource::collection($this->orderItem),
        ];
    }
}
