<?php


use Illuminate\Database\Seeder;

class PaymentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $subscriptions = \App\Models\Subscription::all();
        foreach ($subscriptions as $subscription) {
            \factory(\App\Models\Payment::class)->create([
                'subscription_id' => $subscription->id,
                'payment_ref' => $subscription->reference,
            ]);
        }
    }
}
