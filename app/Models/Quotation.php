<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class Quotation extends Model
{
    protected $fillable = [
        'lead_id',
        'description',
        'image',
        'quantity',
        'price',
        'total',
        'status',
        'rejection_reason'
    ];
   protected $fillable = [
    'lead_id',
    'quotation_no',
    'items',
    'status'
];

protected $casts = [
    'items' => 'array'
];

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }
      public function inventoryStock()
    {
        return $this->belongsTo(InventoryStock::class, 'item');
    }
}
