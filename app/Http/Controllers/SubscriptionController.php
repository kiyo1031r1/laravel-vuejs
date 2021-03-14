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
        $user->newSubscription('default', env('STRIPE_PREMIUM_KEY')
        )->create($request->paymentMethod);
        $user->load('subscriptions');
    }
}
