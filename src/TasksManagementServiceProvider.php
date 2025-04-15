<?php

declare(strict_types=1);

namespace Alessandronuunes\TasksManagement;

use Alessandronuunes\TasksManagement\Events\TaskCreatedEvent;
use Alessandronuunes\TasksManagement\Events\TaskUpdatedEvent;
use Alessandronuunes\TasksManagement\Filament\Resources\TaskCustomFieldResource;
use Alessandronuunes\TasksManagement\Http\Middleware\EnsureTeamMiddleware;
use Alessandronuunes\TasksManagement\Listeners\SendTaskCreatedNotification;
use Alessandronuunes\TasksManagement\Listeners\SendTaskUpdatedNotification;
use Alessandronuunes\TasksManagement\Models\Comment;
use Alessandronuunes\TasksManagement\Models\Task;
use Alessandronuunes\TasksManagement\Models\TaskTag;
use Alessandronuunes\TasksManagement\Observers\CommentObserver;
use Alessandronuunes\TasksManagement\Observers\TaskObserver;
use Alessandronuunes\TasksManagement\Observers\TaskTagObserver;
use Filament\Facades\Filament;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class TasksManagementServiceProvider extends PackageServiceProvider
{
    public static string $name = 'tasks-management';

    public function configurePackage(Package $package): void
    {
        $package
            ->name(static::$name)
            ->hasConfigFile()
            ->hasTranslations()
            ->hasViews()
            ->hasAssets()
            ->hasMigrations([
                'create_tasks_table',
                'create_task_user_table',
                'create_comments_table',
                'create_task_tags_table',
                'create_task_attachments_table',
                'create_task_custom_fields_table',
                'create_task_custom_field_values_table'
            ])
            ->hasCommands([
                Console\Commands\InstallCommand::class,
            ]);

        // Add translations publishing
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../resources/lang' => resource_path('lang/vendor/tasks-management'),
            ], 'tasks-management-translations');
        }
    }

    public function packageBooted(): void
    {
        parent::packageBooted();

        // Register Resources
        $this->app->booted(function () {
            if (class_exists(\Filament\Facades\Filament::class)) {
                \Filament\Facades\Filament::serving(function () {
                    \Filament\Facades\Filament::registerResources([
                        \Alessandronuunes\TasksManagement\Filament\Resources\TaskResource::class,
                        \Alessandronuunes\TasksManagement\Filament\Resources\TaskCustomFieldResource::class,
                        \Alessandronuunes\TasksManagement\Filament\Resources\TaskTagResource::class,
                    ]);
                });
            }
        });

        Task::observe(TaskObserver::class);
        Comment::observe(CommentObserver::class);
        TaskTag::observe(TaskTagObserver::class);
        // Fix: Register events individually instead of using array
        $this->app['events']->listen(
            TaskCreatedEvent::class,
            [SendTaskCreatedNotification::class, 'handle']
        );

        $this->app['events']->listen(
            TaskUpdatedEvent::class,
            [SendTaskUpdatedNotification::class, 'handle']
        );

        if (config('tasks-management.use_teams')) {
            $this->app['router']->aliasMiddleware('ensure-team', EnsureTeamMiddleware::class);
        }
    }

    public function boot(): void
    {
        parent::boot();

        // Set the locale from config
        $locale = config('tasks-management.locale', 'en');
        app()->setLocale($locale);
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'tasks-management');
    }
}
