<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Leave extends Model
{
    protected $fillable = [
        'employee_id', 'leave_type', 'from_date', 'to_date',
        'days', 'reason', 'status', 'note'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}