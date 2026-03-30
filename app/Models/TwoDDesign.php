<?php
// app/Models/TwoDDesign.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\AsArrayObject;

class TwoDDesign extends Model
{
    protected $table = 'two_d_designs';

    protected $fillable = [
        'project_id', 'drawing_type', 'drawing_status',
        'assigned_designer', 'project_manager',
        'drawing_start_date', 'submission_date', 'approval_date',
        'pm_internal_review', 'pm_review_date', 'pm_review_notes',
        'client_approval', 'client_feedback',
        'revision_count', 'revision_notes',
        'internal_approval', 'design_freeze', 'freeze_date',
        'forwarded_to_production', 'forwarded_date',
        'additional_cost_flag', 'change_after_freeze_note',
        'drawing_files', 'created_by', 'updated_by',
    ];

    protected $casts = [
        'drawing_start_date'      => 'date',
        'submission_date'         => 'date',
        'approval_date'           => 'date',
        'pm_review_date'          => 'date',
        'freeze_date'             => 'date',
        'forwarded_date'          => 'date',
        'pm_internal_review'      => 'boolean',
        'client_approval'         => 'boolean',
        'internal_approval'       => 'boolean',
        'design_freeze'           => 'boolean',
        'forwarded_to_production' => 'boolean',
        'additional_cost_flag'    => 'boolean',
        'drawing_files'           => 'array',
    ];

    public function project()   { return $this->belongsTo(Project::class); }
    public function createdBy() { return $this->belongsTo(User::class, 'created_by'); }
    public function updatedBy() { return $this->belongsTo(User::class, 'updated_by'); }
}
