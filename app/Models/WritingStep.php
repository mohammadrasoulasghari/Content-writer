<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WritingStep extends Model
{
    protected $guarded = [];
    protected $casts = [
        'placeholders' => 'array',
    ];

    public function contentType(): BelongsTo
    {
        return $this->belongsTo(ContentType::class);
    }
}
