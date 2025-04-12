<?php

namespace Alessandronuunes\TasksManagement\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Alessandronuunes\TasksManagement\Enums\CustomFieldType;

class TaskCustomField extends Model
{
    protected $fillable = [
        'team_id',
        'name',
        'code',
        'type',
        'options',
        'is_required',
        'sort_order',
    ];

    protected $casts = [
        'type' => CustomFieldType::class,
        'options' => 'array',
        'is_required' => 'boolean',
    ];

    public function team(): BelongsTo
    {
        return $this->belongsTo(config('tasks-management.models.team'));
    }

    public function values(): HasMany
    {
        return $this->hasMany(TaskCustomFieldValue::class);
    }
}