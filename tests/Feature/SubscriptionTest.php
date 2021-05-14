<?php


namespace Feature;


use App\Models\Auth\User;
use App\Models\Payment;
use App\Models\Plan;
use App\Models\Subscription;
use App\Services\Paystack\Paystack;
use Carbon\Carbon;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Tests\TestCase;

class SubscriptionTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed');
    }

    public function testChooseSubscription()
    {
        $plan = Plan::first();

        $user = \factory(User::class)->create();
        $this->loginAs($user);
        $this->sendGet("plan/$plan->id");
        $this->assertSuccessResponse();
        $subscription = Subscription::where(['user_id' => $user->id, 'plan_id' => $plan->id])->first();
        $this->assertEquals($subscription, $plan->subscriptions->last());
    }

    public function testCantChooseSubscription()
    {
        $plan = Plan::first();

        $this->sendGet("plan/$plan->id");
        $this->assertErrorResponse(401);
    }

    public function testVerifyPayment()
    {
        $user = \factory(User::class)->create();
        $this->loginAs($user);
        $plan = Plan::first();
        $sub = Subscription::create([
            'plan_id' => $plan->id,
            'amount' => $plan->amount,
            'is_active' => false,
            'user_id' => $user->id,
        ]);
        Paystack::fake($sub->amount);
        $data = [
            'ref' => $sub->reference,
            'amount' => $sub->amount,
        ];
        $this->sendPost("plan/payments/verify", $data);
        $this->assertSuccessResponse();
        $sub = Subscription::findByReference($sub->reference);
        $this->assertTrue($sub->is_active == true);
    }

    public function testRecurringPayment()
    {
        $user = \factory(User::class)->create();
        $this->loginAs($user);
        $plan = Plan::first();
        $sub = Subscription::create([
            'plan_id' => $plan->id,
            'amount' => $plan->amount,
            'is_active' => false,
            'user_id' => $user->id,
        ]);
        Payment::create([
            'subscription_id' => $sub->id,
            'start_at' => \Carbon\Carbon::now(),
            'paid_at' => \Carbon\Carbon::now(),
            'payment_ref' => $sub->id,
            'authorization' => 'AUTH_8dfhjjdt',
            'end_at' => \Carbon\Carbon::now()->addMonth(),
        ]);
        Paystack::fake($sub->amount);
        $this->artisan('recurring:payment');

        $sub = Subscription::find($sub->id);
        $this->assertTrue($sub->is_active == true);
    }

    public function testDeactivateSubscription()
    {
        $sub = Subscription::where('is_active', true)->first();
        $sub->payments->last()->update([
            'end_at' => Carbon::now()->subDay(2)
        ]);
        $this->artisan('deactivate:sub');

        $sub->refresh();
        $this->assertTrue($sub->is_active == false);
    }
}
