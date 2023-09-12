<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

use App\Models\Achievements;
use App\Models\AchievementUser;
use App\Models\Badges;
use App\Models\BadgeUser;

class AchievementsController extends Controller
{
    public function index(User $user)
    {
        $unlockedAchievements = $this->getUnlockedAchievements($user);
    
        $nextAvailableAchievements = $this->getNextAvailableAchievements($user);
    
        $currentBadge = $this->getCurrentBadge($user);
    
        $nextBadge = $this->getNextBadge($user);
    
        $remainingToUnlockNextBadge = $this->getRemainingAchievementsToUnlockNextBadge($user);
    
        return [
            'unlocked_achievements' => $unlockedAchievements,
            'next_available_achievements' => $nextAvailableAchievements,
            'current_badge' => $currentBadge,
            'next_badge' => $nextBadge,
            'remaing_to_unlock_next_badge' => $remainingToUnlockNextBadge,
        ];
    }

    public function getUnlockedAchievements(User $user){
        // Get the user
        $_user = User::find($user->id);

        if (!$_user) {
            // Handle the case where the user doesn't exist
            return [];
        }

        // Get the completed achievements for the user
        $completedAchievements = AchievementUser::where('user_id', $user->id)
                                                ->pluck('achievement_name')
                                                ->get();

        // unlock achievements
        $unlockedAchievements = $completedAchievements->toArray();

        return $unlockedAchievements;
    }

    public function getNextAvailableAchievements(User $user){
        // Get the user
        $_user = User::find($user->id);

        if (!$_user) {
            // Handle the case where the user doesn't exist
            return [];
        }

        // Get the completed achievements for the user
        $completedAchievements = AchievementUser::where('user_id', $user->id)
                                                ->pluck('achievement_name')
                                                ->get();

        // Get all achievements from the database
        $allAchievements = AchievementUser::all()->pluck('name');

        // Calculate the next available achievements
        $nextAvailableAchievements = array_diff($allAchievements->toArray(), $completedAchievements->toArray());

        return $nextAvailableAchievements;
    }

    public function getCurrentBadge(User $user){
        // Get the user
        $_user = User::find($user->id);

        if (!$_user) {
            // Handle the case where the user doesn't exist
            return "";
        }

        // Get the current badge for the user
        $badgeUser = BadgeUser::where('user_id', $user->id)->first();

        $badge_name = "";

        if(isset($badgeUser->badge_name)){
            $badge_name = $badgeUser->badge_name;
        }
        else{
            $badge = Badges::all()
                            -orderBy('required_achievements', 'asc')
                            ->first();

            if(isset($badge->name)){
                $badge_name = $badge->name;
            }
        }

        return $badge_name;
    }

    public function getNextBadge(User $user){
        // Get the user
        $_user = User::find($user->id);

        if (!$_user) {
            // Handle the case where the user doesn't exist
            return "";
        }

        // Get the current badge for the user
        $badgeUser = BadgeUser::where('user_id', $user->id)->first();

        $next_badge_name = "";

        if(isset($badgeUser->badge_name)){
            $achievementCount = AchievementUser::where('user_id', $user->id)->count();
            $badge = Badges::where('required_achievements ', '>', $achievementCount)
                            -orderBy('required_achievements', 'asc')
                            ->first();

            if(isset($badge->name)){
                $next_badge_name = $badge->name;
            }
        }
        else{
            $badge = Badges::orderBy('required_achievements', 'asc')
                            ->skip(1)
                            ->first();

            if(isset($badge->name)){
                $next_badge_name = $badge->name;
            }
        }

        return $next_badge_name;
    }

    public function getRemainingAchievementsToUnlockNextBadge(User $user){
        // Get the user
        $_user = User::find($user->id);

        if (!$_user) {
            // Handle the case where the user doesn't exist
            return "";
        }

        $next_achievement_count = 0;

        $achievementCount = AchievementUser::where('user_id', $user->id)->count();
        $badge = Badges::where('required_achievements ', '>', $achievementCount)
                        -orderBy('required_achievements', 'asc')
                        ->first();

        if(isset($badge->name)){
            $next_achievement_count = abs($achievementCount - $badge->required_achievements);
        }

        return $next_achievement_count;
    }
}
