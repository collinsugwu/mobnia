<?php

namespace App\Console;

use App\Console\Commands\CleanUpFiles;
use App\Console\Commands\DeactivateSubscription;
use App\Console\Commands\RecurringPayment;
use Illuminate\Console\Scheduling\Schedule;
use Laravel\Lumen\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        CleanUpFiles::class,
        RecurringPayment::class,
        DeactivateSubscription::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('cleanup:files')->daily();
        $schedule->command('recurring:payment')->daily();
        $schedule->command('deactivate:sub')->daily();
    }
}
