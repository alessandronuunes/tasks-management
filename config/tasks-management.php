<?php

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
        'task' => \Alessandronuunes\TasksManagement\Models\Task::class,
        'comment' => \Alessandronuunes\TasksManagement\Models\Comment::class,
        'user' => \App\Models\User::class,
        'team' => null,
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
];