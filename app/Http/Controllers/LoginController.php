<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

include 'ChromePhp.php';

class LoginController extends Controller
{
    public function login(Request $request){
        // $credentials = $request->validate([
        //      'email' => ['required', 'email'],
        //      'password' => ['required']
        // ]);

        if(Auth::attempt([
            'email' => $request->input('email'),
            'password' => $request->input('password'),
        ])){
            return response()->json();
        }
        throw ValidationException::withMessages([
            'email' => ['メールアドレスかパスワードが間違っています'],
            'password' => ['メールアドレスかパスワードが間違っています']
        ]);
    }

    public function logout(){
        Auth::logout();
        return response()->json();
    }
}