<?php

namespace App\Http\Controllers\Api\Parent;

use App\Enums\Http;
use App\Helpers\MessageResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\ClassroomsResource;
use App\Http\Resources\Api\ExamResource;
use App\Http\Resources\Api\RoomResource;
use App\Models\Classroom;
use App\Models\Quiz;
use App\Models\Room;
use Illuminate\Http\Request;

class MainController extends Controller
{
    public function getClassrooms(Request $request){
        $classrooms=Classroom::whereHas('students',function($q){
            $q->where('user_id',auth()->user()->user->id);
        })->with('user','subject')->paginate($request->per_page??10);
        return new MessageResponse(
            message: 'Get classroom Data',
            code: Http::OK,
            body: [
                'classrooms' => ClassroomsResource::collection($classrooms),
                "pagination"=>getPagination($classrooms),
            ]
        );
    }
    public function getRooms(Request $request,$id){
        $classroom=Classroom::find($id);
        if(!$classroom){
            return new MessageResponse(
                message: 'classroom not found',
                code: Http::NOT_FOUND
            );
        }


        // $rooms=Room::whereHas('classroom',function($q) use($id){
        // $q->where('id',$id));

        // })->whereHas('lessons')->paginate(80);
        $rooms = Room::whereHas('lessons')->select('*')
        ->join('class_rooms', 'class_rooms.room_id', '=', 'rooms.id')
         ->where('class_rooms.classroom_id',$id)
        ->orderBy('class_rooms.room_order', 'asc')
        ->paginate($request->per_page??10);

        return new MessageResponse(
            message: 'Get rooms Data',
            code: Http::OK,
            body: [
                'rooms' => $rooms->map(function($data,$key) use($classroom,$rooms){
                    $missed=checkMissed($data->id,auth()->user()->user->id);
                    return [
                        "id"=>$data->id,
                        "room_key"=>"Room".sprintf('%02d', (count($classroom->rooms()->whereHas('lessons')->get()) -$key+$rooms->firstItem())),
                        "title"=>$data->title,
                        "missed"=>$missed,
                        "created_at"=>$data->created_at->format('Y M d'),

                    ];
                }),
                'classroom'=>$classroom?->title,
                'grade'=>$classroom->grade->grade->name,
                "pagination"=>getPagination($rooms),

            ]
        );
    }
    public function getExams(Request $request,$id){
        $classroom=Classroom::find($id);
         if(!$classroom){
            return new MessageResponse(
                message: 'classroom not found',
                code: Http::NOT_FOUND
            );
        }
        $exams=Quiz::where('type','!==',3)->where('classroom_id',$id)->paginate($request->per_page??10);
        return new MessageResponse(
            message: 'Get exams Data',
            code: Http::OK,
            body: [
                'exams' => ExamResource::collection($exams),
                "pagination"=>getPagination($exams),

            ]
        );
    }
}
