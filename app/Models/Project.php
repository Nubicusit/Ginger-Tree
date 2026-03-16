<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
        'name',
        'client',
        'client_gst',
        'company_gst',
        'sales_rep',
        'sales_phone',
        'scope',
        'timeline',
        'start_date',
        'end_date',
        'total_value',
        'gst_rate',
        'po_number',
        'payment_terms',
        'notes',
        'status',
        'lead_id',
    ];

    protected $casts = [
        'start_date'  => 'date',
        'end_date'    => 'date',
        'total_value' => 'float',
        'gst_rate'    => 'float',
    ];

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }
}
