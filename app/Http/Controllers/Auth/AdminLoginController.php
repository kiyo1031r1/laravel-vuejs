<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AdminLoginController extends Controller
{
    public function login(Request $request){
        $credentials = $request->validate([
             'email' => ['required', 'email'],
             'password' => ['required']
        ]);

        if(Auth::attempt($credentials)){
            return response()->json();
        }
        throw ValidationException::withMessages([
            'not_found' => ['メールアドレスかパスワードが間違っています'],
        ]);
    }

    public function logout(){
        Auth::logout();
        return response()->json();
    }
}
