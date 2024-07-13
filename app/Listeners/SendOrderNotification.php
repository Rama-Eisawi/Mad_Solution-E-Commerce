<?php

namespace App\Listeners;

use App\Events\OrderCreated;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Notifications\OrderCreated as OrderCreatedNotification;

class SendOrderNotification
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(OrderCreated $event)
    {
        $admin_users = User::where('admin', 1)->get();
        foreach ($admin_users as $admin) {
            $admin->notify(new OrderCreatedNotification($event->order));
        }
    }
}
