<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ItemUpdateRequest extends FormRequest
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
            'name'=>"string|unique:items,name,{$this->route('item_id')},id",
            'price'=>"numeric",
            'details'=>"string|nullable",
            'preparation_time'=>'numeric',
            'menu_id'=>'exists:menus,id',
            'active'=>'boolean',
            'image'=>'image|max:10000'
        ];
    }
}
