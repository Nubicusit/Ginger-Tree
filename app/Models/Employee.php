<?php
// app/Models/Employee.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'employee_id',
        'first_name',
        'last_name', 
        'email',
        'phone',
        'department_id',
        'designation_id',
        'joining_date',
        'salary',
        'status',
        'profile_image',
        'address',
        'emergency_contact',
        'pan_number',
        'bank_account',
        'ifsc_code',
        'blood_group'
    ];

    protected $casts = [
        'joining_date' => 'date',
        'salary' => 'decimal:2',
    ];

    // Relationships
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function designation()
    {
        return $this->belongsTo(Designation::class);
    }

    public function attendance()
    {
        return $this->hasMany(Attendance::class);
    }

    public function payroll()
    {
        return $this->hasMany(Payroll::class);
    }

    public function leaveRequests()
    {
        return $this->hasMany(LeaveRequest::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeRecent($query, $days = 30)
    {
        return $query->where('joining_date', '>=', now()->subDays($days));
    }

    // Accessors
    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function getShortNameAttribute()
    {
        return str($this->first_name)->substr(0, 1) . '. ' . $this->last_name;
    }
     public function user()
    {
        return $this->belongsTo(User::class);
    }
    
public function hasEmployee()
{
    return $this->employee() ? true : false;
}
   
// app/Models/User.php - ADD THIS
public function employee()
{
    return $this->hasOne(Employee::class, 'user_id');
}

}
