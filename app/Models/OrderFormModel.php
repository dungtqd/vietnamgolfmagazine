<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderFormModel extends Model
{
    protected $table = 'order_form';


    protected $hidden = [
    ];

    protected $guarded = [];
}
