<?php

namespace App\Http\Middleware;

use App\Models\ClassroomStudent;
use Closure;
use Illuminate\Http\Request;

class ClassRoomMiddleware
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
        if(auth()->user()->type==2){
            return $next($request);
        }else{
            $classes= ClassroomStudent::where('user_id',auth()->id())->pluck('classroom_id')->toArray();
            if(in_array($request->id,$classes)){
                return $next($request);
            }else{
                return  redirect()->back()->with(['message'=>"you are not authorized in this page",'alert-type'=>'warning']);

            }
        }


    }
}
