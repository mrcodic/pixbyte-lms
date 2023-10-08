<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GenerateCouponRequest extends FormRequest
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
           "prefix_code"=>'nullable|sometimes|min:4|max:4',
           "num_coupon"=>'required|numeric',
            "type"=>"required|in:1,2,3,4,5,6",
            "instructor_id"=>'required',
            "price"=>'required|numeric',
            "room_id"=>"required_if:type,==,2",
            "classroom_id"=>"required_if:type,6,3",
            "grade_id"=>"required_if:type,==4",
            "quiz_id"=>"required_if:type,==5",
            "date_subscription_from"=>"required_if:type,6",
            "date_subscription_to"=>"required_if:type,6",

        ];
    }
}
