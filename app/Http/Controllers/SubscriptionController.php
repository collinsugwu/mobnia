<?php


namespace App\Http\Controllers;

use App\Http\Resources\SubscriptionResource;
use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SubscriptionController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index() {
       $plans = Plan::all();
       return $this->success($plans);
    }
    /**
     * @param Request $request
     * @param $plan_id
     * @param SubscriptionResource $subscriptionResource
     * @return \Illuminate\Http\JsonResponse|string
     */
    public function choosePlan(Request $request, $plan_id, SubscriptionResource $subscriptionResource)
    {
        $user = $request->user();
        //check if a user has an active plan
        abort_if($user->hasActiveSubscription(),
            Response::HTTP_UNPROCESSABLE_ENTITY, 'You have an active subscription');
        $plan = Plan::where('id', $plan_id)->first();

        //creates a subscription
        $subscription = $subscriptionResource->createSubscription($user, $plan);
        return $this->success($subscription);
    }

    /**
     * Verifies Payment made and save the details.
     * @param Request $request
     * @param SubscriptionResource $subscriptionResource
     * @return \Illuminate\Http\JsonResponse
     */
    public function verifyPayment(Request $request, SubscriptionResource $subscriptionResource)
    {
        $subscriptionResource->verifyPayment($request);
        return $this->success();
    }
}
