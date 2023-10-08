<?php


use App\Models\Attendance;
use App\Models\CompleteLesson;
use App\Models\Result;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use \App\Http\Traits\HelperTrait ;
if(!function_exists('duration')){
    function duration($path){
        $getID3 = new \getID3;
        $file = $getID3->analyze($path);
        $duration = date('H:i:s', $file['playtime_seconds']);
        return $duration;
    }
}

if(!function_exists('get_instructor')){
    function get_instructor(){
        if(auth()->user()->instructor &&!empty(auth()->user()->instructor->instructor_id)){
            $instructor_id=auth()->user()->instructor->instructor_id;
        }else{
            $instructor_id=auth()->id();
        }
        return $instructor_id;
    }
}

if(!function_exists('adminDbTablesPermissions')) {
    /**
     * @return mixed
     */
    function adminDbTablesPermissions()
    {
        $adminPermissions = [
            'users', 'roles', 'classroom', 'rooms', 'lessons', 'coupons', 'students', 'gifts','attendance','student_rank','setting', 'category','subcategory','quiz','question','question_bank'
        ];
        return $adminPermissions;
    }
}


if(!function_exists('tblPermissions')){
    /**
     * @return mixed
     * This is function use for return all permission
     */

    function tblPermissions($tbl,$roles,$type)
    {
        $tblPermissions = Permission::where('name', 'LIKE', "%{$tbl}%")
            ->where('type',$type)
            ->where('guard_name',$roles ? $roles->where('type', $type)->first()->guard_name : 'web')
            ->get();

        return $tblPermissions;
    }
}

if(!function_exists('userPermissions')){
    /**
     * @return mixed
     */
    function userPermissions($tbl=null,$type=null,$role=null){
        if($tbl){
            $userPermissions = $role->getAllPermissions()
                ->filter(function ($q)use($tbl){
                    if (strpos($q->name, $tbl) !== false)
                        return $q;
                })
                ->pluck('id')->toArray();

        }else{
            $userPermissions = $role->getAllPermissions()->pluck('id')->toArray();
        }
        return $userPermissions;
    }
}
if(!function_exists('studentDbTablesPermissions')) {
    /**
     * @return mixed
     */
    function studentDbTablesPermissions()
    {
        $studentPermissions = [
            'classroom','rooms',
        ];
        return $studentPermissions;
    }
}
if(!function_exists('studentOnlineDbTablesPermissions')) {
    /**
     * @return mixed
     */
    function studentOnlineDbTablesPermissions()
    {
        $studentPermissions = [
            'classroom','rooms',
        ];
        return $studentPermissions;
    }
}
if(!function_exists('studentOfflineDbTablesPermissions')) {
    /**
     * @return mixed
     */
    function studentOfflineDbTablesPermissions()
    {
        $studentPermissions = [
            'classroom','rooms',
        ];
        return $studentPermissions;
    }
}

if(!function_exists('instructorDbTablesPermissions')) {
    /**
     * @return mixed
     */
    function instructorDbTablesPermissions()
    {
        $instructorPermissions = [
            'students', 'coupon', 'classroom', 'rooms', 'lessons', 'attendance','quizes','assignment'
        ];
        return $instructorPermissions;
    }
}
if(!function_exists('user_can_any')){
    /**
     * @param $table
     * @return bool
     */
    function user_can_any($table)
    {
        $user = auth('web')->user();

        return $user->can('read-'.$table) || $user->can('create-'.$table) ||
            $user->can('update-'.$table) || $user->can('delete-'.$table) ;
    }

}

