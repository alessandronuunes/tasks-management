<?php

declare(strict_types=1);

namespace Alessandronuunes\TasksManagement\Observers;

use Alessandronuunes\TasksManagement\Models\TaskTag;

class TaskTagObserver
{
    public function creating(TaskTag $taskTag): void
    {
        if (config('tasks-management.use_teams', false)) {
            $taskTag->team_id = auth()->user()->current_team_id;
        }
    }
}