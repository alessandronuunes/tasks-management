<?php

declare(strict_types=1);

namespace Alessandronuunes\TasksManagement\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class TaskAttachment extends Model
{
    protected $fillable = [
        'task_id',
        'name',
        'file_path',
        'mime_type',
        'size',
        'uploaded_by',
    ];

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(config('tasks-management.models.user'), 'uploaded_by');
    }

    public function getUrl(): string
    {
        return Storage::url($this->file_path);
    }

    protected static function booted(): void
    {
        static::deleting(function (TaskAttachment $attachment) {
            Storage::delete($attachment->file_path);
        });
    }
}