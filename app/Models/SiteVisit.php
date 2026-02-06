<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteVisit extends Model
{
    protected $fillable = [
    'lead_id',
    'visit_datetime',
    'assigned_staff',
    'site_condition_notes',
    'measurement_files',
    'space_details',
    'materials_finishes',
    'style_preferences',
    'appliances_accessories',
    'brand_preferences',
    'finish_preferences',
    'budget_sensitivity',
    'initial_cost_estimate',
    'approval_status'
];

protected $casts = [
    'measurement_files' => 'array',
];

  public function lead()
    {
        return $this->belongsTo(Lead::class);
    }
}
