<?php

declare(strict_types=1);

namespace Alessandronuunes\TasksManagement\Events;

use Alessandronuunes\TasksManagement\Models\Task;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TaskCreatedEvent
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(public Task $task)
    {
    }
}
