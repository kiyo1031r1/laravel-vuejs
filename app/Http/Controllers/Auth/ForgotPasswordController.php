<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\ResetPasswordMail;
use App\Models\PasswordReset;
use App\Http\Controllers\Controller;

class ForgotPasswordController extends Controller
{
    public function forgot(Request $request){
        $request->validate([
            'email' => ['required', 'email']
        ]);

        PasswordReset::where('email', $request->input('email'))->delete();

        $passwordReset = new PasswordReset();
        $passwordReset->email = $request->input('email');
        $token = $this->createToken();
        $passwordReset->token = $token;
        $passwordReset->save();
        
        $this->sendResetPasswordMail($passwordReset->email, $token);

        return $passwordReset;
    }

    private function createToken(){
        return hash_hmac('sha256', Str::random(40), config('app.key'));
    }

    private function sendResetPasswordMail($email, $token){
        Mail::to($email)->send(new ResetPasswordMail($token));
    }

}
