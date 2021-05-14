<?php


namespace App\Console\Commands;


use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Console\Command;
use Illuminate\Contracts\Queue\ShouldQueue;

class DeactivateSubscription extends Command implements ShouldQueue
{
    use Queueable;

    protected $signature = 'deactivate:sub';

    protected $description = 'Deactivate expired subscriptions';

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
     *
     */
    public function handle()
    {
        $subscriptions = Subscription::where('is_active', true)->get();
        foreach ($subscriptions as $subscription) {
            $payment = $subscription->payments->last();
            if ($payment->end_at > Carbon::now()) {
                continue;
            }
            $subscription->update([
                'is_active' => false,
            ]);
        }
    }
}
