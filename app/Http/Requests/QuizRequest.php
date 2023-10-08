<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QuizRequest extends FormRequest
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
        return [
            'title'=>'required',
            'type'=>'required',
            'grade'=>'required',
            'price'=>'required_if:type,==,2',
            'room_id'=>'required_if:type,!=,1',
            'classroom_id'=>'required_if:type,==,2',
            'lock_after'=>'required_if:type,==,2',
            'question_bank_id'=>'required',
            'timer'=>'required_if:type!==,3',
            'score'=>'required_if:type!==,3',
            "questions"=>"required"
        ];
    }
    public function messages()
    {
        return[
         "questions.required"=>"please select questions question is required"
        ];
    }
}
