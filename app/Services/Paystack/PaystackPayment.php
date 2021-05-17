<?php


namespace App\Services\Paystack;


use App\Interfaces\verifiableInterface;
use App\Models\Subscription;
use Illuminate\Http\Response;

class PaystackPayment implements verifiableInterface
{
    private $ref;
    private $subscription;
    private $paystack;

    public $authorization_code;

    public function __construct($ref, Subscription $subscription)
    {
        $this->ref = $ref;
        $this->subscription = $subscription;
        $this->paystack = new Paystack();
    }

    /**
     * Verify Successful Transaction
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function verify()
    {
        $verify = $this->paystack->verify($this->ref);
        abort_unless($verify, Response::HTTP_PAYMENT_REQUIRED);
        $this->verifyAmount();
        $this->getAuthCode();
    }

    /**
     * @param $sub
     * @param $auth_code
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function recurringBilling()
    {
        $email = $this->subscription->user->email;
        $amount = $this->subscription->amount;
        $payment = $this->subscription->payments->first();

        $auth_code = $payment->authorization;
        $this->paystack->recurringBilling($email, $amount, $auth_code);
        $verify = $this->paystack->verify($this->ref);
        abort_unless($verify, Response::HTTP_PAYMENT_REQUIRED);
        $this->verifyAmount();
    }

    /** Verify amount paid
     * @param Paystack $paystack
     */
    private function verifyAmount()
    {
        $amount_in_kobo = $this->paystack->response['data']['amount'];
        abort_if($this->subscription->amount != $amount_in_kobo,
            Response::HTTP_PAYMENT_REQUIRED, 'Wrong Amount Paid');
    }

    /**
     * @param $paystack
     */
    private function getAuthCode()
    {
        $this->authorization_code = $this->paystack->response['data']['authorization']['authorization_code'];
    }

}
