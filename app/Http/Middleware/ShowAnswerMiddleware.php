<?php

namespace App\Http\Middleware;

use App\Models\CouponUsed;
use App\Models\Quiz;
use App\Models\Result;
use Closure;
use Illuminate\Http\Request;

class ShowAnswerMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $quiz=Quiz::find($request->quiz);

        if(auth()->user()->type != 2){
           $result=Result::where(['student_id'=>auth()->id(),'quiz_id'=>$request->quiz])->where('points','!=','0')->first();
           if($result){
               $resultLock=true;
               if( count($quiz->usedCoupon)>0){
                   $day=$quiz->lock_after;
                   $created_at=($quiz->usedCoupon?$quiz->usedCoupon->last()->created_at:null);
                   $endDate = \Carbon\Carbon::parse($created_at)->addDays($day);
                   $date1 = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $endDate);
                   $date2 = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', now());
                   $resultLock = $date1->gt($date2);
               }
               if(!$resultLock){
                   if($request->route()->getName()=='quiz.show.answer'){
                       return $next($request);
                   }else{
                       return redirect()->route('quiz.show.answer',$request->quiz);
                   }
               }else{
                   if($quiz->type==1 || $quiz->type==3  ){
                         if($request->route()->getName()=='quiz.show.answer'){
                             return $next($request);
                         }else{
                             if(!$result){
                              return  $next($request);
                             }else{
                                if($quiz->type==3){
                                    return redirect()->route('quiz.show.answer',$request->quiz);

                                }else{
                                    return redirect()->route('quiz.answer_student.answer',$request->quiz);

                                }
                             }
                         }
                     }else{
                         return redirect()->route('quiz.answer_student.answer',$request->quiz);
                     }

               }

           }else{

               if($request->route()->getName()!=='quiz.show.answer') {
                   $quiz=Quiz::find($request->quiz);
                   $usedCoupon=CouponUsed::where('user_id',auth()->id())->where('coupon_used_id',$request->quiz)->where('coupon_used_type','quiz')->first();
                   if ($usedCoupon ||  $quiz->price==null || $quiz->price==0  ) {
                       if(checkMissedExam($quiz,auth()->id())) {
                           if(\App\Models\ClassroomStudent::where('user_id',auth()->id())->first()->block){
                               $request['block']=true;
                               $request['unlock']=true;
                               $request['grade']=auth()->user()->grade_id;
                           }else{
                               $request['unlock']=true;
                               $request['grade']=auth()->user()->grade_id;
                           }
                       }elseif(unlockafterRoomDetail($quiz->price ,$quiz->lock_after,$quiz->usedCoupon->last()->created_at??'')==''){
                           $request['unlock']=true;
                           $request['grade']=auth()->user()->grade_id;
                       }
                       else{
                           if($quiz->type==2){
                               if(unlockafterRoomDetail($quiz->price ,$quiz->lock_after,@$quiz->usedCoupon->last()->created_at)!=""){
                                return $next($request);

                               }else{
                                   return redirect()->route('room.show',$quiz->room->id)->with(['warning'=>'Room is Locked']);
                               }
                           }else{
                             return $next($request);
                           }
                       }
                   }

                   if(\App\Models\ClassroomStudent::where('user_id',auth()->id())->first()->block){
                       $request['block']=true;
                       $request['unlock']=true;
                       $request['grade']=auth()->user()->grade_id;
                   }else{
                       $request['unlock']=true;
                       $request['grade']=auth()->user()->grade_id;
                   }
                   return $next($request);
               }else{
                   return redirect()->route('quiz.show.answer',$request->quiz);
               }
           }

        }else{
            return $next($request);

        }
    }
}
