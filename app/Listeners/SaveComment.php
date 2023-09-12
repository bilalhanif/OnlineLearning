<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

use App\Events\CommentWritten;
use App\Events\AchievementUnlocked;
use App\Events\BadgeUnlocked;

use App\Models\Comment;
use App\Models\Achievements;
use App\Models\AchievementUser;
use App\Models\Badges;
use App\Models\BadgeUser;


class SaveComment
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
    public function handle(CommentWritten $event): void
    {
        // Create a new comment instance and save it to the database
        $comment = new Comment();
        $comment->body = $event->comment->body;
        $comment->user_id = $event->user->id;

        $comment->save();

        $commentCount = Comment::where('user_id', $event->user->id)->count();


        //Get Commnets and call achievement unlock event
        if($commentCount > 0){
            $achievement = Achievement::where('type', 1)
                            ->where('required_count', '<=', $commentCount)
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
