<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Activity extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'category',
        'priority',
        'assigned_to',
        'created_by',
        'due_date',
        'current_status',
    ];

    protected function casts(): array
    {
        return [
            'due_date' => 'date',
        ];
    }

    public static array $categories = [
        'SMS Monitoring',
        'Server Monitoring',
        'Database Validation',
        'API Health Check',
        'Incident Response',
        'Network Monitoring',
        'Application Support',
        'Security Audit',
        'Backup Verification',
        'Performance Review',
    ];

    public static array $priorities = ['low', 'medium', 'high', 'critical'];
    public static array $statuses = ['pending', 'in_progress', 'done'];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function updates(): HasMany
    {
        return $this->hasMany(ActivityUpdate::class);
    }

    public function latestUpdate(): HasMany
    {
        return $this->hasMany(ActivityUpdate::class)->latest()->limit(1);
    }

    public function getPriorityColorAttribute(): string
    {
        return match ($this->priority) {
            'critical' => 'red',
            'high'     => 'orange',
            'medium'   => 'yellow',
            'low'      => 'green',
            default    => 'gray',
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->current_status) {
            'done'        => 'green',
            'in_progress' => 'blue',
            'pending'     => 'yellow',
            default       => 'gray',
        };
    }

    public function isOverdue(): bool
    {
        return $this->due_date
            && $this->due_date->isPast()
            && $this->current_status !== 'done';
    }
}
