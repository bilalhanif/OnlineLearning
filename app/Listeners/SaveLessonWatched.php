<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

use App\Events\LessonWatched;
use App\Events\AchievementUnlocked;
use App\Events\BadgeUnlocked;

use App\Models\Lesson;
use App\Models\LessonUser;
use App\Models\Achievements;
use App\Models\AchievementUser;
use App\Models\Badges;
use App\Models\BadgeUser;

class SaveLessonWatched
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
    public function handle(LessonUser $event): void
    {
        // Create a new lesson user instance and save it to the database
        $lessonUser = new LessonUser();
        $lessonUser->user_id = $event->user->id;
        $lessonUser->lesson_id = $event->lesson->id;
        $lessonUser->watched = true;

        $lessonUser->save();

        $lessonCount = LessonUser::where('user_id', $event->user->id)->count();

        //Get Lessons and call achievement unlock event
        if($lessonCount > 0){
            $achievement = Achievement::where('type', 2)
                            ->where('required_count', '<=', $lessonCount)
                            -orderBy('required_count', 'desc')
                            ->first();

            if (isset($achievement->name)) {
                event(new AchievementUnlocked($achievement->name, $event->user));
            }
        }

        //Get Badge and call badeg event
        $achievementCount = AchievementUser::where('user_id', $event->user->id)->count();
        $badge = Badges::where('required_achievements ', '<=', $achievementCount)
                            -orderBy('required_achievements', 'desc')
                            ->first();
        if (isset($badge->name)) {
            event(new BadgeUnlocked($badge->name, $event->user));
        }
    }
}
