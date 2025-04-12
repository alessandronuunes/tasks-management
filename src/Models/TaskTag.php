<?php

declare(strict_types=1);

namespace Alessandronuunes\TasksManagement\Models;

use Alessandronuunes\TasksManagement\Observers\TaskTagObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[ObservedBy(TaskTagObserver::class)]
class TaskTag extends Model
{
    protected $fillable = [
        'team_id',
        'name',
        'color',
    ];

    protected static function booted(): void
    {
        if (config('tasks-management.use_teams')) {
            static::addGlobalScope('team', function ($query) {
                $query->where('team_id', auth()->user()->current_team_id);
            });
        }
    }

    public function team(): ?BelongsTo
    {
        if (! config('tasks-management.use_teams')) {
            return null;
        }

        return $this->belongsTo(config('tasks-management.models.team'));
    }

    public function tasks(): BelongsToMany
    {
        return $this->belongsToMany(Task::class, 'task_tag', 'task_tag_id', 'task_id');
    }
}