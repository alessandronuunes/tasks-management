<?php

declare(strict_types=1);

namespace Alessandronuunes\TasksManagement\Facades;

use Illuminate\Support\Facades\Facade;

class TasksManagement extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'tasks-management';
    }
}
