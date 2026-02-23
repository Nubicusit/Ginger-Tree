<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $table = 'services';

    protected $fillable = [
        'service_name',
        'category_service',
        'price',
        'gst_percentage',
        'service_tax',
    ];

    /**
     * Accessor: GST amount
     */
    // public function getGstAmountAttribute(): float
    // {
    //     return ($this->price * ($this->gst_percentage ?? 0)) / 100;
    // }

    /**
     * Accessor: Total price (price + GST + service tax)
     */
    // public function getTotalPriceAttribute(): float
    // {
    //     return $this->price
    //         + $this->gst_amount
    //         + ($this->service_tax ?? 0);
    // }
}
