<?php

declare(strict_types=1);

return [
    'status' => [
        'pending' => 'Pending',
        'in_progress' => 'In Progress',
        'completed' => 'Completed',
    ],
    'priority' => [
        'urgent' => 'Urgent',
        'high' => 'High',
        'medium' => 'Medium',
        'low' => 'Low',
    ],
    'type' => [
        'email' => 'Email',
        'call' => 'Call',
        'meeting' => 'Meeting',
        'visit' => 'Visit',
    ],
    'custom_fields' => [
        'types' => [
            'text' => 'Text Field',
            'select' => 'Select',
        ],
    ],
    'audits' => [
        'events' => [
            'created' => 'Created',
            'updated' => 'Updated',
            'deleted' => 'Deleted',
            'restored' => 'Restored',
        ],
    ],
];