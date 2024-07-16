<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ContentType extends Model
{
    protected $guarded = [];
    public function writingSteps(): HasMany
    {
        return $this->hasMany(WritingStep::class);
    }

    public function articles(): HasMany
    {
        return $this->hasMany(Article::class);
    }
}
