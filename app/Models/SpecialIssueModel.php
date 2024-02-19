<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SpecialIssueModel extends Model
{
    protected $table = 'special_issue';

    public function language(): BelongsTo
    {
        return $this->belongsTo(LanguageModel::class, 'language_id');
    }


    protected $hidden = [
    ];

    protected $guarded = [];
}
