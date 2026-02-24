<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'title',
        'type',
        'category',
        'amount',
        'date',
        'payment_method',
        'reference',
        'note',
    ];

    protected $casts = [
        'date'   => 'date',
        'amount' => 'decimal:2',
    ];

    // ── Scopes ──────────────────────────────────────────
    public function scopeIncome($query)
    {
        return $query->where('type', 'income');
    }

    public function scopeExpense($query)
    {
        return $query->where('type', 'expense');
    }
}