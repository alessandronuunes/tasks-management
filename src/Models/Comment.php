<?php

declare(strict_types=1);

namespace Alessandronuunes\TasksManagement\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use SoftDeletes;
    use HasUlids;

    protected $fillable = [
        'team_id',
        'user_id',
        'commentable_id',
        'commentable_type',
        'content',
    ];

    public function team(): BelongsTo
    {
        return $this->belongsTo(config('tasks-management.models.team'));
    }

    public function commentable()
    {
        return $this->morphTo();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(config('tasks-management.models.user'));
    }
}