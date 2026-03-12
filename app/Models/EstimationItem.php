<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EstimationItem extends Model
{
    protected $fillable = [
        'estimation_id', 'section', 'description', 'category','item_id','name',
        'unit', 'qty', 'unit_price','gst', 'amount', 'sort_order','length', 'breadth', 'area', 'gst', 'gst_amount'
    ];
}
