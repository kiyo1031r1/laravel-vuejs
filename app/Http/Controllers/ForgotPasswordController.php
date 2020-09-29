<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\support\str;

class ForgotPasswordController extends Controller
{
    public function forgot(Request $request){
        $request->validate([
            'email' => ['required', 'email']
        ]);

        $token = $this.createToken();
    }

    public function createToken(){
        return hash_hmac('sha256', Str::random(40), config('app.key'));
    }
}
