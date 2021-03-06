<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class SubscriptionController extends Controller
{
    public function index(){
        $user = Auth::user();
        return $user->createSetupIntent();
    }

    public function getStatus(Request $request){
        $user = $request->user();
        $is_active = $user->subscribed('default');
        $grace_period = '';

        if($is_active){
            //課金継続中
            if($user->subscription('default')->recurring()){
                $subscription_status = 'premium';
            }
            //課金キャンセル(猶予期間あり)
            elseif($user->subscription('default')->onGracePeriod()){
                $subscription_status = 'cancel';
                $grace_period = $user->subscriptions()->onGracePeriod()->first()->ends_at;
            }
        }
        //未課金
        else{
            $subscription_status = 'normal';
        }

        return ['status' => $subscription_status, 'grace_period' => $grace_period];
    }

    public function subscribe(Request $request){
        $user = $request->user();

        //未課金または課金猶予がない場合に登録
        if(!$user->subscribed('default')){

            //顧客情報登録済みの場合、前回のカード情報を全て削除
            if($user->stripe_id){
                $this->destroyCard($user->stripe_id);
            }

            $user->newSubscription('default', env('STRIPE_PREMIUM_KEY')
            )->create($request->payment_method);

            //ステータスをプレミアムへ変更
            $user->status = 'premium';
            $user->save();
        }
        else{
            throw ValidationException::withMessages([
                'subscription' => ['すでにプレミアム会員です。またはプレミアム期間中です。']
            ]);
        }
    }

    public function editCard(Request $request){
        $user = $request->user();
        $stripe_id = $user->stripe_id;
        
        //顧客情報登録済みの場合
        if($stripe_id){
            //前回のカード情報を全て削除
            $this->destroyCard($stripe_id);

            //カード情報を更新
            $new_payment_method = $request->payment_method;
            $user->updateDefaultPaymentMethod($new_payment_method);
        }
        else{
            throw ValidationException::withMessages([
                'subscription' => ['課金未実施の為、処理を行うことができませんでした。']
            ]);
        }
    }

    private function destroyCard($stripe_id){
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
        $payment_methods = $stripe->paymentMethods->all([
            'customer' => $stripe_id,
            'type' => 'card',
        ]);

        foreach($payment_methods as $payment_method){
            $stripe->paymentMethods->detach($payment_method->id);
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
        else{
            throw ValidationException::withMessages([
                'subscription' => ['未課金状態の為、処理を行うことができませんでした。']
            ]);
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
                $week = ['日', '月', '火', '水', '木', '金', '土'];
                $next_update_week = date('w', $subscription_details->current_period_end);
    
                return $next_update. ' ('.$week[$next_update_week].')';
            }
        }
    }

    public function getCardInformation(Request $request){
        $user = $request->user();
        $stripe_id = $user->stripe_id;
        //顧客情報登録済みの場合
        if($stripe_id){
            $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
            $payment_methods = $stripe->paymentMethods->all([
                'customer' => $stripe_id,
                'type' => 'card',
                'limit' => 1,
            ]);
            
            $payment_method = $payment_methods->data[0];
            $card = [];
            $card['name'] = $payment_method->billing_details->name;
            $card['exp_month'] = $payment_method->card->exp_month;
            $card['exp_year'] = $payment_method->card->exp_year;
            $card['brand'] = $user->card_brand;
            $card['last_four'] = $user->card_last_four;
            
            return $card;
        }
    }
}