if(!function_exists('couponType')){
    function couponType($type){

        switch ($type){
            case "1":
               return 'Single Room';
            case "2":
                return 'Room';
            case "3":
                return 'Classroom';
            case "4":
                return 'Grade';
            case "5":
                return 'Quiz';
            case "6":
                return 'Subscription';

        }
    }
}
if(!function_exists('getAmount')){
    function getAmount($input)
    {
        $input = number_format($input);
        $input_count = substr_count($input, ',');
        if ($input_count != '0') {
            if ($input_count == '1') {
                return substr($input, 0, -4) . 'k';
            } else if ($input_count == '2') {
                return substr($input, 0, -8) . 'm';
            } else if ($input_count == '3') {
                return substr($input, 0, -12) . 'b';
            } else {
                return;
            }
        } else {
            return $input;
        }
    }
}
if(!function_exists('generateCode')){
    function generateCode($prefix){
        if ($prefix){
         $char=Str::random(8);
         $lastCode=$prefix.$char;
        }else{
            $lastCode=Str::random(12);
        }
      $lastCode=rtrim(chunk_split(strtoupper($lastCode),4,'-'),'-');
      return $lastCode;

    }
}
if(!function_exists('getGradeId')){
    function getGradeId($class_room_id){
        $class_room=\App\Models\Classroom::findOrFail($class_room_id);

        return $class_room->grade->grade_id;

    }
}
if(!function_exists('unlockafter')){
function unlockafter($price,$unlock,$usedcoubon,$created_at){
//    dd($price,$unlock,$usedcoubon,$created_at);
    $day=$unlock;
    $created_att=($price && $price==0)?$created_at:$usedcoubon;
    $endDate = \Carbon\Carbon::parse($created_att)->addDays($day);

    $date1 = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $endDate);
    $date2 = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', now());

    $start = new DateTime($date1);
    $end = new DateTime($date2);
    $diff = $start->diff($end);
    $daysInSecs = $diff->d * 24 * 60 * 60;

    $hoursInSecs = $diff->h * 60 * 60;
    $minsInSecs = $diff->i * 60;
    $seconds = ($daysInSecs + $hoursInSecs + $minsInSecs + $diff->s)*1000;
    return $seconds;

}

}
if(!function_exists('unlockafterRoomDetail')){
    function unlockafterRoomDetail($price,$unlock,$usedcoubon){
        if($usedcoubon){
            if($unlock!==0){
                $day=$unlock;
                $created_at=$usedcoubon;
                $endDate = \Carbon\Carbon::parse($created_at)->addDays($day);
                $date1 = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $endDate);
                $date2 = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', now());
                $result = $date1->gt($date2);
                if($result){
                    $start = new DateTime($date1);
                    $end = new DateTime($date2);
                    $diff = $start->diff($end);
                    $daysInSecs = $diff->d;
                    $hoursInSecs = $diff->h ;
                    $minsInSecs = $diff->i;
                    return $daysInSecs.'Days'.' '.$hoursInSecs.'h'.' '.$minsInSecs.'m'." Left";
                }else{
                    return '';
                }
            }else{
                return 'Free';
            }

        }else{
            if($price==0){

            return 'Free';
            }else{
                return '';
            }

        }

    }

}

