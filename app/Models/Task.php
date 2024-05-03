<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id_register',
        'id_task_status',
        'title',
        'description',
        'category',
        'color',
        'start_date',
        'end_date',
        'deleted_at',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id_register');
    }

    public function shared(): HasMany
    {
        return $this->hasMany(SharedTask::class, 'id_task');
    }
}
