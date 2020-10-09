<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
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

        $user = User::where('email', $request->email)->first();
        if($user == null) {
            throw ValidationException::withMessages([
                'not_found' => ['メールアドレスかパスワードが間違っています'],
            ]);
        }

        if($user->role_id === 1) {
            throw ValidationException::withMessages([
                'not_found' => ['権限がありません']
            ]);
        }

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
