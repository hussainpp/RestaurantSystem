<?php

namespace App\Http\Requests;

use App\Models\item;
use Closure;
use Illuminate\Foundation\Http\FormRequest;

class OrderUpdateItemRequest extends FormRequest
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
        return [
            'item'=> "array|required",
            'item.*'=>'required_array_keys:item_id,quantity,order_id',
            'item.*.item_id'=>["bail","exists:items,id",
            function (string $attribute, int $value, Closure $fail) {
                $item=item::find($value);
                if (!$item->active) {
                    $fail("The {$attribute} field must be active.");
                }
            }],
            'item.*.order_id'=>"exists:orders,id",
            'item.*.quantity'=>'numeric|min:1',         
        ];
    }
}
