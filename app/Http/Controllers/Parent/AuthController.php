<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginParentRequest;
use App\Models\Admin;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function login()
    {
        // dd(auth()->user());
        return view('parent.auth.login');
    }


    public function logins(LoginParentRequest $request){

        // dd($request);
        $request->authenticate();
        $request->session()->regenerate();

        return redirect()->route('parent.home');
    }


}
