<?php

namespace App\Console;

use App\Models\Order;
use Carbon\Carbon;
use Illuminate\{Console\Scheduling\Schedule, Foundation\Console\Kernel as ConsoleKernel, Support\Facades\Log};

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->call(function () {
            $this->deleteDeliveredOrders();
        })->everyMinute();
    }
    protected function deleteDeliveredOrders()
    {
        $threeDaysAgo = Carbon::now()->subDays(3);

        $deletedOrders = Order::where('status', 'delivered')
            ->where('delivered_at', '<=', $threeDaysAgo)
            ->delete();
        
        Log::info("Deleted $deletedOrders delivered orders that are older than 3 days.");
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
