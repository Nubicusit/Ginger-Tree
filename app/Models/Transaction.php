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
        'employee_id',
        'deducted_month',
        'status',
    ];

    protected $casts = [
        'date'   => 'date',
        'amount' => 'decimal:2',
    ];

    // ── Scopes ───────────────────────────────────────────────────────────

    public function scopeIncome($query)
    {
        return $query->where('type', 'income');
    }

    public function scopeExpense($query)
    {
        return $query->where('type', 'expense');
    }

    public function scopeAdvance($query)
    {
        return $query->where('type', 'advance');
    }

    public function scopePendingAdvances($query, $employeeId, $month)
    {
        return $query->where('type', 'advance')
                     ->where('employee_id', $employeeId)
                     ->where('status', 'pending')
                     ->where('deducted_month', $month);
    }

    // ── Relationship ─────────────────────────────────────────────────────

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
