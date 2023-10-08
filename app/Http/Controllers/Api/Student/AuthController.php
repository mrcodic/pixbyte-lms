<?php

namespace App\Http\Controllers\Api\Student;

use App\Enums\Http;
use App\Helpers\MessageResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Student\LoginRequest ;
use App\Http\Resources\Api\StudentResource;
use App\Models\User;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function loginUser(LoginRequest $request)
    {
        $validated = $request->validated();
        $name_id = data_get($validated, 'name_id');
        $password = data_get($validated, 'password');
        $fcm = data_get($validated, 'fcm');


        $student = User::where('name_id', $name_id)->first();
        $student->device_tokens()->firstOrCreate(['device_token'=>$fcm],[
                'tokenable_id' => $student->id,
                'tokenable_type' => get_class($student),
                'device_token'=>$fcm
        ]);
        if (!$student || !Hash::check($password, $student->password)) {
            return new MessageResponse(
                message: 'Invalid credentials',
                code: Http::BAD_REQUEST,
                errors: __('Invalid credentials')
            );
        }
        $token = $student->createToken('authToken', ['login-token'])->plainTextToken;
        return new MessageResponse(
            message: 'Login Successfully',
            code: Http::OK,
            body: [
                'student' => StudentResource::make($student),
                'access_token' => $token,
            ]
        );
    }
    public function logout(Request $request): Responsable
    {
        $request->user()->currentAccessToken()->delete();

        return new MessageResponse(
            message: 'Logout Successfully',
            code: Http::OK,
        );
    }
    public function getProfile(): Responsable
    {
        $user=auth()->user();
        return new MessageResponse(
            message: 'Get Student Data',
            code: Http::OK,
            body: [
                'student' => StudentResource::make($user),
            ]
        );
    }
}
