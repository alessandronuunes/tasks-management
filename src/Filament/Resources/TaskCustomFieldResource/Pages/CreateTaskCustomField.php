<?php

namespace Alessandronuunes\TasksManagement\Filament\Resources\TaskCustomFieldResource\Pages;

use Alessandronuunes\TasksManagement\Filament\Resources\TaskCustomFieldResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTaskCustomField extends CreateRecord
{
    protected static string $resource = TaskCustomFieldResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        return $data;
    }
}