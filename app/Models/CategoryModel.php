<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CategoryModel extends Model
{
    protected $table = 'category';

    public function language(): BelongsTo
    {
        return $this->belongsTo(LanguageModel::class, 'language_id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(__CLASS__, 'parent_id');
    }

    protected $hidden = [
    ];

    protected $guarded = [];
}
