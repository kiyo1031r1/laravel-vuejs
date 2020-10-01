<?php

namespace App\Http\Controllers;

use App\Models\PasswordReset;
use Illuminate\Http\Request;

class ResetPasswordController extends Controller
{

    private $login_path = 'login';
    private $reset_password_path = 'reset_password';

    public function __construct(){
        $this->middleware('guest');
    }

    public function checkToken($token = null){
        $isToken = PasswordReset::where('token', $token)->exists();
        $baseUrl = config('app.url');
        if($isToken) {
            $route = "{$baseUrl}/{$this->reset_password_path}";
            return redirect($route)->cookie('RESET_TOKEN', $token);
        }
        else{
            $route = "{$baseUrl}/{$this->login_path}";
            return redirect($route)->cookie('ERROR_MESSAGE', $token);
        }
    }
}
