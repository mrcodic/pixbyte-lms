<?php

namespace App\Http\Requests\Auth;

use App\Models\ParentStudent;
use App\Models\Setting;
use Dotenv\Exception\ValidationException as ExceptionValidationException;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use App\Models\User;

class LoginRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
            'g-recaptcha-response' => ['recaptcha'],//recaptcha validation
            ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    const COOKIE_NAME = "device_code";

    public function authenticate()
    {
        $this->ensureIsNotRateLimited();

        // if (! Auth::attempt($this->only('email', 'password'), $this->boolean('remember'))) {
        //     RateLimiter::hit($this->throttleKey());

        //     throw ValidationException::withMessages([
        //         'email' => trans('auth.failed'),
        //     ]);
        // }


        //Login with Email or ID
        $user = User::where('email', $this->login)
            ->orWhere('name_id', $this->login)
            ->first();
        if (!$user || !Hash::check($this->password, $user->password)) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'login' => __(key:'auth.failed'),
            ]);
        }

        $device_code=Str::random(20);
        $device_code=rtrim(chunk_split(strtoupper($device_code),4,'-'),'-');

        $settingNumDevice=Setting::where('name','num_device_login')->where('type','site')->first();
;
        if($user->type==3 ||$user->type==4){

            $ips=$user->student->ip;
            if(empty($ips)){
             $ips=[];
             $ips[]=$device_code;
             $user->student()->update(['ip'=>$ips]);
                setcookie('device_code', json_encode($ips));
                Auth::login($user, $this->boolean(key:'remember'));
                RateLimiter::clear($this->throttleKey());
            }else{
                $code=$ips;
               if(count($code) >= (int)$settingNumDevice->value){

                    $cookiesCode=json_decode(request()->cookie('device_code'));
                    if($cookiesCode){
                        foreach ($cookiesCode as $coo){
                            if(in_array($coo,$code)){
                                Auth::login($user, $this->boolean(key:'remember'));
                                RateLimiter::clear($this->throttleKey());
                            }else{
                                abort(401);
                            }
                        }
                    }else{
                        abort(401);
                    }
                }else{

                   if( !empty(request()->cookie('device_code')) && array_intersect(json_decode(request()->cookie('device_code')),$code)){
                       Auth::login($user, $this->boolean(key:'remember'));
                       RateLimiter::clear($this->throttleKey());
                   }else{
                       array_push($code,$device_code);
                       setcookie('device_code', json_encode($code));
                       $user->student()->update(['ip'=>$code]);
                       Auth::login($user, $this->boolean(key:'remember'));
                       RateLimiter::clear($this->throttleKey());
                   }
                }


            }
        }else{
            Auth::login($user, $this->boolean(key:'remember'));
            RateLimiter::clear($this->throttleKey());

        }

        Auth::user()->update(['force_logout'=>0]);



    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited()
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     *
     * @return string
     */
    public function throttleKey()
    {
        return Str::lower($this->input('email')).'|'.$this->ip();
    }
}
