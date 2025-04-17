<?php

declare(strict_types=1);

return [
    'common' => [
        'name' => 'Name',
        'description' => 'Description',
        'created_at' => 'Created at',
        'updated_at' => 'Updated at',
    ],
    'tasks' => [
        'status' => 'Status',
        'priority' => 'Priority',
        'type' => 'Type',
        'users' => 'Users',
        'starts_at' => 'Start Date',
        'ends_at' => 'End Date',
        'assigned_users' => 'Assigned Users',
        'tags' => 'Tags',
        'attachments' => 'Attachments',
        'sections' => [
            'task_data' => 'Task Data',
            'properties' => 'Properties'
        ],
        'placeholders' => [
            'tags' => 'Select tags'
        ]
    ],
    'custom_fields' => [
        'code' => 'Code',
        'type' => 'Type',
        'options' => 'Options',
        'is_required' => 'Required',
        'sort_order' => 'Sort Order',
        'types' => [
            'text' => 'Text Input',
            'select' => 'Select',
        ],
        'placeholder' => 'Placeholder',
        'help_text' => 'Help Text',
        'hint' => 'Hint',
        'settings' => 'Settings',
        'preview' => 'Preview',
        'preview_label' => 'Field Preview',
    ],
    'task_tags' => [
        'color' => 'Color',
    ],
    'comments' => [
        'title' => 'Comments',
        'content' => 'Content',
        'user' => 'User',
        'created_at' => 'Created at',
        'actions' => [
            'create' => 'Add comment',
        ],
        'modal' => [
            'new' => 'New Comment',
        ],
    ],
    'audits' => [
        'event' => 'Event',
        'user' => 'User',
        'created_at' => 'Date',
        'ip_address' => 'IP',
        'field' => 'Field',
        'old_value' => 'Previous Value',
        'new_value' => 'New Value',
        'sections' => [
            'details' => 'Log Details',
            'changes' => 'Changes',
        ],
        'columns' => [
            'field' => 'Field',
            'old_value' => 'Previous Value',
            'new_value' => 'New Value',
        ],
        'messages' => [
            'no_changes' => 'No changes recorded',
            'system' => 'System',
        ],
    ],
];