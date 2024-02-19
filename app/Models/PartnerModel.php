<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PartnerModel extends Model
{
    protected $table = 'partner';

    protected $hidden = [
    ];

    protected $guarded = [];
}
