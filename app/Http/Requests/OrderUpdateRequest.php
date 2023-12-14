<?php

namespace App\Http\Requests;

use Closure;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class OrderUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        if(Auth::check()){
        return [
            'type_order_id'=>'exists:type_orders,id|numeric',
            'name'=>'string|required_unless:type_order_id,1',
            'address'=>'string|required_unless:type_order_id,1',
            'phone'=>'numeric|required_unless:type_order_id,1',
            'note'=>'string|nullable',
            'user_id'=>'missing',
            "state_order_id"=>["exists:state_orders,id","numeric",function (string $attribute, mixed $value, Closure $fail) {
                if ($value == 2&&$this->type_order_id==1) {
                    $fail("The {$attribute} is invalid.");
                }
            }],
            "promo_code"=>'string|nullable|exists:promo_codes,code',
            'item_id'=>'array',
            'item_id.*'=>'required_array_keys:item_id,quantity',
            'item_id.*.item_id'=>'exists:items,id|numeric',
            'item_id.*.quantity'=>'numeric',
        ];
    }
    else
    {
        return [
            'type_order_id'=>'missing',
            'name'=>'string|required_unless:type_order_id,1',
            'address'=>'string|required_unless:type_order_id,1',
            'phone'=>'numeric|required_unless:type_order_id,1',
            'note'=>'string|nullable',
            'user_id'=>'missing',
            "state_order_id"=>["exists:state_orders,id","numeric",function (string $attribute, mixed $value, Closure $fail) {
                if ($value == 2&&$this->type_order_id==1) {
                    $fail("The {$attribute} is invalid.");
                }
            }],
            "promo_code"=>'string|nullable|exists:promo_codes,code',
            'item_id'=>'array',
            'item_id.*'=>'required_array_keys:item_id,quantity',
            'item_id.*.item_id'=>'exists:items,id|numeric',
            'item_id.*.quantity'=>'numeric',
        ];
    }
}
}