<?php

namespace Alessandronuunes\TasksManagement\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Alessandronuunes\TasksManagement\Enums\CustomFieldType;
use Alessandronuunes\TasksManagement\Casts\OptionsArrayCast;
use Alessandronuunes\TasksManagement\Models\TaskCustomFieldValue;

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
        'placeholder',
        'help_text',
        'hint',
    ];

    protected $casts = [
        'type' => CustomFieldType::class,
        'options' => OptionsArrayCast::class,
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