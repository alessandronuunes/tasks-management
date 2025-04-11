<?php

declare(strict_types=1);

return [
    /*
    |--------------------------------------------------------------------------
    | Team Support
    |--------------------------------------------------------------------------
    |
    | This option controls whether the plugin should support team functionality.
    | When enabled, tasks will be scoped to teams.
    |
    */
    'use_teams' => false,

    /*
    |--------------------------------------------------------------------------
    | Models Configuration
    |--------------------------------------------------------------------------
    |
    | Here you can specify custom models to use for tasks and related entities.
    |
    */
    'models' => [
        'task' => Alessandronuunes\TasksManagement\Models\Task::class,
        'comment' => Alessandronuunes\TasksManagement\Models\Comment::class,
        'user' => App\Models\User::class,
        'team' => null,
    ],
    /*
    |--------------------------------------------------------------------------
    | Navigation Configuration
    |--------------------------------------------------------------------------
    */
    'navigation' => [
        'icon' => 'heroicon-o-rectangle-stack',
        'sort' => 30,
    ],
    /*
    |--------------------------------------------------------------------------
    | Notifications
    |--------------------------------------------------------------------------
    |
    | Configure notification settings for tasks.
    |
    */
    'notifications' => [
        'enabled' => true,
        'channels' => ['database', 'mail'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Dashboard Widgets
    |--------------------------------------------------------------------------
    |
    | Configure which widgets should be displayed on the dashboard.
    |
    */
    'widgets' => [
        'tasks_overview' => true,
        'latest_tasks' => true,
    ],
    /**
     * Locale configuration
     */
    'locale' => 'pt_BR',

    'actions' => [
        'create_another' => false, // Default value for createAnother in actions
    ],
    'attachments' => [
        'disk' => 'public',
        'max_size' => 10240, // 10MB em kilobytes
        'allowed_types' => [
            'image/*',
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ],
    ],
];
