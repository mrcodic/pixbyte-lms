<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class AdminRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        if(request()->method=='PATCH'){
            $password='sometimes|nullable|min:6';
        }else{
            $password='required|min:6';
        }
        return [
            "name"=>'required|string',
            "email"=>'required|email|unique:admins,email,'.$this->id,
            "password"=>$password,
            "role_id"=>'required',
        ];
    }
}
