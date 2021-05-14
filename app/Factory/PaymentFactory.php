<?php


namespace App\Factory;


use App\Models\Subscription;
use App\Services\Paystack\PaystackPayment;

class PaymentFactory
{
    /**
     * @param $ref
     * @param Subscription $subscription
     * @return PaystackPayment
     */
    public function initializePayment($ref, Subscription $subscription)
    {
        return new PaystackPayment($ref, $subscription);
    }
}
