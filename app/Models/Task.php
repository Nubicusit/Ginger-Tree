<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'lead_id',
        'sales_executive_id',
        'title',
        'followup_date',
    ];
     public function lead()
    {
        return $this->belongsTo(Lead::class);
    }

    // public function salesExecutive()
    // {
    //     return $this->belongsTo(SalesExecutive::class);
    // }
}
