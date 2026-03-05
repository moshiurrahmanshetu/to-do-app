<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;


class Task extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'priority',
        'category',
        'status',
        'due_date',
        'reminder_time',
        'notification_sent',
    ];

    protected $casts = [
        'due_date' => 'date',
        'reminder_time' => 'datetime',
        'notification_sent' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
 
}
