<?php

declare(strict_types=1);

namespace Alessandronuunes\TasksManagement\Observers;

use Alessandronuunes\TasksManagement\Models\Task;
use Alessandronuunes\TasksManagement\Services\LoggingService;

class TaskObserver
{
    public function created(Task $task): void
    {
        LoggingService::log($task, 'created', $task->getAttributes(), []);
    }

    public function updated(Task $task): void
    {   
        LoggingService::log(
            $task, 
            'updated', 
            $task->getChanges(),
            $task->getOriginal()
        );
    }

    public function deleted(Task $task): void
    {
        LoggingService::log($task, 'deleted', [], $task->getAttributes());
    }

    public function restored(Task $task): void
    {   
        LoggingService::log($task, 'restored', $task->getAttributes(), []);
    }

}
