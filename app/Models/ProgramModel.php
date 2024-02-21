<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProgramModel extends Model
{
    protected $table = 'program';

    public function language(): BelongsTo
    {
        return $this->belongsTo(LanguageModel::class, 'language_id');
    }
    public function parent(): BelongsTo
    {
        return $this->belongsTo(__CLASS__, 'parent_id');
    }
    public function originalProgram(): BelongsTo
    {
        return $this->belongsTo(__CLASS__, 'original_program');
    }

    protected $hidden = [
    ];

    protected $guarded = [];
}
