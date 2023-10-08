<?php

namespace App\Jobs;

use App\Models\Attendance;
use App\Models\Classroom;
use App\Models\ClassroomStudent;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CheckAttendance implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $classroom_id,$room,$instructor_id,$type,$diff,$AuthUser,$flag;
    public function __construct($classroom_id,$room,$instructor_id,$type,$diff,$AuthUser,$flag)
    {
        $this->classroom_id=$classroom_id;
        $this->room=$room;
        $this->instructor_id=$instructor_id;
        $this->type=$type;
        $this->diff=$diff;
        $this->AuthUser=$AuthUser;
        $this->flag=$flag;

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        switch ($this->type){
            case "delete_room":
                if(is_array($this->room)){
                    Attendance::whereIn('instructor_id',$this->instructor_id)->whereIn('attendance_id',$this->room)->delete();
                }else{
                    Attendance::where('instructor_id',$this->instructor_id)->where('attendance_id',$this->room)->delete();
                }
                Log::info('success deleted Room ');

                break;
            case "update_room":
                foreach ($this->classroom_id as $classRoom) :
                    $classRoom = Classroom::find($classRoom);
                    if($this->diff && $classRoom){
                        if($this->flag=='add'){
                            $users = ClassroomStudent::where('classroom_id',$classRoom->id)->pluck('user_id')->toArray();
                            foreach ($users as $user) :
                                $attendance=Attendance::firstOrCreate(['attendance_id'=>$this->room->id,'attendance_type'=>'room','student_id'=>$user,'classroom_id'=>$classRoom->id,'instructor_id'=>$classRoom->instructor_id],['attendance_id'=>$this->room->id,'attendance_type'=>'room','student_id'=>$user,'classroom_id'=>$classRoom->id,'instructor_id'=>$classRoom->instructor_id]);
                                $desc='when update room: '.$this->room->title.'change no status ';
                                $student=User::find($user);
                                activity()
                                    ->performedOn($attendance)
                                    ->causedBy($attendance->instructor_id)
                                    ->withProperties(['attributes' => ["id" => $this->room->id, 'title' => $this->room->title,'take_action'=>$this->AuthUser->name, 'student' => $student->name, 'classroom' => $classRoom->id]])
                                    ->log($desc);

//                           foreach ($classRoom->quizes as $quizFromClass){
//                               Attendance::updateOrCreate(['attendance_id'=>$quizFromClass->id,'attendance_type'=>'quiz','student_id'=>$user,'classroom_id'=>$classRoom->id,'instructor_id'=>$classRoom->instructor_id,'status'=>4]);
//                           }
                            endforeach;
                        }else{
                            Attendance::where('attendance_id',$this->room->id)->where('instructor_id',$this->instructor_id)->where('classroom_id',$classRoom->id)->delete();
                        }

                   }

                endforeach;
                Log::info('success Updated Room Success ');

                break;
            case "request_room":
                    if($this->diff){
                        Attendance::where(['instructor_id'=>$this->instructor_id,'classroom_id'=>$this->classroom_id['current_class'],'student_id'=>$this->classroom_id['student_id']])->delete();
                        $classRooms=Classroom::find($this->classroom_id['another_class']);
                        $classRoomsCurrent=Classroom::find($this->classroom_id['current_class']);
                            foreach ($classRooms->rooms as $roomFromClass){
                                $attendance=Attendance::firstOrCreate(['attendance_id'=>$roomFromClass->id,'attendance_type'=>'room','student_id'=>$this->classroom_id['student_id'],'classroom_id'=>$classRooms->id,'instructor_id'=>$classRooms->user->id,'status'=>Null]);
                                $user=User::find($this->classroom_id['student_id']);
                                $desc='when student move from : '.$classRoomsCurrent->title.' to :'.$classRooms->title.' change room : ' .$roomFromClass->title.' status new student';
                                activity()
                                    ->performedOn($attendance)
                                    ->causedBy($attendance->instructor_id)
                                    ->withProperties(['attributes' => ["id" => $roomFromClass->id, 'title' => $roomFromClass->title,
                                        'take_action'=>$this->AuthUser->first_name .' '. $this->AuthUser->last_name,
                                        'student' => $user->name, 'classroom' => $classRooms->id]])
                                    ->log($desc);
                            }
                            foreach ($classRooms->quizes as $quizFromClass){
                                Attendance::updateOrCreate(['attendance_id'=>$quizFromClass->id,'attendance_type'=>'quiz','student_id'=>$this->classroom_id['student_id'],'classroom_id'=>$classRooms->id,'instructor_id'=>$classRooms->user->id,'status'=>4]);
                            }

                    }else{
                        $classRooms=Classroom::find($this->classroom_id['another_class']);
                        $classRoomsCurrent=Classroom::find($this->classroom_id['current_class']);
                        foreach ($classRooms->rooms as $roomFromClass){
                            $attendance=Attendance::firstOrCreate(['attendance_id'=>$roomFromClass->id,'attendance_type'=>'room','student_id'=>$this->classroom_id['student_id'],'classroom_id'=>$classRoomsCurrent->id,'instructor_id'=>$classRooms->user->id],['attendance_id'=>$roomFromClass->id,'attendance_type'=>'room','student_id'=>$this->classroom_id['student_id'],'classroom_id'=>$classRooms->id,'instructor_id'=>$classRooms->user->id,'status'=>Null]);
                            $user=User::find($this->classroom_id['student_id']);
                            $desc='when student move from : '.$classRoomsCurrent->title.' to :'.$classRooms->title.' change room : ' .$roomFromClass->title.' status new student';
                            activity()
                                ->performedOn($attendance)
                                ->causedBy($attendance->instructor_id)
                                ->withProperties(['attributes' => ["id" => $roomFromClass->id, 'title' => $roomFromClass->title,'take_action'=>$this->AuthUser->name, 'student' => $user->name, 'classroom' => $classRooms->id]])
                                ->log($desc);
                        }
                        foreach ($classRooms->quizes as $quizFromClass){
                            Attendance::updateOrCreate(['attendance_id'=>$quizFromClass->id,'attendance_type'=>'quiz','student_id'=>$this->classroom_id['student_id'],'classroom_id'=>$classRooms->id,'instructor_id'=>$classRooms->user->id,'status'=>4]);
                        }
                    }
                      Log::info('success request change ');
                break;

        }
    }
}
