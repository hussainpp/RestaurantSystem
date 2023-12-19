<?php

namespace App\Http\Requests;

use App\Models\item;
use Closure;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class OrderStoreRequest extends FormRequest
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
        if (Auth::check())
            return [
                'user_id' => 'missing',
                "state_order_id" => "missing",
                'type_order_id' => 'exists:type_orders,id|required',
                'name' => 'string|required_unless:type_order_id,1',
                'address' => 'string|required_unless:type_order_id,1',
                'phone' => 'numeric|digits_between:11,16|required_unless:type_order_id,1',
                'note' => 'string|nullable',
                "promo_code" => 'string|nullable|exists:promo_codes,code',

                'item' => 'array|required',
                'item.*' => 'required_array_keys:item_id,quantity',
                'item.*.item_id' => ["bail", "exists:items,id", function (string $attribute, int $value, Closure $fail) {
                    $item = item::find($value);
                    if (!$item->active) {
                        $fail("The {$attribute} field must be active.");
                    }
                }],
                'item.*.quantity' => 'numeric|min:1',

            ];
        else return [
            "type_order_id" => "missing",
            'user_id' => 'missing',
            "state_order_id" => "missing",
            'name' => 'string|required',
            'address' => 'string|required',
            'phone' => 'numeric|required|digits_between:11,16',
            'note' => 'string|nullable',
            "promo_code" => 'string|nullable|exists:promo_codes,code',

            'item' => 'array|required',
            'item.*' => 'required_array_keys:item_id,quantity',
            'item.*.item_id' => ["bail", "exists:items,id", function (string $attribute, int $value, Closure $fail) {
                $item = item::find($value);
                if (!$item->active) {
                    $fail("The {$attribute} field must be active.");
                }
            }],
            'item.*.quantity' => 'numeric|min:1',

        ];
    }
}
