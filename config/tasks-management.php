<?php

declare(strict_types=1);

return [
    'models' => [
        'task' => Alessandronuunes\TasksManagement\Models\Task::class,
        'user' => App\Models\User::class,
        'team' => App\Models\Team::class,
        'comment' => App\Models\Comment::class,
    ],

    'tables' => [
        'tasks' => 'tasks',
        'task_user' => 'task_user',
    ],

    'features' => [
        'multitenancy' => false,
    ],

    'morphable_types' => [
        // Define os tipos de modelos que podem ser relacionados com tarefas
        // 'App\Models\Lead' => 'Leads',
    ],

    'navigation' => [
        'group' => 'Tasks',
        'sort' => 30,
    ],
];
