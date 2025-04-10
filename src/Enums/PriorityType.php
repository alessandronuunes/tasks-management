<?php

declare(strict_types=1);

namespace Alessandronuunes\TasksManagement\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum PriorityType: string implements HasColor, HasIcon, HasLabel
{
    case Urgent = 'urgent';
    case High = 'high';
    case Medium = 'medium';
    case Low = 'low';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Urgent => __('tasks-management::enums.priority.urgent'),
            self::High => __('tasks-management::enums.priority.high'),
            self::Medium => __('tasks-management::enums.priority.medium'),
            self::Low => __('tasks-management::enums.priority.low'),
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Urgent => 'danger',
            self::High => 'warning',
            self::Medium => 'info',
            self::Low => 'success',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::Urgent => 'heroicon-o-exclamation-triangle',
            self::High => 'heroicon-o-arrow-trending-up',
            self::Medium => 'heroicon-o-minus',
            self::Low => 'heroicon-o-arrow-trending-down',
        };
    }
}