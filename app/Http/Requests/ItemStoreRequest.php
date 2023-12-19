<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ItemStoreRequest extends FormRequest
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
            'name'=>"required|string|unique:items,name",
            'price'=>"required|numeric",
            'details'=>"string|nullable",
            'preparation_time'=>'required|numeric',
            'menu_id'=>'required|exists:menus,id',
            'active'=>'required|boolean',
            'image'=>'required|image|max:5000',
        ];
    }
}
