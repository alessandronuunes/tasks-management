<?php

declare(strict_types=1);

namespace Alessandronuunes\TasksManagement\Enums;

enum TaskType: string
{
    case General = 'general';
    case Bug = 'bug';
    case Feature = 'feature';
    case Documentation = 'documentation';

    public function getLabel(): string
    {
        return match ($this) {
            self::General => 'Geral',
            self::Bug => 'Bug',
            self::Feature => 'Feature',
            self::Documentation => 'Documentação',
        };
    }
}
