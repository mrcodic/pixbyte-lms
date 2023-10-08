<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
        // dd(request()->);
        return [
            "name_id"=>'unique:users,name_id,'.$this->id,
            "first_name"=>'required|string',
            "last_name"=>'required|string',
            "email"=>'required|email|unique:users,email,'.$this->id,
            "password"=>$password,
            "role_id"=>'required_if:type,2',
            "profile_image.*"=>'image|mimes:png,jpg,jpeg|max:2048',
        ];
    }
}
