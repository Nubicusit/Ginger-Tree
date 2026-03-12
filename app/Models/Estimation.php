<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Estimation extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'lead_id',
        'estimation_no',
        'client_name',
        'client_email',
        'client_phone',
        'site_address',
        'title',
        'status',
        'subtotal',
        'discount',
        'gst_pct',
        'gst_amount',
        'grand_total',
        'created_by',
        'deleted_at'
    ];
    // app/Models/Estimation.php

protected static function boot()
{
    parent::boot();

    static::updated(function ($estimation) {
        $quotation = \App\Models\Quotation::where('lead_id', $estimation->lead_id)->first();
        if (!$quotation) return;

        // Sync totals back to quotation
        $quotation->update([
            'subtotal'    => $estimation->subtotal,
            'gst_amount'  => $estimation->gst_amount,
            'grand_total' => $estimation->grand_total,
            'status'      => $estimation->status, // if quotation has status column
        ]);
    });
}
}


