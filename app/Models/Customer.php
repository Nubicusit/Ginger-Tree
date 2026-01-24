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
        'company',
        'payment_status',
        'gst_number'
    ];
}

