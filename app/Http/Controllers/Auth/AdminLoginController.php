<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AdminLoginController extends Controller
{
    public function login(Request $request){
        if(Auth::attempt([
            'email' => $request['email'],
            'password' => $request['password'],
            'role_id' => Role::where('name', '管理者')->first()->id
            ])){
            return;
        }
        else{
            throw ValidationException::withMessages([
                'not_found' => ['メールアドレスかパスワードが間違っています'],
            ]);
        }
    }

    public function logout(){
        Auth::logout();
    }
}
