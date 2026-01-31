<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Designer extends Model
{
    protected $fillable = [
        'designer_name',
        'designer_no',
        'designer_email',
        'designer_address',
    ];
}
