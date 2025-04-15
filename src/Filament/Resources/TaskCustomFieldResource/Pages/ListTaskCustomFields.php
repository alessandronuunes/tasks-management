<?php

namespace Alessandronuunes\TasksManagement\Filament\Resources\TaskCustomFieldResource\Pages;

use Alessandronuunes\TasksManagement\Filament\Resources\TaskCustomFieldResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTaskCustomFields extends ListRecords
{
    protected static string $resource = TaskCustomFieldResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->modalWidth('2xl')
                ->createAnother(config('tasks-management.actions.create_another', false))
            ,
        ];
    }
}