<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $table = 'attendance';

    protected $fillable = [
        'employee_id',
        'date',
        'status',
        'check_in',
        'check_out',
        'late_minutes',
        'ot_hours',        // ✅ Added
        'notes',
        'leave_type',
        'leave_status',
    ];

    protected $casts = [
        'date'          => 'date',
        'check_in'      => 'datetime:H:i',
        'check_out'     => 'datetime:H:i',
        'late_minutes'  => 'integer',
        'ot_hours'      => 'decimal:2',    // ✅ Added
    ];

    // ✅ MISSING RELATIONSHIP - ADD THIS
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function scopeToday($query)
    {
        return $query->whereDate('date', today());
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('date', now()->month)
                     ->whereYear('date', now()->year);
    }
}
