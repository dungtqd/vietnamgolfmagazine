<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdvertisementModel extends Model
{
    protected $table = 'advertisement';

    public function layout(): BelongsTo
    {
        return $this->belongsTo(LayoutModel::class, 'layout_id');
    }

    protected $hidden = [
    ];

    protected $guarded = [];
}
