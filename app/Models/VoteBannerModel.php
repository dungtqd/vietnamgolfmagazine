<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VoteBannerModel extends Model
{
    protected $table = 'vote_banner';

    public function language(): BelongsTo
    {
        return $this->belongsTo(LanguageModel::class, 'language_id');
    }

    protected $hidden = [
    ];

    protected $guarded = [];
}
