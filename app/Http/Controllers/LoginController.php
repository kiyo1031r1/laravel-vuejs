<?php

namespace App\Http\Controllers;

use ChromePhp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
include 'ChromePhp.php';

class LoginController extends Controller
{
    public function login(Request $request){
        if(Auth::attempt([
            'email' => $request->input('email'),
            'password' => $request->input('password'),
        ])){
            ChromePhp::log(Auth::check());
            ChromePhp::log('test2');
            return response();
        }
        else{
            ChromePhp::log('test3');
        }
    }

    public function logout(){
        Auth::logout();
        ChromePhp::log('test');
        return response();
    }
}