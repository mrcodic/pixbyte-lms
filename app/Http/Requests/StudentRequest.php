<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StudentRequest extends FormRequest
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
            $password='sometimes|nullable|confirmed|min:6';
        }else{
            $password='required|confirmed|min:6';
        }
        return [
            "first_name"=>'required|string',
            "last_name"=>'required|string',
            "email"=>'required|email|unique:users,email,'.$this->id,
            "phone"=>'required|numeric',
            "password"=>$password,
            "grade_id"=>'required',
            "profile_image"=>'image|mimes:png,jpg,jpeg|max:2048',
        ];
    }
}
