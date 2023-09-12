<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\BadgeUnlocked;
use App\Models\Badges;
use App\Models\BadgeUser;

class UnlockBadge
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
    public function handle(BadgeUnlocked $event): void
    {
        $badge = BadgeUser::where('user_id', $event->user->id)->first();

        if(!isset($badge->id)){
            // Create a new badge user instance and save it to the database
            $badgeUser = new BadgeUser();
            $badgeUser->badge_name = $event->name;
            $badgeUser->user_id = $event->user->id;

            $badgeUser->save();
        }
        else{
            // Udpate badge name if exists
            if($badge->badge_name != $event->name){
                $badge->badge_name = $event->name;
                $badge->save();
            }   
        }
    }
}
