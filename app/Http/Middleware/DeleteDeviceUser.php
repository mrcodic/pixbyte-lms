<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DeleteDeviceUser
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
        $user=auth('web')->user();
        if(($user->type==3 || $user->type==4) && $user->student->device_status==1 && $user->force_logout){
            auth('web')->user()->student()->update(['device_status'=>0]);
            Auth::logout();
            setcookie('device_code','');
            $request->session()->invalidate();

            $request->session()->regenerateToken();
            return redirect('/login');
        }else{
        return $next($request);
        }
    }
}
