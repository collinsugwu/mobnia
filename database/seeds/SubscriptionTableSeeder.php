<?php

use App\Models\Auth\User;
use App\Models\Plan;
use App\Models\Subscription;
use Illuminate\Database\Seeder;

class SubscriptionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::get();
        $plans = Plan::get();
        \factory(Subscription::class)->create([
            'is_active' => true,
            'user_id' => $users->first()->id,
            'amount' => $plans->last()->amount,
            'plan_id' => $plans->last()->id,
        ]);
        \factory(Subscription::class)->create([
            'is_active' => false,
            'user_id' => $users->last()->id,
            'amount' => $plans->first()->amount,
            'plan_id' => $plans->first()->id,
        ]);
    }
}
