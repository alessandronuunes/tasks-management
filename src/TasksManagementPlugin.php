<?php

declare(strict_types=1);

namespace Alessandronuunes\TasksManagement;

use Alessandronuunes\TasksManagement\Filament\Resources\TaskCustomFieldResource;
use Filament\Panel;
use Filament\Contracts\Plugin;
use Alessandronuunes\TasksManagement\Filament\Resources\TaskResource;
use Alessandronuunes\TasksManagement\Filament\Resources\TaskTagResource;
use Alessandronuunes\TasksManagement\Filament\Widgets\LatestTasksWidget;
use Alessandronuunes\TasksManagement\Filament\Widgets\TasksOverviewWidget;

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
                TaskTagResource::class,
                TaskCustomFieldResource::class,
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
