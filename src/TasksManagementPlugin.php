<?php

declare(strict_types=1);

namespace Alessandronuunes\TasksManagement;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Alessandronuunes\TasksManagement\Filament\Resources\TaskResource;
use Alessandronuunes\TasksManagement\Filament\Widgets\TasksOverviewWidget;
use Alessandronuunes\TasksManagement\Filament\Widgets\LatestTasksWidget;

class TasksManagementPlugin implements Plugin
{
    public static function make(): static
    {
        return new static();
    }

    public function getId(): string
    {
        return 'tasks-management';
    }

    public function register(Panel $panel): void
    {
        $panel
            ->resources([
                TaskResource::class,
            ])
            ->widgets([
                TasksOverviewWidget::class,
                LatestTasksWidget::class,
            ]);
    }

    public function boot(Panel $panel): void
    {
        //
    }
}
