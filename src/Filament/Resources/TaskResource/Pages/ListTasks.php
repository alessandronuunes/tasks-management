<?php

declare(strict_types=1);

namespace Alessandronuunes\TasksManagement\Filament\Resources\TaskResource\Pages;

use Alessandronuunes\TasksManagement\Filament\Resources\TaskResource;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Enums\MaxWidth;
use Illuminate\Database\Eloquent\Builder;

class ListTasks extends ListRecords
{
    protected static string $resource = TaskResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label(__('tasks-management::tasks.add'))
                ->extraModalWindowAttributes(['class' => '[&_.fi-modal-content]:!p-0'])
                ->modalWidth(MaxWidth::TwoExtraLarge),
        ];
    }

    private function getAssignedTasksCount(): ?int
    {
        $userId = auth()->id();
        $result = static::getModel()::query()
            ->where('user_id', '!=', $userId)
            ->whereHas('users', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->count();

        return $result ?: null;
    }

    public function getTabs(): array
    {
        $userId = auth()->id();

        return [
            'all' => Tab::make(__('tasks-management::tasks.all_tasks'))
                ->icon('heroicon-o-ellipsis-horizontal-circle')
                ->modifyQueryUsing(fn (Builder $query) => $query),
            'assigned' => Tab::make(__('tasks-management::tasks.assigned_tasks'))
                ->icon('heroicon-o-user-circle')
                ->badge($this->getAssignedTasksCount())
                ->modifyQueryUsing(
                    fn (Builder $query) => $query
                        ->whereHas('users', function ($query) use ($userId) {
                            $query->where('users.id', $userId);
                        })
                ),
            'created' => Tab::make(__('tasks-management::tasks.created_tasks'))
                ->icon('heroicon-o-document-plus')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('user_id', $userId)),
        ];
    }
}
