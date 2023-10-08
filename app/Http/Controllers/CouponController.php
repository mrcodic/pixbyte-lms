<?php

namespace App\Http\Controllers;

use App\Exports\CouponExport;
use App\Http\Requests\CouponRequest;
use App\Http\Requests\GenerateCouponRequest;
use App\Http\Resources\CouponResource;
use App\Models\Attendance;
use App\Models\Classroom;
use App\Models\ClassroomStudent;
use App\Models\Coupon;
use App\Models\CouponUsed;
use App\Models\Grade;
use App\Models\Quiz;
use App\Models\Room;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class CouponController extends Controller
{
    public function __construct()
    {

        $this->middleware('permission:read-coupon|create-coupon|update-coupon|delete-coupon', ['only' => ['index','show','export']]);
        $this->middleware('permission:create-coupon', ['only' => ['create','store']]);
        $this->middleware('permission:update-coupon', ['only' => ['edit','update']]);
        $this->middleware('permission:delete-coupon', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $rooms=Room::where('user_id',get_instructor())->select('id','title')->get();
        $classrooms=Classroom::where('instructor_id',get_instructor())->select('id','title')->get();
        $grades=Grade::select('id','name')->get();
        $quizzes=Quiz::where('user_id',get_instructor())->select('id','title')->get();
        return  view('coupon.index',get_defined_vars());
    }
    public function get_coupon_data(Request $request){


        $start = request('start');
        $limit = (request('length')) ? (request('length') != "-1" ? request('length') : 5000 ) : 10;
        $room = Coupon::where('instructor_id',get_instructor())->filter($request)->with(['classroom:id,title','grade:id,name'])->orderBy('id',"desc")->skip($start)->take($limit)->get();;
        $rooms= CouponResource::collection($room);
        return datatables($rooms)
            ->setOffset($start)->with(['recordsTotal'=>Coupon::where('instructor_id',get_instructor())->count(), "recordsFiltered" => Coupon::where('instructor_id',get_instructor())->filter($request)->count(),'start' => $start])->
            make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Htgenerate_couponstp\Response
     */
    public function create()
    {

        $rooms=Room::where('user_id',get_instructor())->select('id','title')->get();
        $classrooms=Classroom::where('instructor_id',get_instructor())->select('id','title')->get();
        $grades=Grade::select('id','name')->get();
        $quizzes=Quiz::where('user_id',get_instructor())->select('id','title')->get();

        return view('coupon.create',get_defined_vars());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CouponRequest $request)
    {
        Coupon::create($request->all());
        return  redirect()->route('coupon.index')->with(['message'=>"created Success",'alert-type'=>'success']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Coupon  $coupon
     * @return \Illuminate\Http\Response
     */
    public function show(Coupon $coupon)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Coupon  $coupon
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request,Coupon $coupon)
    {

        $quizzes=Quiz::where('user_id',get_instructor())->select('id','title')->get();
        $rooms=Room::where('user_id',get_instructor())->select('id','title')->get();
        $classrooms=Classroom::where('instructor_id',get_instructor())->select('id','title')->get();
        $grades=Grade::select('id','name')->get();
        $pageTbl = $request->pageTbl ?? null;

        return view('coupon.edit',compact('rooms','classrooms','grades','coupon','quizzes','pageTbl'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Coupon  $coupon
     * @return \Illuminate\Http\Response
     */
    public function update(CouponRequest $request, Coupon $coupon)
    {
//        dd($request->all());
        $input=$request->except('room_id','pageTbl');

        if($input['type']==1 || $input['type']==2){
            $input['classroom_id']=Null;
            $input['grade_id']=Null;
        }elseif($input['type']==3){

            $input['room_id']=Null;
            $input['grade_id']=Null;
        }elseif($input['type']==4){
            $input['room_id']=Null;
            $input['classroom_id']=Null;
        }
        $coupon->update($input);
        return  redirect()->route('coupon.index')->with(['message'=>"Updated Success",'alert-type'=>'success', 'pageTbl' => $request->pageTbl ?? null]);


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Coupon  $coupon
     * @return \Illuminate\Http\Response
     */
    public function destroy(Coupon $coupon)
    {
        $coupon->delete();
        return response()->json(['status'=>true,'message'=>'deleted Successfully'],200);

    }
    public function delete_selected(Request $request){
        Coupon::whereIn('id',$request->idds)->delete();
        return response()->json(['status'=>true,'message'=>'deleted Successfully'],200);
    }
    public function generate_coupons(GenerateCouponRequest $request){
          $idsCoupon=[];
        for ($i=1;$i <=(integer)$request->num_coupon ; $i++){
            $request['code']=generateCode($request->prefix_code);
            $coupon=Coupon::create(['code'=>$request->code,'room_id'=>($request->room_id)??NULL,'classroom_id'=>$request->classroom_id??NULL,'type'=>$request->type
            ,"price"=>$request->price,'quiz_id'=>($request->quiz_id)??NULL,"instructor_id"=>$request->instructor_id,
            'grade_id'=>$request->grade_id??NULL,
            "num_used"=>$request->num_used,
            'date_subscription_from'=>$request->date_subscription_from??NULL,
            'date_subscription_to'=>$request->date_subscription_to??NULL,

            ]);
            $idsCoupon[]=$coupon->id;
        }
        return response()->json(['status'=>true,'message'=>'created Successfully','data'=>$idsCoupon],200);

    }
    public function export_coupon(Request $request){
        $ids=$request->all();
        $excel = Excel::download(new CouponExport($ids), 'coupons.csv' , \Maatwebsite\Excel\Excel::CSV, [
            'Content-Type' => 'text/csv',
        ]);
        ob_end_clean();
        return $excel;
    }
    public function unlock_code(Request $request){
        $request->validate([
            'code' => ['required']
        ]);
        if($request->quiz_id){
            $couponType='quiz';
            $room=Quiz::where('id',$request->quiz_id)->first();

        }else{
            if($request->type && $request->type=='subscription'){
                $couponType='subscription';
            }else{
                $couponType='room';

            }
            $room=Room::where('id',$request->room_id)->first();

        }
        $coupon=preg_replace('/\s+/', '', $request->code);
        $result=false;
         $getCoupon=Coupon::where('code',$coupon)->first();

         $classroomstudent=ClassroomStudent::where('user_id',auth()->id())->pluck('classroom_id')->toArray();
         $classes=Classroom::whereIn('id',$classroomstudent)->get();

         $classroomrequest=Classroom::where('id',$request->classroom_id)->first();
         $gradeRequest=$classroomrequest->grade->grade_id;
         $subjectRequest=$classroomrequest->subject_id;
        $found='';
         foreach ($classes as $class){
             if($class->subject_id==$subjectRequest){
                 $found=1;
             }else{
                 $found=0;
             }
         }
       if($found==1){
           if($getCoupon){
               if($getCoupon->room_id){
                   if(!$request->quiz_id){
                       if(in_array($room->id,explode(',',$getCoupon->room_id))){
                           if($getCoupon->price==$room->price){
                            if( $getCoupon->status && (int)$getCoupon->num_used > 1){
                                      if($getCoupon->student_id == auth()->id()){
                                        if(count($getCoupon->used)< (int)$getCoupon->num_used){
                                            CouponUsed::create(['coupon_id'=>$getCoupon->id,'coupon_used_id'=>$room->id,'user_id'=>auth()->id(),'classroom_id'=>$request->classroom_id,'coupon_used_type'=>$couponType]);
                                        }else{
                                            if($request->ajax()){
                                                return  response()->json(['status'=>false,'message'=>"this coupon must be less than num used"],200);
                                            }else{
                                                return  redirect()->back()->with(['message'=>"this coupon must be less than num used",'alert-type'=>'error']);
                                            }
                                        }
                                      }else{
                                        if($request->ajax()){
                                            return  response()->json(['status'=>false,'message'=>"this coupon assign to another student"],200);
                                        }else{
                                            return  redirect()->back()->with(['message'=>"this coupon assign to another student",'alert-type'=>'error']);
                                        }
                                      }
                            }else{
                                $getCoupon->update(['status'=>1,'student_id'=>auth()->id()]);
                                CouponUsed::create(['coupon_id'=>$getCoupon->id,'coupon_used_id'=>$room->id,'user_id'=>auth()->id(),'classroom_id'=>$request->classroom_id,'coupon_used_type'=>$couponType]);
                                $room->update(['status'=>1]);//make unlock
                            }

                               $result=true;
                           }else{
                               if($request->ajax()){
                                   return  response()->json(['status'=>false,'message'=>"price coupon not equal price room"],200);
                               }else{
                                   return  redirect()->back()->with(['message'=>"price coupon not equal price room",'alert-type'=>'error']);
                               }
                           }
                       }else{
                           if($request->ajax()) {
                               return  response()->json(['status'=>false,'message'=>"Coupon Not Found or Used Before"],400);

                           }else{
                               return  redirect()->back()->with(['message'=>"Coupon Not Found or Used Before",'alert-type'=>'error']);
                           }
                       }
                   }else{
                       if($request->ajax()) {
                           return  response()->json(['status'=>false,'message'=>"Coupon Not Found or Used Before"],400);

                       }else{
                           return  redirect()->back()->with(['message'=>"Coupon Not Found or Used Before",'alert-type'=>'error']);
                       }
                   }

               }
               if($getCoupon->classroom_id){
                   if(in_array($request->classroom_id,explode(',',$getCoupon->classroom_id))){
                      if($couponType=='room'){
                        if($getCoupon->price==$room->price) {
                            if( $getCoupon->status && (int)$getCoupon->num_used > 1){
                                if($getCoupon->student_id == auth()->id()){
                                    if(count($getCoupon->used)< (int)$getCoupon->num_used){
                                        CouponUsed::create(['coupon_id'=>$getCoupon->id,'coupon_used_id'=>$room->id,'user_id'=>auth()->id(),'classroom_id'=>$request->classroom_id,'coupon_used_type'=>$couponType]);
                                    }else{
                                        if($request->ajax()){
                                            return  response()->json(['status'=>false,'message'=>"this coupon must be less than num used"],200);
                                        }else{
                                            return  redirect()->back()->with(['message'=>"this coupon must be less than num used",'alert-type'=>'error']);
                                        }
                                    }
                                  }else{
                                    if($request->ajax()){
                                        return  response()->json(['status'=>false,'message'=>"this coupon assign to another student"],200);
                                    }else{
                                        return  redirect()->back()->with(['message'=>"this coupon assign to another student",'alert-type'=>'error']);
                                    }
                                  }
                            }else{
                                $getCoupon->update(['status' => 1, 'student_id' => auth()->id()]);
                                CouponUsed::create(['coupon_id'=>$getCoupon->id,'coupon_used_id'=>$room->id,'user_id'=>auth()->id(),'classroom_id'=>$request->classroom_id,'coupon_used_type'=>$couponType]);

                            }

                            $result=true;

                        }else{
                            if($request->ajax()){
                                return  response()->json(['status'=>false,'message'=>"price coupon not equal price room"],400);

                            }else{

                            return  redirect()->back()->with(['message'=>"price coupon not equal price room",'alert-type'=>'error']);
                            }
                        }
                      }else{
                        if($getCoupon->type=='6'){
                          $check_date_current=  $this->checkDateCurrent($getCoupon->date_subscription_from,$getCoupon->date_subscription_to);
                          if($check_date_current){
                            $getCoupon->update(['status' => 1, 'student_id' => auth()->id()]);
                            CouponUsed::create(['coupon_id'=>$getCoupon->id,'coupon_used_id'=>$request->classroom_id,'user_id'=>auth()->id(),'classroom_id'=>$request->classroom_id,'coupon_used_type'=>$couponType]);
                          }
                        }else{
                            if($request->ajax()){
                                return  response()->json(['status'=>false,'message'=>"this coupon not coupon subscription"],400);

                            }else{

                            return  redirect()->back()->with(['message'=>"this coupon not coupon subscription",'alert-type'=>'error']);
                            }
                        }
                      }


                   }else{
                    if($request->ajax()){
                        return  response()->json(['status'=>false,'message'=>"this coupon not found in your classroom"],400);

                    }else{
                        return  redirect()->back()->with(['message'=>"this coupon not found in your classroom",'alert-type'=>'error']);
                    }
                   }
               }
               if($getCoupon->grade_id){
                   if(in_array($request->grade_id,explode(',',$getCoupon->grade_id))){
                       if($getCoupon->price==$room->price) {
                        if( $getCoupon->status && (int)$getCoupon->num_used > 1){
                            if($getCoupon->student_id == auth()->id()){
                                if(count($getCoupon->used)< (int)$getCoupon->num_used){
                                    CouponUsed::create(['coupon_id'=>$getCoupon->id,'coupon_used_id'=>$room->id,'user_id'=>auth()->id(),'classroom_id'=>$request->classroom_id,'coupon_used_type'=>$couponType]);
                                }else{
                                    if($request->ajax()){
                                        return  response()->json(['status'=>false,'message'=>"this coupon must be less than num used"],200);
                                    }else{
                                        return  redirect()->back()->with(['message'=>"this coupon must be less than num used",'alert-type'=>'error']);
                                    }
                                }
                              }else{
                                if($request->ajax()){
                                    return  response()->json(['status'=>false,'message'=>"this coupon assign to another student"],200);
                                }else{
                                    return  redirect()->back()->with(['message'=>"this coupon assign to another student",'alert-type'=>'error']);
                                }
                              }
                        }else{
                            $getCoupon->update(['status' => 1, 'student_id' => auth()->id()]);
                            CouponUsed::create(['coupon_id'=>$getCoupon->id,'coupon_used_id'=>$room->id,'user_id'=>auth()->id(),'classroom_id'=>$request->classroom_id,'coupon_used_type'=>$couponType]);

                        }
                          $result=true;

                       }else{
                           if($request->ajax()){
                               return  response()->json(['status'=>false,'message'=>"price coupon not equal price room"],400);

                           }else{
                           return  redirect()->back()->with(['message'=>"price coupon not equal price room",'alert-type'=>'error']);
                           }
                       }

                   }
               }
               if($getCoupon->quiz_id){
                   if(in_array($request->quiz_id,explode(',',$getCoupon->quiz_id))){
                       if($getCoupon->price==$room->price) {
                        if( $getCoupon->status && (int)$getCoupon->num_used > 1){
                            if($getCoupon->student_id == auth()->id()){
                                if(count($getCoupon->used)< (int)$getCoupon->num_used){
                                    CouponUsed::create(['coupon_id'=>$getCoupon->id,'coupon_used_id'=>$room->id,'user_id'=>auth()->id(),'classroom_id'=>$request->classroom_id,'coupon_used_type'=>$couponType]);
                                }else{
                                    if($request->ajax()){
                                        return  response()->json(['status'=>false,'message'=>"this coupon must be less than num used"],200);
                                    }else{
                                        return  redirect()->back()->with(['message'=>"this coupon must be less than num used",'alert-type'=>'error']);
                                    }
                                }
                              }else{
                                if($request->ajax()){
                                    return  response()->json(['status'=>false,'message'=>"this coupon assign to another student"],200);
                                }else{
                                    return  redirect()->back()->with(['message'=>"this coupon assign to another student",'alert-type'=>'error']);
                                }
                              }
                        }else{
                            $getCoupon->update(['status' => 1, 'student_id' => auth()->id()]);
                            CouponUsed::create(['coupon_id'=>$getCoupon->id,'coupon_used_id'=>$request->quiz_id,'user_id'=>auth()->id(),'classroom_id'=>$request->classroom_id,'coupon_used_type'=>$couponType]);

                        }
                             $result=true;

                       }else{
                           if($request->ajax()){
                               return  response()->json(['status'=>false,'message'=>"price coupon not equal price room"],400);
                           }else{
                               return  redirect()->back()->with(['message'=>"price coupon not equal price room",'alert-type'=>'error']);
                           }
                       }

                   }
               }
               if($result==true){
                   $classroom=Classroom::findOrFail($request->classroom_id);
                   DB::table('classroom_students')->updateOrInsert(['instructor_id'=>$classroom->instructor_id,'user_id'=>auth()->id(),'classroom_id'=>$classroom->id]);
                   $new_attendance=Attendance::updateOrCreate(['instructor_id'=>$classroom->instructor_id,'student_id'=>auth()->id(),'classroom_id'=>$classroom->id,'attendance_id'=>$room->id],['instructor_id'=>$classroom->instructor_id,'student_id'=>auth()->id(),'classroom_id'=>$classroom->id,'attendance_id'=>$room->id,'attendance_type'=>$couponType,'status'=>1, 'first_attendance'=>1, 'second_attendance'=>1]);
                   $attendanceCheckNull=Attendance::where(['instructor_id'=>$classroom->instructor_id,'classroom_id'=>$classroom->id,'student_id'=>auth()->id(),'attendance_type'=>'room'])->whereNull('status')->whereDate('created_at','<',$new_attendance->created_at)->update(['status'=>0]);
                   $text='<i><bold>'.$room->title.'</bold></i>'. ' is opened Keep learning. ';
                   $textPhone='('.$room->title.')'. ' is opened Keep learning. ';
                   $redirect='/room/'.$room->id;
                   \App\Jobs\NotificationJop::dispatch('room_opened',$room,$text,auth()->user(),$redirect,$textPhone,'2');//students

                   $textParent= '<bold><i>'.$room->title. '</bold></i> is opened let your student keep learning.';
                   $textParentPhone='('.$room->title. ') is opened let your student keep learning.';
                    \App\Jobs\NotificationJop::dispatch($class,null,$textParent,auth()->user()->parent,$redirect=null,$textParentPhone,'2');//parent


                   if($request->ajax()){
                       return  response()->json(['status'=>true,'message'=>"Room Unlock Success"],200);

                   }else{

                       return  redirect()->back()->with(['message'=>"Room Unlock Success",'alert-type'=>'success']);
                   }
               }else{
                   if($request->ajax()){
                       return  response()->json(['status'=>false,'message'=>"Coupon Not Found or Used Before"],400);

                   }else{
                       return  redirect()->back()->with(['message'=>"Coupon Not Found or Used Before",'alert-type'=>'error']);
                   }
               }


           }else{
               if($request->ajax()){
                   return  response()->json(['status'=>false,'message'=>"Coupon Not Found or Used Before"],400);

               }else{
               return  redirect()->back()->with(['message'=>"Coupon Not Found or Used Before",'alert-type'=>'error']);
               }

           }
       }else{
           if ($request->ajax()){
               return  response()->json(['status'=>false,'message'=>"Don't access this room duo to there are another room with same Subject"],400);

           }else{

           return  redirect()->back()->with(['message'=>"Don't access this room duo to there are another room with same Subject",'alert-type'=>'error']);
           }

       }


    }
    public function resetCoupon(Request $request){

        $coupon=CouponUsed::find($request->couponId);
        $room=Room::find($coupon->coupon_used_id);
        if($request->day > (int)$room->unlock_after){
            $message="please enter number less than unlock room unlock room:".$room->unlock_after;
            return  response()->json(['status'=>false,'message'=>$message],402);
        }
          $date=Carbon::now()->subDay($room->unlock_after);
          $newdate=$date->addDay($request->day);
          $coupon->update(['created_at'=>$newdate]);
        return  response()->json(['status'=>false,'message'=>"change Success"],200);

    }
  private function checkDateCurrent($dateFrom , $dateTo){
    $currentDate = Carbon::now();

    $startDate = Carbon::parse($dateFrom);
    $endDate = Carbon::parse($dateTo);

    if ($currentDate->between($startDate, $endDate)) {
        return true;
    } else {
        return false;
    }
  }
}
