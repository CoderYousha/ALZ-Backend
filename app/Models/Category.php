<?php

namespace App\Models;

use App\Http\Services\File\FileManagementServicesClass;
use App\Http\Traits\DefaultOrder;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use DefaultOrder;

    protected $fillable = [
        'name_en',
        'name_ar',
        'description_en',
        'description_ar',
        'image',
    ];

    protected static function booted()
    {
        static::deleting(function (Category $category) {
            FileManagementServicesClass::deleteFile($category->image);
        });
    }

    public function getImageUrlAttribute()
    {
        return FileManagementServicesClass::getFileAttribute($this->image);
    }
}
