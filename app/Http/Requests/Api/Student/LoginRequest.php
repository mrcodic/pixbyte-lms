<?php

namespace App\Http\Requests\Api\Student;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class LoginRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'name_id' => 'required|exists:users,name_id|max:255',
            'password' => 'required|string',
            "fcm"      =>  'required'
        ];
    }
    public function withValidator(Validator $validator) : void
    {
        $user = User::where('name_id',request('name_id'))->first();
        $validator->after(function ($validator) use($user) {
            if(isset($user) && !in_array($user->type,[3,4])){
                $validator->errors()->add('user', 'user not student');

            }
        });
    }
}
