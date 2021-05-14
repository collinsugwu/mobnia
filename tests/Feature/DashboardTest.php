<?php


namespace Feature;


use App\Models\Auth\User;
use App\Models\Plan;
use App\Models\Subscription;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed');
    }

    public function testCanAccessDashboard()
    {
        $user = \factory(User::class)->create();
        $this->loginAs($user);
        $plan = Plan::first();
        $sub = Subscription::create([
            'plan_id' => $plan->id,
            'amount' => $plan->amount,
            'is_active' => true,
            'user_id' => $user->id,
        ]);
        $this->sendGet('dashboard');
        $this->assertSuccessResponse();
    }

    public function testCantAccessDashboard()
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
        $this->sendGet('dashboard');
        $this->assertErrorResponse(401);
    }
}
