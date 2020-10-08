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

        $user = User::where('email', $request->email)->where('password', $request->password)->first();

        if($user == null){
            throw ValidationException::withMessages([
                'not_found' => ['メールアドレスかパスワードが間違っています'],
            ]);
        }

        $user_roles = $user->roles;
        foreach($user_roles as $role) {
            if($role->name === 'subscriber'){
                throw ValidationException::withMessages([
                    'not_found' => ['権限がありません']
                ]);
            }
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
