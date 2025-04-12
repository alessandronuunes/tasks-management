<?php

namespace Alessandronuunes\TasksManagement\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaskCustomFieldValue extends Model
{
    protected $fillable = [
        'task_id',
        'task_custom_field_id',
        'value',
    ];

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    public function field(): BelongsTo
    {
        return $this->belongsTo(TaskCustomField::class, 'task_custom_field_id');
    }
}