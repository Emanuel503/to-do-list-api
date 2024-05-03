<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SharedTask extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_task',
        'id_user',
    ];

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class, 'id_task');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
