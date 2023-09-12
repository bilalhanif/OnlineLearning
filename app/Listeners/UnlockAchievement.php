<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\AchievementUnlocked;
use App\Models\Achievements;
use App\Models\AchievementUser;

class UnlockAchievement
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
    public function handle(AchievementUnlocked $event): void
    {
        $achievement = AchievementUser::where('user_id', $event->user->id)
                                            ->where('achievement_name', $event->name)
                                            ->count();
        if($achievement == 0){
            // Create a new achievement user instance and save it to the database
            $achievementUser = new AchievementUser();
            $achievementUser->achievement_name = $event->name;
            $achievementUser->user_id = $event->user->id;

            $achievementUser->save();
        }
    }
}
