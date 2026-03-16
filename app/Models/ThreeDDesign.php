<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ThreeDDesign extends Model
{
    use SoftDeletes;

    protected $table = 'three_d_design_stage';

    protected $fillable = [
        'project_id',
        'design_start_date',
        'assigned_designer',
        'client_requirements_freeze',
        'design_status',
        'client_feedback',
        'revision_count',
        'final_3d_approval',
        'approval_date',
        'design_freeze_confirmation',
        'additional_cost_flag',
        'change_after_freeze_note',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'design_start_date'           => 'date',
        'client_requirements_freeze'  => 'date',
        'approval_date'               => 'date',
        'final_3d_approval'           => 'boolean',
        'design_freeze_confirmation'  => 'boolean',
        'additional_cost_flag'        => 'boolean',
        'revision_count'              => 'integer',
    ];

    // ── Relationships ────────────────────────────

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function changeLogs()
    {
        return $this->hasMany(ThreeDDesignChangeLog::class, 'design_id');
    }

    // ── Accessors ────────────────────────────────

    /** Returns true only when both approval and freeze are confirmed */
    public function getCanProceedAttribute(): bool
    {
        return $this->final_3d_approval && $this->design_freeze_confirmation;
    }
    public function scopeInProgress($query) { return $query->where('design_status', 'In Progress'); }
    public function scopeSubmitted($query)  { return $query->where('design_status', 'Submitted'); }
    public function scopeRevised($query)    { return $query->where('design_status', 'Revised'); }
    public function scopeApproved($query)   { return $query->where('final_3d_approval', true); }
    public function scopeFrozen($query)     { return $query->where('design_freeze_confirmation', true); }
    public function scopeReadyToProceed($query) {
        return $query->where('final_3d_approval', true)
                     ->where('design_freeze_confirmation', true);
    }
}
