<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CatalogProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'external_id',
        'catalog_category_id',
        'name',
        'slug',
        'short_description',
        'description',
        'image_url',
        'brand',
        'gtin',
        'lowest_price',
        'currency',
        'active_shop_count',
        'is_available',
        'is_active',
        'source_updated_at',
        'last_synced_at',
    ];

    protected function casts(): array
    {
        return [
            'lowest_price' => 'decimal:2',
            'active_shop_count' => 'integer',
            'is_available' => 'boolean',
            'is_active' => 'boolean',
            'source_updated_at' => 'immutable_datetime',
            'last_synced_at' => 'immutable_datetime',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(CatalogCategory::class, 'catalog_category_id');
    }

    public function variants(): HasMany
    {
        return $this->hasMany(CatalogProductVariant::class);
    }
}
