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

    public function cancel(Request $request){
        $user = $request->user();

        $is_active = $user->subscribed('default');
        if($is_active) {
            //課金継続中の場合、キャンセル処理を行う
            if($user->subscription('default')->recurring()){
                $user->subscription('default')->cancel();
        
                $user->status = 'normal';
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

    public function getNextUpdate(Request $request){
        $user = $request->user();
        $is_active = $user->subscribed('default');

        if($is_active) {
            //課金継続中の場合、次回更新日を返す
            if($user->subscription('default')->recurring()){
                $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
                $subscription = $user->subscriptions()->active()->first();
                $subscription_details = $stripe->subscriptions->retrieve($subscription->stripe_id);
    
                //日時取得後、フォーマット
                $next_update = date('Y-m-d', $subscription_details->current_period_end);
                $week = ['日', '月','水', '木', '金', '土'];
                $next_update_week = date('w', $subscription_details->current_period_end);
    
                return $next_update. ' ('.$week[$next_update_week].')';
            }
        }
    }
}
