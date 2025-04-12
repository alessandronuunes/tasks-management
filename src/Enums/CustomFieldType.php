<?php

declare(strict_types=1);

namespace Alessandronuunes\TasksManagement\Enums;

enum CustomFieldType: string
{
    case Text = 'text';
    case Select = 'select';

    public function getLabel(): string
    {
        return match ($this) {
            self::Text => __('tasks-management::fields.custom_fields.types.text'),
            self::Select => __('tasks-management::fields.custom_fields.types.select'),
        };
    }
}