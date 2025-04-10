<?php

declare(strict_types=1);

namespace Alessandronuunes\TasksManagement;

use Alessandronuunes\TasksManagement\Events\TaskUpdatedEvent;
use Alessandronuunes\TasksManagement\Listeners\TaskUpdatedListener;
use Filament\Facades\Filament;
use Illuminate\Support\ServiceProvider;

class TasksManagementServiceProvider extends ServiceProvider
{
    protected $listen = [
        TaskUpdatedEvent::class => [
            TaskUpdatedListener::class,
        ],
    ];

    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/tasks-management.php',
            'tasks-management'
        );
    }

    public function boot(): void
    {
        // Registra os eventos
        $this->app['events']->listen(
            TaskUpdatedEvent::class,
            TaskUpdatedListener::class
        );

        // Publicar configurações
        $this->publishes([
            __DIR__.'/../config/tasks-management.php' => config_path('tasks-management.php'),
        ], 'tasks-management-config');

        // Publicar migrações
        $this->publishes([
            __DIR__.'/../database/migrations/' => database_path('migrations'),
        ], 'tasks-management-migrations');

        // Carregar migrações
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        // Registrar o Resource do Filament
        Filament::registerResources([
            \Alessandronuunes\TasksManagement\Filament\Resources\TaskResource::class,
        ]);
    }
}
