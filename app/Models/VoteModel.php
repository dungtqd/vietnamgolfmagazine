<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VoteModel extends Model
{
    protected $table = 'vote';

    public function program(): BelongsTo
    {
        return $this->belongsTo(ProgramModel::class, 'program_id');
    }
    public function product(): BelongsTo
    {
        return $this->belongsTo(ProductModel::class, 'product_id');
    }
    public function language(): BelongsTo
    {
        return $this->belongsTo(LanguageModel::class, 'language_id');
    }

    protected $hidden = [
    ];

    protected $guarded = [];
}
