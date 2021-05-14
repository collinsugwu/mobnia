<?php

namespace App\Console\Commands;


use App\Models\File;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Console\Command;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Storage;

class CleanUpFiles extends Command implements ShouldQueue
{
    use Queueable;

    protected $signature = 'cleanup:files';

    protected $description = 'Remove soft-deleted files';


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
        //Files deleted over a month or wasn't completely uploaded
        $files = File::withTrashed()
            ->where(function ($builder) {
                $builder->whereNotNull('deleted_at')
                    ->where('deleted_at', '<=', Carbon::now()->addMonths(-1));
            })->orWhere(function ($builder) {
                $builder->where('completed', false)
                    ->where('created_at', '<=', Carbon::now()->addDays(-1));
            })->get();

        /** @var File $file */
        foreach ($files as $file) {
            $file->forceDelete();
        }
    }
}
