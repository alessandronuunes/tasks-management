<?php

declare(strict_types=1);

namespace Alessandronuunes\TasksManagement\Enums;

enum TaskStatus: string
{
    case Pending = 'pending';
    case InProgress = 'in_progress';
    case Completed = 'completed';
    case Cancelled = 'cancelled';

    public function getLabel(): string
    {
        return match ($this) {
            self::Pending => 'Pendente',
            self::InProgress => 'Em Progresso',
            self::Completed => 'Concluída',
            self::Cancelled => 'Cancelada',
        };
    }
}
