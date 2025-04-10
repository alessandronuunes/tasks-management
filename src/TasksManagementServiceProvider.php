<?php

declare(strict_types=1);

namespace Alessandronuunes\TasksManagement;

use Alessandronuunes\TasksManagement\Events\TaskCreatedEvent;
use Alessandronuunes\TasksManagement\Events\TaskUpdatedEvent;
use Alessandronuunes\TasksManagement\Http\Middleware\EnsureTeamMiddleware;
use Alessandronuunes\TasksManagement\Listeners\SendTaskCreatedNotification;
use Alessandronuunes\TasksManagement\Listeners\SendTaskUpdatedNotification;
use Alessandronuunes\TasksManagement\Models\Comment;
use Alessandronuunes\TasksManagement\Models\Task;
use Alessandronuunes\TasksManagement\Observers\CommentObserver;
use Alessandronuunes\TasksManagement\Observers\TaskObserver;
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
                'create_comments_table'
            ])
            ->hasCommands([
                Console\Commands\InstallCommand::class,
            ]);
    }

    public function packageBooted(): void
    {
        parent::packageBooted();

        Task::observe(TaskObserver::class);
        Comment::observe(CommentObserver::class);

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
}