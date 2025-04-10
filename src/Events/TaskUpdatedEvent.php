<?php

declare(strict_types=1);

namespace Alessandronuunes\TasksManagement\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TaskUpdatedEvent
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(public $task)
    {
    }
}
