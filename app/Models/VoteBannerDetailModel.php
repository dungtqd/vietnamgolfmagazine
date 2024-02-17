<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VoteBannerDetailModel extends Model
{
    protected $table = 'vote_banner_detail';

    public function language(): BelongsTo
    {
        return $this->belongsTo(LanguageModel::class, 'language_id');
    }

    public function banner(): BelongsTo
    {
        return $this->belongsTo(VoteBannerModel::class, 'banner_id');
    }

    protected $hidden = [
    ];

    protected $guarded = [];
}
