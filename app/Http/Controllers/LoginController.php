<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Laravel\Socialite\Facades\Socialite;

class LoginController extends Controller
{
    private $home_path = '/tasks';

    public function login(Request $request){
        $credentials = $request->validate([
             'email' => ['required', 'email'],
             'password' => ['required']
        ]);

        if(Auth::attempt($credentials)){
            return response()->json();
        }
        throw ValidationException::withMessages([
            'not_found' => ['メールアドレスかパスワードが間違っています'],
        ]);
    }

    public function logout(){
        Auth::logout();
        return response()->json();
    }

    public function redirectToProvider($provider){
        return Socialite::driver($provider)->redirect();
    }

    public function handleProviderCallback($provider){
        $providerUser = Socialite::driver($provider)->user();
        $email = $providerUser->getEmail();
        $provider_id = $providerUser->getId();
        if($email) {
            $user = User::firstOrCreate(['email' => $email],[
                    'provider_id' => $provider_id ,
                    'provider_name' => $provider,
                    'email' => $email,
                    'name' => $provider->getName(),
                    'nickname' => $provider->getNickname(),
            ]);
        }
        elseif($provider_id) {
            $user = User::firstOrCreate(['provider_id' => $provider_id],[
                'provider_id' => $provider_id ,
                'provider_name' => $provider,
                'email' => $email,
                'name' => $provider->getName(),
                'nickname' => $provider->getNickname(),
            ]);
        }
        else{
            throw new Exception();
        }

        Auth::login($user);

        $baseUrl = config('app.url');
        $route = "{$baseUrl}/{$this->home_path}";

        return redirect($route);
    }
}