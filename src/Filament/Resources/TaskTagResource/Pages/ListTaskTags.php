<?php

declare(strict_types=1);

namespace Alessandronuunes\TasksManagement\Filament\Resources\TaskTagResource\Pages;

use Alessandronuunes\TasksManagement\Filament\Resources\TaskTagResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTaskTags extends ListRecords
{
    protected static string $resource = TaskTagResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label(__('tasks-management::task_tags.actions.create'))
                ->modalHeading(__('tasks-management::task_tags.actions.create_modal_heading'))
                ->createAnother(config('tasks-management.actions.create_another', false))
                ->modalWidth('md')
        ];
    }
}
