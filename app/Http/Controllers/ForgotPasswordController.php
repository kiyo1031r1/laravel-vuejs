<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\support\str;
use Illuminate\Support\Facades\Mail;
use App\Mail\ResetPasswordMail;

class ForgotPasswordController extends Controller
{
    public function forgot(Request $request){
        $request->validate([
            'email' => ['required', 'email']
        ]);

        ResetPassword::destroy($request->input('email'));

        $resetPassword = new ResetPassword();
        $resetPassword->email = $request->input('email');
        $token = $this.createToken();
        $resetPassword->token = $token;
        $resetPassword->save();
        
        $this.sendResetPasswordMail($resetPassword->email, $token);

        return $resetPassword;
    }

    private function createToken(){
        return hash_hmac('sha256', Str::random(40), config('app.key'));
    }

    private function sendResetPasswordMail($email, $token){
        Mail::to($email)->send(new ResetPasswordMail($token));
    }

}
