<?php

declare(strict_types=1);

namespace Alessandronuunes\TasksManagement\Filament\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Alessandronuunes\TasksManagement\Models\Task;
use Alessandronuunes\TasksManagement\Traits\AuthorizedUsersTrait;

class LatestTasksWidget extends BaseWidget
{

    use AuthorizedUsersTrait;
    protected static ?int $sort = 2;

    protected int | string | array $columnSpan = 'full';
    
    public static function canView(): bool
    {
        return self::isAuthorized();
    }
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
                    ->label(__('tasks-management::fields.common.name'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->label(__('tasks-management::fields.tasks.status'))
                    ->badge(),
                Tables\Columns\TextColumn::make('priority')
                    ->label(__('tasks-management::fields.tasks.priority'))
                    ->badge(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('tasks-management::fields.common.created_at'))
                    ->dateTime(),
            ]);
    }
}
