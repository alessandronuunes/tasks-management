<?php

declare(strict_types=1);

namespace Alessandronuunes\TasksManagement\Filament\Widgets;

use Alessandronuunes\TasksManagement\Models\Task;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestTasksWidget extends BaseWidget
{
    protected static ?int $sort = 2;

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Task::query()
                    ->when(
                        config('tasks-management.use_teams'),
                        fn ($query) => $query->where('team_id', auth()->user()->current_team_id)
                    )
                    ->latest()
                    ->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('tasks-management::tasks.columns.title'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->label(__('tasks-management::tasks.columns.status'))
                    ->badge(),
                Tables\Columns\TextColumn::make('priority')
                    ->label(__('tasks-management::tasks.columns.priority'))
                    ->badge(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('tasks-management::tasks.columns.created_at'))
                    ->dateTime(),
            ]);
    }
}
