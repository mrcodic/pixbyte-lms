<?php

namespace App\Http\Middleware;

use App\Models\CouponUsed;
use App\Models\Room;
use Closure;
use Illuminate\Http\Request;

class StudentUsedCoupon
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

        if(auth()->user()->type != 2){
                $room=Room::findOrFail($request->room);
                $usedCoupon=CouponUsed::where('user_id',auth()->id())->where('coupon_used_id',$request->room)->where('coupon_used_type','room')->first();

                $offlineStudent=auth()->user()->type==4;
                if($usedCoupon && $offlineStudent && $usedCoupon->coupon_id==0){
                    if(!auth()->user()->subscription){
                        $request['unlock']=true;
                        $request['grade']=auth()->user()->grade_id;
                        $request['type']='subscription';
                    }

                }

                if ($usedCoupon || $room->price==0) {

                    if(checkMissed($room->id,auth()->id())) {
                        if(\App\Models\ClassroomStudent::where('user_id',auth()->id())->first()->block){
                            $request['block']=true;
                            $request['unlock']=true;
                            $request['grade']=auth()->user()->grade_id;
                        }else{
                            $request['unlock']=true;
                            $request['grade']=auth()->user()->grade_id;
                        }
                    }elseif(unlockafterRoomDetail($room->price ,$room->unlock_after,$room->usedCoupon->last()->created_at??'')==''){
                        $request['unlock']=true;
                        $request['grade']=auth()->user()->grade_id;
                    }
                    else{
                        $result=$this->checkTakeQuiz($room->quizzes);

                        if($room->pass_quiz && $result){
                            $request['unlock']=true;
                            $request['quiz']=true;
                            $request['assignments']=true;
//                            return redirect()->route('quiz.show',$result['id'])->with(['warning'=>'Please Passed Quiz  '.$result['name']]);
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
            return $next($request);

        }

    }

    private function checkTakeQuiz($quiz){

        $result=0;
        foreach ($quiz as $item){
            if($item->type=='1'){
                if($item->result==null || ($item->result && (int)checkScore($item->result->total_correct_answer,count($item->questions)) <=(int)$item->score)){
                    $result=['id'=>$item->id,'name'=>$item->title];
                }
            }

        }
        return $result;
    }
}
