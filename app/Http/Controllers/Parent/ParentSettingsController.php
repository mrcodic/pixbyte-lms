<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use App\Models\Instructor;
use App\Models\ParentStudent;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class ParentSettingsController extends Controller
{

    public function show() {
        $userId = Auth::user()->id;
        $userData = ParentStudent::find($userId);

        return view('parent.settings', compact('userData'));
    }

    public function update(Request $request) {

        $userId     = Auth::user()->id;
        $userName   = Auth::user()->name_id;
        $user       = ParentStudent::findOrFail($userId);

        if ($request->email === $user->email) {
            $request->validate([
                'email' => ['nullable','email', 'max:255'],
            ]);
        }else {
            $request->validate([
                'email' => ['nullable', 'email', 'max:255', 'unique:users'],
            ]);
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => [ 'string', 'max:255'],
        ]);

        if (!empty($request->password)) {
            $request->validate([
                'password'=>['required', 'confirmed', Rules\Password::defaults()],
                'password_confirmation' => 'required|same:password',
            ]);
            $password = $request->password;

        }else {
            $password = $user->password;
        }

        $user->update([
            'phone' => $request->phone,
            'name' => $request->name,
            'name_id' => $userName,
            'email' => $request->email,
            'password' => $password,
        ]);

        $user->save();

        if($user->wasChanged()) {
            $notification = array(
                'message'       => 'Account settings updated successfully!',
                'alert-type'    => 'success'
            );
        }else{
            $notification = array(
                'message'       => 'Nothing changes!',
                'alert-type'    => 'warning'
            );
            return back();
        }

        return redirect()->route('parent.settings')->with($notification);
    }

}
