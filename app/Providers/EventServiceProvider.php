<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\CommentWritten;
use Illuminate\Auth\Events\LessonWatched;
use Illuminate\Auth\Events\AchievementUnlocked;
use Illuminate\Auth\Events\BadgeUnlocked;

use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Auth\Listeners\SaveComment;
use Illuminate\Auth\Listeners\SaveLessonWatched;
use Illuminate\Auth\Listeners\UnlockAchievement;
use Illuminate\Auth\Listeners\UnlockBadge;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        CommentWritten::class => [
            SaveComment::class,
        ],
        LessonWatched::class => [
            SaveLessonWatched::class,
        ],
        AchievementUnlocked::class => [
            UnlockAchievement::class,
        ],
        BadgeUnlocked::class => [
            UnlockBadge::class,
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
