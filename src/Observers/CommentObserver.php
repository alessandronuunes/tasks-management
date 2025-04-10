<?php

declare(strict_types=1);

namespace Alessandronuunes\TasksManagement\Observers;

use Alessandronuunes\TasksManagement\Models\Comment;

class CommentObserver
{
    public function creating(Comment $comment): void
    {
        if (config('tasks-management.use_teams')) {
            $comment->team_id = auth()->user()->current_team_id;
        }

        $comment->user_id = auth()->id();
    }
}
