<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_name',
        'phone',
        'email',
        'location',
        'project_address',
        'lead_source',
        'project_type',
        'budget_range',
        'expected_start_date',
        'sales_executive_id',
        'designer_id',
        'status',
        'lost_reason',
        'next_followup_date',
        'notes',
        'site_visit'
    ];

    public function designer()
    {
        return $this->belongsTo(User::class, 'designer_id');
    }

    public function salesExecutive()
{
    return $this->belongsTo(User::class, 'sales_executive_id');
}
    public function tasks()
{
    return $this->hasMany(Task::class);
}
public function siteVisit()
{
    return $this->hasOne(SiteVisit::class);
}

}

