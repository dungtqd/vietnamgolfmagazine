<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductModel extends Model
{
    protected $table = 'product';

    public function language(): BelongsTo
    {
        return $this->belongsTo(LanguageModel::class, 'language_id');
    }
    public function province(): BelongsTo
    {
        return $this->belongsTo(ProvinceModel::class, 'province_id');
    }
    public function zone(): BelongsTo
    {
        return $this->belongsTo(ZoneModel::class, 'zone_id');
    }

    protected $hidden = [
    ];

    protected $guarded = [];
}
