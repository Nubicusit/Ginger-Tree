<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesExecutive extends Model
{
    protected $fillable = [
        'name',
        'contact_no',
        'email',
        'address',
    ];
}
