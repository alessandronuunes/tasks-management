<?php

declare(strict_types=1);

namespace Alessandronuunes\TasksManagement\Filament\Widgets;

use Alessandronuunes\TasksManagement\Models\Task;
use Alessandronuunes\TasksManagement\Traits\AuthorizedUsersTrait;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class TasksOverviewWidget extends BaseWidget
{
    use AuthorizedUsersTrait;

    public static function canView(): bool
    {
        return self::isAuthorized();
    }
    protected function getStats(): array
    {
        $query = Task::query();

        if (config('tasks-management.use_teams')) {
            $query->where('team_id', auth()->user()->current_team_id);
        }

        return [
            Stat::make(__('tasks-management::widgets.total_tasks'), $query->count())
                ->description(__('tasks-management::widgets.all_tasks'))
                ->descriptionIcon('heroicon-m-clipboard-document-list')
                ->color('info'),

            Stat::make(__('tasks-management::widgets.pending_tasks'), $query->where('status', 'pending')->count())
                ->description(__('tasks-management::widgets.tasks_pending'))
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),

            Stat::make(__('tasks-management::widgets.completed_tasks'), $query->where('status', 'completed')->count())
                ->description(__('tasks-management::widgets.tasks_completed'))
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),
        ];
    }
}
