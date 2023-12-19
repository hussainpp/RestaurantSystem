<?php

namespace App\Http\Requests;

use App\Models\order;
use Closure;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Validator;

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
        if (Auth::check()) {
            return [
                'type_order_id' => 'exists:type_orders,id|numeric',
                'name' => 'string', 
                'address' => 'string', 
                'phone' => 'numeric|digits_between:11,16',
                'note' => 'string|nullable',
                'user_id' => 'missing',
                "state_order_id" => ["exists:state_orders,id", "numeric", function (string $attribute, mixed $value, Closure $fail) {
                    if ($value == "2" && $this->type_order_id == "1") {
                        $fail("The {$attribute} is invalid.");
                    }
                }],
                "promo_code" => 'string|nullable|exists:promo_codes,code',
             
            ];
        } else {
            return [
                'user_id' => 'missing',
                'type_order_id' => 'missing',
                "state_order_id" => 'missing',
                
                'name' => 'string',
                'address' => 'string',
                'phone' => 'numeric|digits_between:11,16',
                'note' => 'string|nullable',
                "promo_code" => 'string|nullable|exists:promo_codes,code',
    
            ];
        }
    }

    function after()
    {
        $or = order::find($this->route('order_id'));
        return [
            function (Validator $validator) use ($or) {
                if (
                    $this->type_order_id != null&&$this->type_order_id != 1 && $or->type_order_id != $this->type_order_id
                ) {
                    $this->name == null? $validator->errors()->add('name','The name is required'):0;
                    $this->phone == null? $validator->errors()->add('phone','The phone is required.'):0;
                    $this->address == null? $validator->errors()->add('address',"The address is required."):0;
                }
            }
        ];
    }
   
}
