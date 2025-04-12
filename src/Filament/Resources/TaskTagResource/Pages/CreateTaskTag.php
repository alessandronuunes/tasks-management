<?php

declare(strict_types=1);

namespace Alessandronuunes\TasksManagement\Filament\Resources\TaskTagResource\Pages;
use Alessandronuunes\TasksManagement\Filament\Resources\TaskTagResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTaskTag extends CreateRecord
{
    protected static string $resource = TaskTagResource::class;
}
