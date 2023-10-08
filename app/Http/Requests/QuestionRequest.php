<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QuestionRequest extends FormRequest
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
//dd(request()->all());
        return [
            "title"=>"required",
            "name"=>"required",
            "category_id"=>"required",
            "subcategory_id"=>"required",
            "answers"=>"required|array",
            "answers.*.valueInput"=>"required_if:answers.*.status,false",
            "answers.*.valueCk"=>"required_if:answers.*.status,true",
            "answers.0.correct"=>"required_if:answers.0.correct,0",
        ];
    }
}