if(!function_exists('coronJopAttendence')){
    function coronJopAttendence($room,$student,$result,$class){

        $classroomStudent=\App\Models\ClassroomStudent::where('classroom_id',$class->id)->where('user_id',$student)->where('instructor_id',$class->user->id)->first();
        $attendance= Attendance::where('student_id',$student)->where('attendance_id',$room->id)->where('attendance_type','room')->first();
        if(Carbon::parse($room->created_at) > Carbon::parse($classroomStudent->created_at??Null)){
            if(!$attendance){
               $attendance= Attendance::updateOrCreate(['attendance_type'=>'room','attendance_id'=>$room->id,'student_id'=>auth('web')->id(),'classroom_id'=>$class->id,'instructor_id'=>$class->user->id],['attendance_type'=>'room','attendance_id'=>$room->id,'student_id'=>auth('web')->id(),'classroom_id'=>$class->id,'instructor_id'=>$class->user->id,'status'=>4]);
            }
       }
//        else{
//        $attendance= Attendance::updateOrCreate(['attendance_type'=>'room','attendance_id'=>$room->id,'student_id'=>auth('web')->id(),'classroom_id'=>$class->id,'instructor_id'=>$class->user->id],['attendance_type'=>'room','attendance_id'=>$room->id,'student_id'=>auth('web')->id(),'classroom_id'=>$class->id,'instructor_id'=>$class->user->id,'status'=>Null]);
//       }
        $roomCreated= DB::table('class_rooms')->where('classroom_id',$class->id)->where('room_id',$room->id)->first()->created_at;

        $created=($roomCreated)??$room->created_at;
        if($created >= $attendance->created_at){
            if($class->setting_missed){
                $lesson=CompleteLesson::where(['user_id'=>auth()->id(),'room_id'=>$room->id,'classroom_id'=>$class->id])->get();
                $progress=getProgress(count($lesson),count($room->lessons));
                $attendance= Attendance::where('student_id',$student)->where('attendance_id',$room->id)->first();
                $day=$room->unlock_after;
                $created_at=($attendance)?$attendance->created_at:Null;
                $endDate = \Carbon\Carbon::parse($created_at)->addDays($day);
                $date1 = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $endDate);
                $date2 = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', now());

                $result2 = $date1->gt($date2);

                if(!$result && !$result2 && $progress!=100){
                    $attendance= Attendance::where('student_id',$student)->where('attendance_id',$room->id)->first();
                    if($attendance){
                        if($attendance->status==3 || $attendance->status==1){
                            // $attendance->update(['status'=>4]); ->New student
                        }elseif($attendance->status==2){
                            $attendance->update(['status'=>0]);//update here
                        }
                        elseif(!$attendance->status){

                            $attendance->update(['status'=>0]);//update here
                        }
//                    if($attendance->status!==0){
                        $classRoom=$class->title;
                        $text='<i><bold>'. $room->title.'</bold></i>'. ' has been missed Hurry up and contact your instructor to prevent suspension. ';
                        $textPhone='('. $room->title.')'. ' has been missed Hurry up and contact your instructor to prevent suspension. ';
                        \App\Jobs\NotificationJop::dispatch($classRoom,$room,$text,auth()->user(),$redirect=null,$textPhone,'3');//students

                        $textInstructor='<i><bold>'. $room->title.'</bold></i>'."has been missed by "."<i><bold>".auth()->user()->name."</bold></i>";
                        $textInstructorPhone='('. $room->title.')'."has been missed by "."(".auth()->user()->name.")";
                        \App\Jobs\NotificationJop::dispatch($classRoom,$room,$textInstructor,$class->user,$redirect=null,$textInstructorPhone,'3');//instructor

                        $textParent="Your Student missed this room <i><bold>".$room->title."</i></bold> Please contact us for more information.";
                        $textParentPhone="Your Student missed this room".$room->title." Please contact us for more information.";
                        \App\Jobs\NotificationJop::dispatch($classRoom,$room,$textParent,auth()->user()->parent,$redirect=null,$textParentPhone,'3');//instructor

//                    }

                    }

                }
            }
            }


    }

}
if(!function_exists('coronJopAbsence')){
    function coronJopAbsence($student,$class){
        $missed=Attendance::where('student_id',$student)->where('classroom_id',$class->id)->where('instructor_id',$class->user->id)->where('attendance_type','room')->whereIn('status',[0,2,3])->count();
        if($missed >= (int)$class->absence_times){
            if( \App\Models\ClassroomStudent::where('user_id',$student)->where('classroom_id',$class->id)->where('user_id',auth()->id())->first()->block==0){
                \App\Models\ClassroomStudent::where('user_id',$student)->where('classroom_id',$class->id)->where('user_id',auth()->id())->first()->update(['block'=>1]);
                $text='Bad News you have been suspended due to the absence limitation being exceeded! Please get in touch with your Instructor Immediately to prevent <i><bold>Permanent Removal</bold></i>.';
                $textPhone='Bad News you have been suspended due to the absence limitation being exceeded! Please get in touch with your Instructor Immediately to prevent ( Permanent Removal).';

                \App\Jobs\NotificationJop::dispatch($class,null,$text,auth()->user(),$redirect=null,$textPhone,'3');
                $textInstructor='<i><bold>'.auth()->user()->name.'</bold></i>'." has been suspended ";
                $textInstructorePhone='('.auth()->user()->name.')'." has been suspended ";
                \App\Jobs\NotificationJop::dispatch($class,null,$textInstructor,$class->user,$redirect=null,$textInstructorePhone,'3');//instructor

                $textParent="Bad News your student has been suspended due to the absence limitation being exceeded! Please contact us immediately to prevent (Permanent Removal).";
                $textParenPhone="Bad News your student has been suspended due to the absence limitation being exceeded! Please contact us immediately to prevent (Permanent Removal).";
                \App\Jobs\NotificationJop::dispatch($class,null,$textParent,auth()->user()->parent,$redirect=null,$textParenPhone,'3');//instructor

            }

        }
    }

}


