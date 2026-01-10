<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Note extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'content',
        'category',
        'priority',
        'reminder_at',
        'reminder_sent',
        'telegram_chat_id',
        'is_completed',
    ];

    /**
     * Get the user that owns the note.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected $casts = [
        'reminder_at' => 'datetime',
        'reminder_sent' => 'boolean',
        'is_completed' => 'boolean',
    ];

    /**
     * Scope para notas pendientes de recordatorio
     */
    public function scopePendingReminders($query)
    {
        return $query->whereNotNull('reminder_at')
            ->where('reminder_sent', false)
            ->where('is_completed', false)
            ->where('reminder_at', '<=', now());
    }

    /**
     * Scope para filtrar por categoría
     */
    public function scopeCategory($query, $category)
    {
        return $query->when($category, fn($q) => $q->where('category', $category));
    }

    /**
     * Scope para filtrar por prioridad
     */
    public function scopePriority($query, $priority)
    {
        return $query->when($priority, fn($q) => $q->where('priority', $priority));
    }

    /**
     * Scope para filtrar por estado de completado
     */
    public function scopeCompleted($query, $completed)
    {
        return $query->when($completed !== null, fn($q) => $q->where('is_completed', $completed));
    }

    /**
     * Obtener el color de prioridad
     */
    public function getPriorityColorAttribute()
    {
        return match ($this->priority) {
            'low' => 'emerald',
            'normal' => 'sky',
            'high' => 'amber',
            'urgent' => 'rose',
            default => 'slate',
        };
    }
}
