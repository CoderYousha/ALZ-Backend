<?php

namespace App\Models;

use App\Http\Services\File\FileManagementServicesClass;
use App\Http\Traits\DefaultOrder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductImage extends Model
{
    use DefaultOrder;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'product_id',
        'path',
    ];
    
    protected $hidden = [
        'path',
    ];

    protected $appends = ['url'];

    public function product(): BelongsTo {
        return $this->belongsTo(Product::class);
    }

    public function getUrlAttribute()
    {
        return FileManagementServicesClass::getFileAttribute($this->path);
    }

}
