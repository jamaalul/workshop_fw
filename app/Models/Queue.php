<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Queue extends Model
{
    protected $fillable = [
        'name',
        'number',
        'status',
        'called_at',
    ];

    protected function casts(): array
    {
        return [
            'called_at' => 'datetime',
            'number'    => 'integer',
        ];
    }

    // ── Scopes ───────────────────────────────────────────────────────────────

    public function scopeWaiting($query)
    {
        return $query->where('status', 'waiting')->orderBy('number');
    }

    public function scopeCalled($query)
    {
        return $query->where('status', 'called');
    }

    public function scopeLate($query)
    {
        return $query->where('status', 'late')->orderBy('number');
    }

    // ── Helpers ──────────────────────────────────────────────────────────────

    /**
     * Return the next sequential queue number.
     */
    public static function nextNumber(): int
    {
        return (static::max('number') ?? 0) + 1;
    }
}