if(!function_exists('coronJopAttendenceExam')){
    function coronJopAttendenceExam($exam,$student){

        $quizUser=$exam->quizUser;
        $class=$exam->classroom;

        if(!$quizUser){
            $quizUser= Attendance::updateOrCreate(['attendance_type'=>'quiz','attendance_id'=>$exam->id,'student_id'=>auth('web')->id(),'classroom_id'=>$class->id,'instructor_id'=>$class->user->id],['attendance_type'=>'quiz','attendance_id'=>$exam->id,'student_id'=>auth('web')->id(),'classroom_id'=>$class->id,'instructor_id'=>$class->user->id,'status'=>4]);
        }else{
            $quizUser=$quizUser;
        }


           if($quizUser){
               $day=$exam->lock_after;
               $created_at=$quizUser->created_at;
               $endDate = \Carbon\Carbon::parse($created_at)->addDays($day);
               $date1 = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $endDate);
               $date2 = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', now());

               $result = $date1->gt($date2);

           }
           if(!$result){
               $quizUser->update(['status'=>0]);
               if( \App\Models\ClassroomStudent::where('user_id',$student)->where('classroom_id',$class->id)->where('user_id',auth()->id())->first()->block !==1) {
                   $text='Bad News you have been suspended due to the absence limitation being exceeded! Please get in touch with your Instructor Immediately to prevent <i><bold>Permanent Removal</bold></i>.';
                   $textPhone='Bad News you have been suspended due to the absence limitation being exceeded! Please get in touch with your Instructor Immediately to prevent (Permanent Removal).';

                   \App\Jobs\NotificationJop::dispatch($class,null,$text,auth()->user(),$redirect=null,$textPhone,'3');
                   $textInstructor='<i><bold>'.auth()->user()->name.'</bold></i>'." has been suspended ";
                   $textInstructorPhone='('.auth()->user()->name.')'." has been suspended ";
                   \App\Jobs\NotificationJop::dispatch($class,null,$textInstructor,$class->user,$redirect=null,$textInstructorPhone,'3');//instructor
                   $textParent="Bad News your student has been suspended due to the absence limitation being exceeded! Please contact us immediately to prevent (Permanent Removal).";
                   $textParenPhone="Bad News your student has been suspended due to the absence limitation being exceeded! Please contact us immediately to prevent (Permanent Removal).";
                   \App\Jobs\NotificationJop::dispatch($class,null,$textParent,auth()->user()->parent,$redirect=null,$textParenPhone,'3');//instructor

                }
                   \App\Models\ClassroomStudent::where('user_id',$student)->where('classroom_id',$class->id)->where('user_id',auth()->id())->first()->update(['block'=>1]);

           }
           return $result;



    }
}

