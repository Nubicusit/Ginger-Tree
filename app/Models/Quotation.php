<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quotation extends Model
{
    protected $fillable = [
        'lead_id',
        'description',
        'image',
        'quantity',
        'price',
        'total',
        'status'
    ];

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }
}
