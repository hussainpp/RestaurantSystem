<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
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
            'name'=>'string',
            'email'=>"unique:users,email,{$this->route('user_id')},id|email",
            'password'=>"string",
            'phone'=>"numeric",
            "address"=>"string|nullable",
            "active"=>"boolean",
            "role_id"=>"exists:roles,id",
            "shift"=>"string|nullable",
            "salary"=>"numeric",
            "item_id"=>"exists:items,id"

        ];
    }
}
