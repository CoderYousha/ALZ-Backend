<?php

namespace App\Models;

use App\Http\Traits\DefaultOrder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use DefaultOrder;

    protected $fillable = [
        'category_id',
        'name_en',
        'name_ar',
        'price',
        'description_en',
        'description_ar',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class);
    }
}
