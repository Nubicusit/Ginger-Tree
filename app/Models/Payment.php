<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'project_id',
        'payment_type',
        'payment_method',
        'amount',
        'discount_type',
        'discount_value',
        'discount_amount',
        'apply_gst',
        'gst_type',
        'gst_amount',
        'total_payable',
        'transaction_id',
        'bank_name',
        'account_number',
        'ifsc_code',
        'transfer_ref',
        'screenshot_path',
        'bank_slip_path',
        'receipt_path',
        'remarks',
        'status',
    ];

    protected $casts = [
        'amount'          => 'float',
        'discount_amount' => 'float',
        'discount_value'  => 'float',
        'gst_amount'      => 'float',
        'total_payable'   => 'float',
        'apply_gst'       => 'boolean',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
