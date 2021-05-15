<?php


namespace App\Console\Commands;


use App\Factory\PaymentFactory;
use App\Http\Resources\SubscriptionResource;
use App\Models\Subscription;
use Illuminate\Bus\Queueable;
use Illuminate\Console\Command;
use Illuminate\Contracts\Queue\ShouldQueue;

class RecurringPayment extends Command implements ShouldQueue
{
    use Queueable;

    protected $signature = 'recurring:payment';

    protected $description = 'Charge cards for recurring bill';

    /**
     * Create a new command instance.
     *
     * @return void
     */

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function handle()
    {
        $subscriptions = Subscription::where('is_active', false)->get();
        foreach ($subscriptions as $subscription) {
            $ref = $subscription->reference;
            $payment = $subscription->payments->first();
            if (is_object($payment)) {
                try {
                    $paymentFactory = new PaymentFactory();
                    $initializePayment = $paymentFactory->initializePayment($ref, $subscription);
                    $initializePayment->recurringBilling();

                    //save payment
                    $auth_code = $payment->authorization;
                    $this->savePayment($subscription, $auth_code);

                    // activate subscription
                    $this->activateSubscription($subscription);
                } catch (\Exception $e) {
                    $e->getMessage();
                }
            }
        }
    }

    /**
     * @param $subscription
     * @param $auth_code
     */
    private function savePayment($subscription, $auth_code)
    {
        $subscriptionResource = new SubscriptionResource();
        $ref = $subscription->reference;
        $subscriptionResource->savePayment($subscription, $ref, $auth_code);
    }

    /**
     * @param $subscription
     */
    private function activateSubscription($subscription)
    {
        $subscriptionResource = new SubscriptionResource();
        $subscriptionResource->activateSubscription($subscription);
    }
}
