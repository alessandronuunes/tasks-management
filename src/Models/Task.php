<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\PriorityType;
use App\Enums\TaskStatus;
use App\Enums\TaskType;
use App\Observers\TaskObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[ObservedBy(TaskObserver::class)]
class Task extends Model
{
    use HasFactory;
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
            'type'      => TaskType::class,
            'priority'  => PriorityType::class,
            'status'    => TaskStatus::class,
            'starts_at' => 'datetime',
            'ends_at'   => 'datetime',
        ];
    }

    /**
     * Obtém o time associado à tarefa.
     */
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    /**
     * Obtém o usuário que criou  a tarefa.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Obtém o status da tarefa.
     */
    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class);
    }

    /**
     * Obtém a tarefa pai, se houver.
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    /**
     * Obtém as subtarefas desta tarefa.
     */
    public function subtasks(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    /**
     * Obtém o modelo taskable.
     */
    public function taskable()
    {
        return $this->morphTo();
    }

    /**
     * Obtém os comentários associados à tarefa.
     */
    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    /**
     * Obtém as atribuições de usuários para esta tarefa.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->withPivot('read_at');
    }
}
