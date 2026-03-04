<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use App\Models\Department;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_unique_id',
        'name',
        'email',
        'password',
        'visible_password',
        'contact_no',
        'role',
        'status',
        'department_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
public function department()
{
    return $this->belongsTo(Department::class);
}
// app/Models/User.php
public function employee()
{
    return $this->hasOne(Employee::class, 'user_id');
}
protected static function boot()
{
    parent::boot();

    static::creating(function ($user) {

        $department = Department::find($user->department_id);

        $deptPrefix = $department
            ? strtoupper(substr($department->name, 0, 2))
            : 'NA';

        $year = date('y');

        do {
            $random = mt_rand(1000, 9999);
            $uniqueId = $deptPrefix . '-' . $year . '-' . $random;
        } while (User::where('user_unique_id', $uniqueId)->exists());

        $user->user_unique_id = $uniqueId;
    });
}

}
