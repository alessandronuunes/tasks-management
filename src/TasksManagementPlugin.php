<?php

declare(strict_types=1);

namespace Alessandronuunes\TasksManagement;

use Filament\Contracts\Plugin;
use Filament\Panel;

class TasksManagementPlugin implements Plugin
{
    public function getId(): string
    {
        return 'tasks-management';
    }

    public function register(Panel $panel): void
    {
        $panel
            ->resources([
                Filament\Resources\TaskResource::class,
            ])
            ->widgets([
                Filament\Widgets\TasksOverviewWidget::class,
                Filament\Widgets\LatestTasksWidget::class,
            ]);
    }

    public function boot(Panel $panel): void
    {
        //
    }

    public static function make(): static
    {
        return app(static::class);
    }

    public static function get(): static
    {
        /** @var static $plugin */
        $plugin = filament(app(static::class)->getId());

        return $plugin;
    }
}
