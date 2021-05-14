<?php


namespace App\Http\Resources;


use App\Factory\PaymentFactory;
use App\Models\Auth\User;
use App\Models\Payment;
use App\Models\Plan;
use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SubscriptionResource
{
    /**
     * @param User $user
     * @param Plan $plan
     * @return Subscription
     */
    public function createSubscription(User $user, Plan $plan)
    {
        $subscription = new Subscription();
        $subscription->user_id = $user->id;
        $subscription->plan_id = $plan->id;
        $subscription->amount = $plan->amount;
        $subscription->save();

        return $subscription;
    }


    /**
     * @param Request $request
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function verifyPayment(Request $request)
    {
        $ref = $request->ref;
        $sub = Subscription::findByReference($ref);
        abort_unless(is_object($sub),
            Response::HTTP_UNPROCESSABLE_ENTITY, 'Subscription not found');

        // Payment Factory to initialize payment gateway
        $auth_code = $this->paymentFactory($ref, $sub);

        $this->savePayment($sub, $ref, $auth_code);
        // activate the subscription
        $this->activateSubscription($sub);
    }

    /**
     * @param $ref
     * @param $sub
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function paymentFactory($ref, $sub){
        $paymentFactory = new PaymentFactory();
        $payment = $paymentFactory->initializePayment($ref, $sub);
        $payment->verify();
        return $payment->authorization_code;
    }

    /**
     * @param Subscription $subscription
     * @param $ref
     * @param $auth_code
     */
    public function savePayment(Subscription $subscription, $ref, $auth_code)
    {
        $end_at = $this->getPlanDuration($subscription);
        $payment = new Payment();
        $payment->subscription_id = $subscription->id;
        $payment->start_at = Carbon::now();
        $payment->paid_at = Carbon::now();
        $payment->payment_ref = $ref;
        $payment->end_at = $end_at;
        $payment->authorization = $auth_code;
        $payment->save();
    }

    /**
     * @param $subscription
     * @return Carbon
     */
    private function getPlanDuration($subscription)
    {
        $duration = $subscription->plan->duration;
        switch ($duration) {
            case 'monthly':
                $end_at = Carbon::now()->addMonth();
                break;
            case 'yearly':
                $end_at = Carbon::now()->addYear();
        }
        return $end_at;
    }

    /**
     * @param Subscription $subscription
     */
    public function activateSubscription(Subscription $subscription)
    {
        $subscription->update([
            'is_active' => true
        ]);
    }
}
