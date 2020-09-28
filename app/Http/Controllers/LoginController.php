<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(Request $request){

        if(Auth::attempt([
            'email' => $request->input['email'],
            'password' => $request->input['password'],
        ])){
            return response();
        }
    }

    public function logout(){
        Auth::logout();
        return response();
    }
}