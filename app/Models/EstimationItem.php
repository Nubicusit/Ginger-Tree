<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EstimationItem extends Model
{
    protected $fillable = [
        'estimation_id',
        'item_id',
        'name',
        'section',
        'description',
        'category',
        'unit',
        'qty',
        'unit_price',
        'amount',
        'sort_order',
        'length',
        'breadth',
        'area',
        'gst',
        'gst_amount',
        'service_id',
        'service_tax',
    ];

    public function estimation()
    {
        return $this->belongsTo(Estimation::class);
    }

    public function inventoryItem()
    {
        return $this->belongsTo(InventoryStock::class, 'item_id');
    }

    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }
}
