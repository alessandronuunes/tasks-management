<?php

namespace Alessandronuunes\TasksManagement\Filament\Resources\TaskCustomFieldResource\Pages;

use Alessandronuunes\TasksManagement\Filament\Resources\TaskCustomFieldResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTaskCustomField extends EditRecord
{
    protected static string $resource = TaskCustomFieldResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}