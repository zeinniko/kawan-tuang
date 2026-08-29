<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Vibe extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'icon_url',
        'image_url',
    ];

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_vibes');
    }

    protected static function booted(): void
    {
        static::saving(function ($vibe) {
            $vibe->slug = Str::slug($vibe->name);
        });
    }
}