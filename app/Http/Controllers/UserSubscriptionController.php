<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserSubscription;
use Illuminate\Support\Facades\Auth;

class UserSubscriptionController extends Controller
{

    public function show(){
        return view('subscription.subscribe');
    }

    public function store()
    {

        $subscription_status['user_id'] = auth()->id();
        $subscription_status['isPremium'] = true;

        UserSubscription::create($subscription_status);

        return redirect('dashboard')->with('message', 'You are now Premium!');
    }

    public function destroy(UserSubscription $subscription)
    {

        $user = Auth::user();
        $currentUser = auth()->id();
        $data = \App\Models\User::find($currentUser);
        $user_id = $data->UserSubscription->user_id;
        $isPremium = $data->UserSubscription->isPremium;

        if ($user_id != $currentUser) {
            abort(403, 'Unauthorized Action');
        }

        $data->UserSubscription->delete();

        return redirect('dashboard')->with('message', 'Unsubscribed from Premium');
    }
}
