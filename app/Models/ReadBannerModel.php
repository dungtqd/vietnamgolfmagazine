<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReadBannerModel extends Model
{
    protected $table = 'read_banner';

    public function layout(): BelongsTo
    {
        return $this->belongsTo(LayoutModel::class, 'layout_id');
    }

    protected $hidden = [
    ];

    protected $guarded = [];
}
