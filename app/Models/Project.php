<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'lead_id',
        'project_code',
        'status',
        'expected_start_date',
        'expected_end_date',
        'materials'
    ];

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }
    public function materials()
    {
        return $this->hasMany(InventoryStock::class);
    }

    public function getTotalMaterialCostAttribute()
    {
        return $this->materials->sum('total');
    }

    public function isCompleted()
    {
        return $this->status === 'Completed';
    }
}
