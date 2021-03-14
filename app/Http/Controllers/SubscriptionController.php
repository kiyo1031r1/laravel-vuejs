<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubscriptionController extends Controller
{
    public function index(){
        $user = Auth::user();
        return $user->createSetupIntent();
    }

    public function subscribe(Request $request){
        $user = $request->user();

        //未課金または課金猶予がない場合に登録
        if(!$user->subscribed('default')){
            $user->newSubscription('default', env('STRIPE_PREMIUM_KEY')
            )->create($request->payment_method);

            //ステータスをプレミアムへ変更
            if($user->status === 'normal'){
                $user->status = 'premium';
                $user->save();
            }
        }
    }

    public function cancelNow(Request $request){
        $user = $request->user();
        $user->subscription('default')->cancelNow();
        $user->status = 'normal';
        $user->save();
    }
}
