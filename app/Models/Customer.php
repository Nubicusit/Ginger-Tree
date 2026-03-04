<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'customer_id',
        'name',
        'project_type',
        'contact_no',
        'email',
        'address',
        'customer_type',
        'industry',
        'website',
        'company',
        'gst_number',
        'notes',
        'payment_status',
        'project_status',
    ];
    protected static function booted()
{
    static::saving(function ($customer) {
        $customer->project_status = match ($customer->payment_status) {
            'paid'    => 'completed',
            'balance' => 'in_progress',
            default   => 'pending',
        };
    });
}
protected static function boot()
{
    parent::boot();

    static::creating(function ($customer) {

        $lastCustomer = self::latest()->first();

        $nextNumber = $lastCustomer
            ? ((int) filter_var($lastCustomer->customer_id, FILTER_SANITIZE_NUMBER_INT)) + 1
            : 1;

        $customer->customer_id = 'CUST-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    });
}
}

