<?php

namespace App\Services\Contractors\Base\Jobs;

use App\Services\Order\Models\Order;
use App\Services\Order\Models\OrderLog;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class OrderTruncation implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
//        Order::query()
//            ->where('created_at', '<', now()->subDays(10)->startOfDay())
//            ->delete();

        OrderLog::query()
            ->where('created_at', '<', now()->subDays(2)->startOfDay())
            ->delete();
    }
}
