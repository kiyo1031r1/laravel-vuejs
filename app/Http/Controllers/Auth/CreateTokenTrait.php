<?php

namespace App\Http\Controllers\Auth;

use Illuminate\support\Str;

trait CreateTokenTrait
{
    private function createToken(){
        return hash_hmac('sha256', Str::random(40), config('app.key'));
    }
}