<?php

namespace App\Http\Controllers\Api\Parent;

use App\Enums\Http;
use App\Helpers\MessageResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Parent\LoginRequest;
use App\Http\Resources\Api\ParentResource;
use App\Models\ParentStudent;
use App\Models\User;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function loginUser(LoginRequest $request)
    {
        $validated = $request->validated();
        $name_id = data_get($validated, 'name_id');
        $password = data_get($validated, 'password');
        $fcm = data_get($validated, 'fcm');
        $parent = ParentStudent::where('name_id', $name_id)->first();
        $parent->device_tokens()->firstOrCreate(['device_token'=>$fcm],[
                'tokenable_id' => $parent->id,
                'tokenable_type' => get_class($parent),
                'device_token'=>$fcm
        ]);
        if (!$parent || $password != Crypt::decrypt($parent->password)) {
            return new MessageResponse(
                message: 'Invalid credentials',
                code: Http::BAD_REQUEST,
                errors: __('Invalid credentials')
            );
        }
        $token = $parent->createToken('authToken', ['login-token'])->plainTextToken;
        return new MessageResponse(
            message: 'Login Successfully',
            code: Http::OK,
            body: [
                'parent' => ParentResource::make($parent),
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
            message: 'Get Parent Data',
            code: Http::OK,
            body: [
                'parent' => ParentResource::make($user),
            ]
        );
    }

}
