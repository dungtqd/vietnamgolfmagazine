<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContactModel extends Model
{
    protected $table = 'contact';


    protected $hidden = [
    ];

    protected $guarded = [];
}
