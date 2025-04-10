<?php

declare(strict_types=1);

namespace Alessandronuunes\TasksManagement\Filament\Resources\TaskResource\Pages;

use Alessandronuunes\TasksManagement\Events\TaskUpdatedEvent;
use Alessandronuunes\TasksManagement\Filament\Resources\TaskResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTask extends EditRecord
{
    protected static string $resource = TaskResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        event(new TaskUpdatedEvent($this->record));
    }

    protected function beforeFill(): void
    {
        if (! isset($this->record->id)) {
            return;
        }

        if ($this->record->users()->wherePivot('read_at', null)->exists()) {
            $this->record->users()->updateExistingPivot(auth()->id(), ['read_at' => now()]);
        }
    }
}
