<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Article extends Model
{
    protected $guarded = [];

    protected $casts = [
        'keywords' => 'array',
        'english_texts' => 'array',
    ];

    public function aiModel(): BelongsTo
    {
        return $this->belongsTo(AiModel::class);
    }

    public function requests(): MorphMany
    {
        return $this->morphMany(RequestLog::class, 'loggable');
    }
}
