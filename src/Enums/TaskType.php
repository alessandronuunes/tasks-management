<?php

declare(strict_types=1);

namespace Alessandronuunes\TasksManagement\Enums;

use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum TaskType: string implements HasLabel, HasIcon
{
    case Email = 'email';
    case Call = 'call';
    case Meeting = 'meeting';
    case Visit = 'visit';

    public function getLabel(): string
    {
        return match ($this) {
            self::Email => __('tasks-management::enums.type.email'),
            self::Call => __('tasks-management::enums.type.call'),
            self::Meeting => __('tasks-management::enums.type.meeting'),
            self::Visit => __('tasks-management::enums.type.visit'),
        };
    }

    public function getIcon(): string
    {
        return match ($this) {
            self::Email => 'heroicon-o-envelope',
            self::Call => 'heroicon-o-phone',
            self::Meeting => 'heroicon-o-users',
            self::Visit => 'heroicon-o-map-pin',
        };
    }
}
