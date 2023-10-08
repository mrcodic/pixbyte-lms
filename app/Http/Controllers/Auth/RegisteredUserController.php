<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\IdGenerator;
use App\Models\Attendance;
use App\Models\Student;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        //Generating a random unique candidate_id for each applicant & adding 0 in upfront if id less than 5 digits
        // $usersData = User::get(['id'])->toArray();
        // do {

        // $name_id = str_pad(rand(1, 5000), 5, "0", STR_PAD_LEFT);

        // } while (in_array($name_id, $usersData));

        // User Id generator
        $name_id = IdGenerator::create(new User, 'name_id', 5, 'mvs');

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'name_id' => $name_id,
            'email' => $request->email,
            'type' => 3,
            'password' => Hash::make($request->password),
        ]);
        $device_code=Str::random(20);
        $device_code=rtrim(chunk_split(strtoupper($device_code),4,'-'),'-');
        $ips[]=$device_code;
//        Attendance::create(['student_id'=>$user->id]);
        Student::create(['user_id'=>$user->id,"ip"=>$ips]);
        setcookie('device_code', json_encode($ips));
        event(new Registered($user));
        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }
}
