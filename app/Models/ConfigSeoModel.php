<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConfigSeoModel extends Model
{
    protected $table = 'config_seo';

    public function language(): BelongsTo
    {
        return $this->belongsTo(LanguageModel::class, 'language_id');
    }

    protected $hidden = [
    ];

    protected $guarded = [];
}
