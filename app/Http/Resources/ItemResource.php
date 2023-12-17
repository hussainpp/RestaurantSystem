<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=>$this->id,
           'name'=>$this->name,
           'price'=>$this->price,
           'preparation_time'=>$this->preparation_time,
           'details'=>$this->details??"",
           'image'=>$this->image??"",
           'active'=>$this->active??"",

        ];
        //return parent::toArray($request);
    }
}
