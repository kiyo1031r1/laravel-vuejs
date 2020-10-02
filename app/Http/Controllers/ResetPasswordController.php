<?php

namespace App\Http\Controllers;

use App\Models\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;

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
        $validator = $this->validator($request->all());

        $validator->after(function ($validator, $request) {
            $token = Crypt::decryptString($request->input('token'));

            $passwordReset = PasswordReset::where('token', $token)->first();
            if(!$passwordReset){
                $validator->errors()->add('token', '処理に失敗しました。再度メールを送信してください');
            }

            $user = User::where('email', $passwordReset->email)->first();
            if(!$user) {
                $validator->errors()->add('user', '登録ユーザーが見つかりませんでした');
            }
            
            if($user) {
                $validator->errors()->add('user', 'テスト');
            }
        });
        
        $validator->validate();


    }

    private function validator($data) {
        return Validator::make($data, [
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'token' => ['required'] 
        ]);
    }
}
