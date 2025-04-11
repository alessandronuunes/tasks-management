<?php

declare(strict_types=1);

namespace Alessandronuunes\TasksManagement\Filament\Resources\TaskTagResource\Pages;

use Alessandronuunes\TasksManagement\Filament\Resources\TaskTagResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTaskTag extends EditRecord
{
    protected static string $resource = TaskTagResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
