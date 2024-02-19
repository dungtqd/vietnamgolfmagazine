<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ArticleModel extends Model
{
    protected $table = 'article';

    public function language(): BelongsTo
    {
        return $this->belongsTo(LanguageModel::class, 'language_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(CategoryModel::class, 'category_id');
    }

    protected $hidden = [
    ];

    protected $guarded = [];
}
