<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LayoutModel extends Model
{
    protected $table = 'layout';

    protected $hidden = [
    ];

    protected $guarded = [];
}
