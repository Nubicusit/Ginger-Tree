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
            // Only auto-generate if not already set
            if (!empty($customer->customer_id)) return;

            $last = self::orderBy('id', 'desc')->first();

            $nextNumber = 1;
            if ($last && $last->customer_id) {
                // Extract digits from e.g. "CUST-0042" → 42
                preg_match('/(\d+)$/', $last->customer_id, $matches);
                $nextNumber = isset($matches[1]) ? (int)$matches[1] + 1 : 1;
            }

            $customer->customer_id = 'CUST-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
        });
    }
}

