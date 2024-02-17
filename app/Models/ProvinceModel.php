<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProvinceModel extends Model
{
    protected $table = 'province';


    protected $hidden = [
    ];

    protected $guarded = [];
}
