<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AiModel extends Model
{
    protected $guarded = [];
    protected $casts = [
        'advantages' => 'array',
        'disadvantages' => 'array',
    ];

    public function articles(): HasMany
    {
        return $this->hasMany(Article::class);
    }

}
