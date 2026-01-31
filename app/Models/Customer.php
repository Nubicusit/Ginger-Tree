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

}

