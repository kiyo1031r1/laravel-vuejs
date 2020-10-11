<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\Role;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\Controller;
use Illuminate\support\Str;


class LoginController extends Controller
{
    private $login_path = 'login';

    public function login(Request $request){
        $credentials = $request->validate([
             'email' => ['required', 'email'],
             'password' => ['required']
        ]);

        if(Auth::attempt($credentials)){
            $user = Auth::user();
            $user->remember_token = $this->createToken();
            $user->save();

            return response()->json();
        }
        throw ValidationException::withMessages([
            'not_found' => ['メールアドレスかパスワードが間違っています'],
        ]);
    }

    public function logout(){
        $user = Auth::user();
        $user->remember_token = null;
        $user->save();

        Auth::logout();
        return response()->json();
    }

    public function redirectToProvider($provider){
        return Socialite::driver($provider)->redirect();
    }

    public function handleProviderCallback($provider){
        $baseUrl = config('app.url');
        $route = "{$baseUrl}/{$this->login_path}";

        try{
            $providerUser = Socialite::driver($provider)->user();
            $email = $providerUser->getEmail();
            $provider_id = $providerUser->getId();
            if($email) {
                $user = User::firstOrCreate(['email' => $email],[
                        'provider_id' => $provider_id ,
                        'provider_name' => $provider,
                        'email' => $email,
                        'name' => $providerUser->getName(),
                        'nickname' => $providerUser->getNickname(),
                        'role_id' => Role::find(1)->id,
                ]);

                $user->remember_token = $this->createToken();
                $user->save();
            }
            elseif($provider_id) {
                $user = User::firstOrCreate(['provider_id' => $provider_id],[
                    'provider_id' => $provider_id ,
                    'provider_name' => $provider,
                    'email' => $email,
                    'name' => $providerUser->getName(),
                    'nickname' => $providerUser->getNickname(),
                    'role_id' => Role::find(1)->id,
                ]);

                $user->remember_token = $this->createToken();
                $user->save();
            }
            else{
                throw new Exception();
            }
    
            Auth::login($user);
            return redirect($route)->cookie('SOCIAL_LOGIN_SUCCESS', '', 0, '', '', false, false);

        } catch (Exception $e) {
            return redirect($route)->cookie('SOCIAL_LOGIN_FAILED', '', 0, '', '', false, false);
        }
    }

    private function createToken(){
        return hash_hmac('sha256', Str::random(40), config('app.key'));
    }
}