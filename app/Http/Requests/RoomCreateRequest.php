<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RoomCreateRequest extends FormRequest
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
            'title'         => 'required',
            'price'         => 'nullable',
            'description'   => 'required',
        //    'class_room_id'    => (!request()->method()=="PATCH")?((!request('classRoomId'))?'required':'sometimes'):"required",
            "unlock_after"=>"required_if:price,!==,0|nullable|numeric"
        ];
    }
}
