<?php

declare(strict_types=1);

namespace Alessandronuunes\TasksManagement\Models;

use Alessandronuunes\TasksManagement\Enums\PriorityType;
use Alessandronuunes\TasksManagement\Enums\TaskStatus;
use Alessandronuunes\TasksManagement\Enums\TaskType;
use Alessandronuunes\TasksManagement\Observers\TaskObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Log;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;

#[ObservedBy(TaskObserver::class)]
class Task extends Model implements Auditable
{
    use SoftDeletes;
    use AuditableTrait;
    
    protected static function boot()
    {
        parent::boot();
        
        // Só executa a lógica de auditoria se logging estiver habilitado
        if (config('tasks-management.logging.enabled')) {
            Log::debug('Task::boot - Modelo Task inicializado', [
                'implements_auditable' => is_a(new static, Auditable::class),
                'uses_auditable_trait' => in_array(AuditableTrait::class, class_uses_recursive(static::class)),
            ]);
        }
    }

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

    protected $casts = [
        'type' => TaskType::class,
        'priority' => PriorityType::class,
        'status' => TaskStatus::class,
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
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

    /**
     * Get the comments for this task.
     */
    public function comments(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(config('tasks-management.models.comment'), 'commentable');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(config('tasks-management.models.user'))->withPivot('read_at');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(TaskTag::class, 'task_tag', 'task_id', 'task_tag_id');
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(TaskAttachment::class);
    }

    public function customFieldValues(): HasMany
    {
        return $this->hasMany(TaskCustomFieldValue::class);
    }

    public function getCustomFieldValue(string $code)
    {
        return $this->customFieldValues()
            ->whereHas('field', fn($query) => $query->where('code', $code))
            ->first()
            ?->value;
    }

    public function setCustomFieldValue(string $code, $value): void
    {
        $field = TaskCustomField::where('code', $code)->first();
        
        if (!$field) {
            return;
        }

        $this->customFieldValues()->updateOrCreate(
            ['task_custom_field_id' => $field->id],
            ['value' => $value]
        );
    }

    /**
     * Get the audit logs for this task.
     */
    public function audits(): MorphMany
    {
        $logModel = config('tasks-management.logging.options.implementation');
        $relationName = config('tasks-management.logging.options.relation_name', 'auditable');
        
        return $this->morphMany($logModel, $relationName);
    }
}
