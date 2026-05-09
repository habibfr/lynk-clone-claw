<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Link extends Model
{
    protected $fillable = [
        'profile_id',
        'title',
        'url',
        'icon',
        'order',
        'is_active',
        'clicks',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'clicks' => 'integer',
        'order' => 'integer',
    ];

    public function profile(): BelongsTo
    {
        return $this->belongsTo(Profile::class);
    }

    public function clickRecords(): HasMany
    {
        return $this->hasMany(Click::class);
    }
}
