<?php

namespace App\Providers;

use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\LeadDeleted' => [
            'App\Listeners\LeadDeletedListener',
        ],
        'App\Events\ContactDeleted' => [
            'App\Listeners\ContactDeletedListener',
        ],
        'App\Events\AccountDeleted' => [
            'App\Listeners\AccountDeletedListener',
        ],
        'App\Events\DealDeleted' => [
            'App\Listeners\DealDeletedListener',
        ],
        'App\Events\TaskDeleted' => [
            'App\Listeners\TaskDeletedListener',
        ],
        'App\Events\UserCreated' => [
            'App\Listeners\UserCreatedListener',
        ],
    ];

    /**
     * Register any other events for your application.
     *
     * @param  \Illuminate\Contracts\Events\Dispatcher  $events
     * @return void
     */
    public function boot(DispatcherContract $events)
    {
        parent::boot($events);

        //
    }
}
