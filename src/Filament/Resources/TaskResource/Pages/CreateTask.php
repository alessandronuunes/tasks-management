<?php

namespace Alessandronuunes\TasksManagement\Filament\Resources\TaskResource\Pages;

use Alessandronuunes\TasksManagement\Filament\Resources\TaskResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTask extends CreateRecord
{
    protected static string $resource = TaskResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $customFields = $data['custom_fields'] ?? [];
        unset($data['custom_fields']);
        
        $this->customFields = $customFields;
        
        return $data;
    }

    protected function afterCreate(): void
    {
        $task = $this->record;
        
        if (isset($this->customFields)) {
            foreach ($this->customFields as $code => $value) {
                if ($value !== null && $value !== '') {
                    $task->setCustomFieldValue($code, $value);
                }
            }
        }
    }
}