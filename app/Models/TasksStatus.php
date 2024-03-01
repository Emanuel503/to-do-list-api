<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TasksStatus extends Model
{
    use HasFactory;

    protected $fillable = [
        'status', 
        'description',
    ];
}
