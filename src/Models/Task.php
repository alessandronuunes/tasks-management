<?php

declare(strict_types=1);

namespace Alessandronuunes\TasksManagement\Models;

use Alessandronuunes\TasksManagement\Enums\PriorityType;
use Alessandronuunes\TasksManagement\Enums\TaskStatus;
use Alessandronuunes\TasksManagement\Enums\TaskType;
use Alessandronuunes\TasksManagement\Observers\TaskObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[ObservedBy(TaskObserver::class)]
class Task extends Model
{
    use HasUlids;
    use SoftDeletes;

    protected $fillable = [
        'type',
        'team_id',
        'user_id',
        'status',
        'priority',
        'parent_id',
        'taskable_id',
        'taskable_type',
        'name',
        'description',
        'starts_at',
        'ends_at',
    ];

    protected function casts(): array
    {
        return [
            'type' => TaskType::class,
            'priority' => PriorityType::class,
            'status' => TaskStatus::class,
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
        ];
    }

    public function team(): ?BelongsTo
    {
        if (! config('tasks-management.use_teams')) {
            return null;
        }

        return $this->belongsTo(config('tasks-management.models.team'));
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(config('tasks-management.models.user'));
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function subtasks(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function taskable()
    {
        return $this->morphTo();
    }

    public function comments(): MorphMany
    {
        return $this->morphMany(config('tasks-management.models.comment'), 'commentable');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(config('tasks-management.models.user'))->withPivot('read_at');
    }
}
