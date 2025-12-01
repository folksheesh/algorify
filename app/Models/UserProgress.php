<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProgress extends Model
{
    use HasFactory;

    protected $table = 'user_progress';

    protected $fillable = [
        'user_id',
        'kursus_id',
        'item_type',
        'item_id',
        'status',
        'watch_time',
        'total_duration',
        'score',
        'passed',
        'completed_at',
    ];

    protected $casts = [
        'watch_time' => 'integer',
        'total_duration' => 'integer',
        'score' => 'decimal:2',
        'passed' => 'boolean',
        'completed_at' => 'datetime',
    ];

    // Status constants
    const STATUS_NOT_STARTED = 'not_started';
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_COMPLETED = 'completed';

    // Item type constants
    const TYPE_VIDEO = 'video';
    const TYPE_MATERI = 'materi';
    const TYPE_QUIZ = 'quiz';
    const TYPE_UJIAN = 'ujian';

    /**
     * Relationship to User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship to Kursus
     */
    public function kursus()
    {
        return $this->belongsTo(Kursus::class);
    }

    /**
     * Get the actual item based on item_type
     */
    public function getItemAttribute()
    {
        return match($this->item_type) {
            self::TYPE_VIDEO => Video::find($this->item_id),
            self::TYPE_MATERI => Materi::find($this->item_id),
            self::TYPE_QUIZ, self::TYPE_UJIAN => Ujian::find($this->item_id),
            default => null
        };
    }

    /**
     * Check if this item is completed
     */
    public function isCompleted(): bool
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    /**
     * Mark as completed
     */
    public function markAsCompleted(): self
    {
        $this->status = self::STATUS_COMPLETED;
        $this->completed_at = now();
        $this->save();
        
        return $this;
    }

    /**
     * Scope untuk filter by user
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope untuk filter by kursus
     */
    public function scopeForKursus($query, $kursusId)
    {
        return $query->where('kursus_id', $kursusId);
    }

    /**
     * Scope untuk filter by status completed
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }

    /**
     * Scope untuk filter by item type
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('item_type', $type);
    }
}
