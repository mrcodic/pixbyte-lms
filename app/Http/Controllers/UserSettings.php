<?php

namespace App\Http\Controllers;
use App\Models\Instructor;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Intervention\Image\Facades\Image;
use Spatie\Permission\Models\Role;

class UserSettings extends Controller
{
    public function show() {
        $userId = Auth::user()->id;
        $userData = User::find($userId);

        return view('settings.account', compact('userData'));
    }

    public function update(Request $request) {
        $userId     = Auth::user()->id;
        $userName   = Auth::user()->name_id;
        $user       = User::findOrFail($userId);
        if ($request->email === $user->email) {
            $request->validate([
                'email' => ['required', 'string', 'email', 'max:255'],
            ]);
        }else {
            $request->validate([
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            ]);
        }

        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            // 'bio' => ['required', 'string'],

        ]);

        if (!empty($request->password)) {
            $request->validate([
                'password'=>['required', 'confirmed', Rules\Password::defaults()],
                'password_confirmation' => 'required|same:password',
            ]);
            $password = Hash::make($request->password);

        }else {
            $password = $user->password;
        }

        $user->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'name_id' => $userName,
            'email' => $request->email,
            'bio' => $request->bio,
            'password' => $password,
        ]);


        $user->save();
        $request->phone ? $user->student->update(['phone' => $request->phone ]) :null;

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

        return redirect()->route('settings')->with($notification);
    }
    public function my_assistant(){
    $assistant= Instructor::where('instructor_id',\auth()->id())->pluck('user_id')->toArray();
    $assistants= User::whereIn('id',$assistant)->get();

        return view('myassistant.index',compact('assistants'));
    }
    public function checkUpload(Request $request){
        if ($request->file('profile_image')) {
            $test = $request->validate([
                'profile_image' => 'image|mimes:png,jpg,jpeg|max:2048',
            ]);
        }
        if($test){

        return response()->json(['status'=>1, 'msg'=>'validation successfully.']);
        }

    }
    public function uploadProfile(Request $request){
        $user=\auth()->user();
        $validation=false;
        if ($request->file('profile_image')) {
            $test = $request->validate([
                'profile_image'=>'image|mimes:png,jpg,jpeg|max:2048',
            ]);
            $file       = $request->file('profile_image');
            $fileName   = $user->user_id.date('YmdHi').$file->getClientOriginalName();
            $img = Image::make($file);
            $img->resize(150, 150, function ($constraint) {
                $constraint->aspectRatio();
            })->save('uploads/profile_images/'.$fileName, 60);
//              $img->save('uploads/profile_images/'.$fileName, 60);
//              $file->move(public_path('uploads/profile_images'), $fileName);
            $user['profile_image'] = $fileName;
            $user->save();
            return response()->json(['status'=>1, 'msg'=>'Image has been cropped successfully.', 'name'=>$fileName]);

        }
    }
}
