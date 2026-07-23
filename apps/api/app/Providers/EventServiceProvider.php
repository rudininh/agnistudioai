<?php

namespace App\Providers;

use App\Events\ContentIdeaCreated;
use App\Events\ContentIdeaUpdated;
use App\Events\ProjectCreated;
use App\Events\ProjectUpdated;
use App\Events\UserRegistered;
use App\Events\UserUpdated;
use App\Events\WorkspaceCreated;
use App\Events\WorkspaceUpdated;
use App\Listeners\LogContentIdeaCreated;
use App\Listeners\LogContentIdeaUpdated;
use App\Listeners\LogProjectCreation;
use App\Listeners\LogProjectUpdate;
use App\Listeners\LogUserUpdate;
use App\Listeners\LogWorkspaceUpdate;
use App\Listeners\SendWelcomeEmail;
use App\Listeners\SendWelcomeNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        WorkspaceCreated::class => [
            SendWelcomeNotification::class,
        ],
        ProjectCreated::class => [
            LogProjectCreation::class,
        ],
        ProjectUpdated::class => [
            LogProjectUpdate::class,
        ],
        WorkspaceUpdated::class => [
            LogWorkspaceUpdate::class,
        ],
        UserRegistered::class => [
            SendWelcomeEmail::class,
        ],
        UserUpdated::class => [
            LogUserUpdate::class,
        ],
        ContentIdeaCreated::class => [
            LogContentIdeaCreated::class,
        ],
        ContentIdeaUpdated::class => [
            LogContentIdeaUpdated::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
