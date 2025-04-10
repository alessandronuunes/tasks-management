<?php

declare(strict_types=1);

namespace Alessandronuunes\TasksManagement\Observers;

use Alessandronuunes\TasksManagement\Events\TaskCreatedEvent;
use Alessandronuunes\TasksManagement\Events\TaskUpdatedEvent;
use Alessandronuunes\TasksManagement\Models\Task;

class TaskObserver
{
    public function created(Task $task): void
    {
        if ($task->users->isNotEmpty()) {
            TaskCreatedEvent::dispatch($task);
        }
    }

    public function updated(Task $task): void
    {
        if ($task->wasChanged(['status', 'priority', 'ends_at'])) {
            TaskUpdatedEvent::dispatch($task);
        }
    }

    public function deleting(Task $task): void
    {
        $task->comments()->delete();
        $task->users()->detach();
    }
}