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
        'custom_field_value' => \Alessandronuunes\TasksManagement\Models\TaskCustomFieldValue::class,
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

    /*
    |--------------------------------------------------------------------------
    | Locale configuration
    |--------------------------------------------------------------------------
    |
    | Configure which widgets should be displayed on the dashboard.
    |
    */

    'locale' => 'pt_BR',
    /*
    |--------------------------------------------------------------------------
    | Actions
    |--------------------------------------------------------------------------
    |
    | Configure which widgets should be displayed on the dashboard.
    |
    */

    'actions' => [
        'create_another' => false, // Default value for createAnother in actions
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
    | Attachments configuration
    |--------------------------------------------------------------------------
    |
    |
    */
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

    /*
    |--------------------------------------------------------------------------
    | Resources Configuration
    |--------------------------------------------------------------------------
    |
    |
    */
    'resources' => [
        'tasks' => [
            'enabled' => true,
            'navigation' => [
                'group' => null,
                'sort' => 1,
                'icon' => 'heroicon-o-rectangle-stack',
                'parent' => null,
                'slug' => 'tasks'
            ],
        ],
        'task_tags' => [
            'enabled' => true,
            'navigation' => [
                'group' => 'Settings',
                'sort' => 3,
                'icon' => 'heroicon-o-tag',
                'parent' => 'Tasks',
                'slug' => 'task/tags'
            ],
        ],
        'custom_fields' => [
            'enabled' => true,
            'navigation' => [
                'group' => null,
                'sort' => 2,
                'icon' => 'heroicon-o-rectangle-stack',
                'parent' => 'Tasks',
                'slug' => 'task/custom-fields'
            ],
        ],
    ]
];
