<?php

namespace Alessandronuunes\TasksManagement\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum AuditEventType: string implements HasColor, HasLabel
{
    case CREATED = 'created';
    case UPDATED = 'updated';
    case DELETED = 'deleted';

    public function getColor(): string
    {
        return match($this) {
            self::CREATED => 'success',
            self::UPDATED => 'warning',
            self::DELETED => 'danger',
            default => 'gray',
        };
    }

    public function getLabel(): string
    {
        return __("tasks-management::enums.audits.events.{$this->value}");
    }
}