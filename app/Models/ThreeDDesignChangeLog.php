<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ThreeDDesignChangeLog extends Model
{
    protected $table = 'three_d_design_change_log';

    public $timestamps = false; 

    protected $fillable = [
        'design_id',
        'changed_by',
        'changed_at',
        'change_note',
        'field_changed',
        'old_value',
        'new_value',
        'cost_impact',
    ];

    protected $casts = [
        'changed_at'  => 'datetime',
        'cost_impact' => 'boolean',
    ];

    public function design()
    {
        return $this->belongsTo(ThreeDDesign::class, 'design_id');
    }

    public function changedBy()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}
