<?php

namespace App\Http\Controllers;

use App\Models\PasswordReset;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

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
            return redirect($route)->cookie('RESET_TOKEN', $token, 0, '', '', false, false);
        }
        else{
            $route = "{$baseUrl}/{$this->login_path}";
            return redirect($route)->cookie('ERROR_MESSAGE', $token, 0, '', '', false, false);
        }
    }

    public function reset(Request $request){
        $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'token' => ['required'] 
        ]);

        $token = Crypt::decryptString($request->input('token'));
        $token = explode('|', $token)[1];
        $passwordReset = PasswordReset::where('token', $token)->first();

        if(!$passwordReset){
            throw ValidationException::withMessages([
                'token' => ['処理に失敗しました。再度メールを送信してください']
            ]);
        }

        $user = User::where('email', $passwordReset->email)->first();
        if(!$user){
            throw ValidationException::withMessages([
                'user' => ['登録ユーザーが見つかりませんでした']
            ]);
        }

        $user = DB::transaction(function() use($passwordReset, $user, $request){
            PasswordReset::where('email', $passwordReset->email)->delete();
            $user->password = Hash::make($request->input('password'));
            $user->save();
            return $user;
        });

        Auth::login($user);

        return $user;
    }
}