if(!function_exists('getProgress')){
    function getProgress($lessonComplete,$lessonCount){

        if($lessonCount!=0){
                $progress=($lessonComplete/(int)$lessonCount)*100;
            }else{
                $progress=0;
                }
         return floor($progress);

    }
}
if(!function_exists('totalPrice')){
    function totalPrice($coupons){
$sum=0;
       foreach ($coupons as $coupon){
         $sum=$sum+(int)$coupon->price;
       }
        return floor($sum);

    }
}
if(!function_exists('attendanceStatus')){
    function attendanceStatus($status){
        switch ($status){
            case "0":
                return 'Absent';
            case "1":
                return 'Present';
            case "2":
                return "Absent with excuse";
            case "3":
                return "left/leave early with justification";
            case "4":
                return 'New student';
            default:
                return "No Action";

        }

    }
}
if(!function_exists('checkMissed')){
    function checkMissed($room,$student){
        $missed=\App\Models\Attendance::where('student_id',$student)->where('attendance_id',$room)->whereIn('status',[0,2,3])->first();
        if($missed){
            return true;
        }
        return false;
    }
}
if(!function_exists('checkTakeQuiz')){
     function checkTakeQuiz($quiz){

        $result=0;
        foreach ($quiz as $item){
            if($item->result==null || ($item->result && (int)checkScore($item->result->total_correct_answer,count($item->questions)) <=(int)$item->score)){
                $result=['id'=>$item->id,'name'=>$item->title,'type'=>$item->type];
            }
        }
        return $result;
    }
}
if(!function_exists('checkScore')){
    function checkScore($points,$numQiuz){

       if($points!=0){
           if($numQiuz!==0){
               $percentage=(($points/$numQiuz)*100);
               return floor($percentage);
           }
           else{
               return 0;
           }
       }else{
           return 0 ;
       }
    }
}
if(!function_exists('checkCompleteExam')){
    function checkCompleteExam($quiz_id,$user){
        $result=Result::where(['student_id'=>$user,'quiz_id'=>$quiz_id])->first();
        if($result){
                return true;
        }else{
            return false;
        }
    }
}
if(!function_exists('checkMissedExam')){
    function checkMissedExam($exam,$student){
        $missed=Attendance::where('student_id',$student)->where('attendance_id',$exam->id)->whereIn('status',[0,2,3])->first();

        if($missed){
            return true;
        }
        return false;

    }
}
if(!function_exists('checkAbsence')){
    function checkAbsence($student,$class){
            $result=\App\Models\ClassroomStudent::where('user_id',$student)->where('classroom_id',$class->id)->where('instructor_id',auth()->id())->first();
        if($result){
            return true;
        }
        return false;

    }

}

if(!function_exists('grade')){
    function grade($id){
       return \App\Models\Grade::where('id',$id)->first()->name;
    }
}
if(!function_exists('Order')){
    function Order($id,$roomIndex){
        $classrooms=\App\Models\Classroom::where('id',$id)->count();
        return $classrooms-$roomIndex;
    }
}

if(!function_exists('roomOrder')){
    function roomOrder($room,$classroom){
       $room_order=DB::table('class_rooms')->where('room_id',$room)->where('classroom_id',$classroom)->first();
        return $room_order->room_order;

    }
}
if(!function_exists('getpermission')){
    function getpermission($permissions){
        $array=[];
       foreach ($permissions as $permission){
           foreach (instructorDbTablesPermissions() as $tbl){
               if (str_contains($permission->name, $tbl) !== FALSE) {
                   $array[]=$tbl;
               }
           }
       }
       return array_unique($array);

    }
}
if(!function_exists('getpermission')){
    function getpermission($permissions){
        $array=[];
       foreach ($permissions as $permission){
           foreach (instructorDbTablesPermissions() as $tbl){
               if (str_contains($permission->name, $tbl) !== FALSE) {
                   $array[]=$tbl;
               }
           }
       }
       return array_unique($array);

    }
}
if(!function_exists('getSubCatName')){
    function getSubCatName($id){
        $subCat=\App\Models\SubCategory::find($id);
       return $subCat->name;

    }
}
if(!function_exists('getPagination')){
    function getPagination($model){
        $arr=[
                 'total' => $model->total(),
                'per_page' => $model->perPage(),
                'current_page' => $model->currentPage(),
                'last_page' => $model->lastPage(),
                'from' => $model->firstItem(),
                'to' => $model->lastItem(),
        ];
       return $arr;

    }
}
