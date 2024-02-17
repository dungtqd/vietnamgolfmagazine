<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ModuleDetailModel extends Model
{
    protected $table = 'module_detail';

    public function language(): BelongsTo
    {
        return $this->belongsTo(LanguageModel::class, 'language_id');
    }

    public function module(): BelongsTo
    {
        return $this->belongsTo(ModuleModel::class, 'module_id');
    }

    public function banner(): BelongsTo
    {
        return $this->belongsTo(VoteBannerModel::class, 'banner_id');
    }

    public function layout(): BelongsTo
    {
        return $this->belongsTo(LayoutModel::class, 'layout_id');
    }

    protected $hidden = [
    ];

    protected $guarded = [];
}
