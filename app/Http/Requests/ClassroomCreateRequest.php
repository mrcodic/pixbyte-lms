<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClassroomCreateRequest extends FormRequest
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
            "title"         =>"required",
            "grade_id"      =>"required",
            "subject_id"    =>"required",
            "room_scheduel" =>"required",
            "absence_times" =>"required",
            "description"   =>"required",
            // "cover"         =>"required",
        ];
    }
}
