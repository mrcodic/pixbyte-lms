<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Traits\PointsTrait;
use App\Http\Traits\SendNotificationTrait;
use App\Models\PointDetails;
use App\Models\Setting;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    use SendNotificationTrait, PointsTrait;
    /**
     * Display the login view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     *
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(LoginRequest $request)
    {
        $request->authenticate();


        $user = User::find(auth()->user()->id);
        $user->update(['force_logout'=>0]);

        if($user->first_login):

            $setPoint = $this->setPoint($user, 'point_first_login');

            if($setPoint['status']) :
                $user->first_login = false;
                $user->save();

                $this->sendSingleUserNotificationInQueue(
                    'Welcome in Mives Congrats you have earned '.$setPoint['point'].' points for your first login ',
                    [$user],
                    'User',
                    'first_login',
                    [],
                    null,
                    false,
                    false,
                    ['notification_only'],
                    'Welcome in Mives Congrats you have earned '.$setPoint['point'].' points for your first login ',
                    ''
                );
            endif;
        endif;

        $request->session()->regenerate();
        if($request->ajax()){
            return  response()->json(['status'=>true,'url'=>\auth()->user()->name_id],200);
        }

        return redirect()->intended(RouteServiceProvider::HOME);
    }

    /**
     * Destroy an authenticated session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
