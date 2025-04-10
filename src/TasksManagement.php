<?php

declare(strict_types=1);

namespace Alessandronuunes\TasksManagement;

class TasksManagement
{
    public function getVersion(): string
    {
        return '1.0.0';
    }

    public function isMultitenancyEnabled(): bool
    {
        return config('tasks-management.features.multitenancy', false);
    }
}
