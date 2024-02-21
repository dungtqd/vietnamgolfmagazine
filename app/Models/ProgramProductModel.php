<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProgramProductModel extends Model
{
    protected $table = 'program_product';

    public function program(): BelongsTo
    {
        return $this->belongsTo(ProgramModel::class, 'program_id');
    }
    public function product(): BelongsTo
    {
        return $this->belongsTo(ProductModel::class, 'product_id');
    }

    public function programCode(): BelongsTo
    {
        return $this->belongsTo(ProgramModel::class, 'program_code');
    }
    public function productCode(): BelongsTo
    {
        return $this->belongsTo(ProductModel::class, 'product_code');
    }

    protected $hidden = [
    ];

    protected $guarded = [];
}
