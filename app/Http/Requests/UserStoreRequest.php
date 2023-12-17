<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserStoreRequest extends FormRequest
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
            'name'=>'required|string',
            'email'=>"required|unique:users,email|email",
            'password'=>"required|string|confirmed",
            //'repeatPassword'=>"required|confirmed",
            'phone'=>"required|numeric",
            "address"=>"string|nullable",
            "active"=>"required|boolean",
            "role_id"=>"required|exists:roles,id",
            "shift"=>"nullable|regex:/^\d{1,2}-\d{1,2}$/",
            "salary"=>"required|numeric",
            "item_id"=>"required_if:role_id,4|exists:items,id|missing_unless:role_id,4"
        ];
    }
}
