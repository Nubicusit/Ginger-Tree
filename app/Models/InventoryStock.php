<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryStock extends Model
{
     protected $fillable = [
        'item_name',
        'category',
        'unit',
        'price',
        'gst_percentage',
        'quantity',
        'low_stock_alert'
    ];
}
