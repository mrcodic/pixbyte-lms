<?php

namespace App\Http\Requests\Api\Parent;

use App\Models\ParentStudent;
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
            'name_id' => 'required|exists:parents,name_id|max:255',
            'password' => 'required|string',
            'fcm'=>'required'
        ];
    }

}
