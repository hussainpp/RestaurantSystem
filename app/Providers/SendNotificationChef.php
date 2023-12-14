<?php

namespace App\Providers;

use App\Providers\CreatedOrder;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendNotificationChef
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
    public function handle(CreatedOrder $event): void
    {
        //
    }

    // public function subscribe(Dispatcher $events): void
    // {
    //     $events->listen(
    //         Login::class,
    //         [UserEventSubscriber::class, 'handleUserLogin']
    //     );
 
    //     $events->listen(
    //         Logout::class,
    //         [UserEventSubscriber::class, 'handleUserLogout']
    //     );
    // }
}
