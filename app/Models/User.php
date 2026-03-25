<?php

namespace App\Models;

use App\Http\Services\File\FileManagementServicesClass;
use App\Http\Traits\DefaultOrder;
use App\Http\Traits\Sortable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, DefaultOrder, Sortable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'email',
        'name',
        'password',
        'phone_code',
        'phone',
        'account_role',
        'login_type',
        'login_service_id',
        'language',
        'image',
        'is_active',
        'verified_at',
    ];

    public $mySearchableFields = [
        'email',
        'phone',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'verified_at' => 'datetime',
        'password' => 'hashed',
    ];


    protected static function booted()
    {
        static::deleting(function (User $user) {
            FileManagementServicesClass::deleteFile($user->image);
        });
    }

    public function fcmToken()
    {
        return $this->hasOne(FcmToken::class);
    }

    public function scopeActive($query, $active = true)
    {
        return $query->where('is_active', $active);
    }

    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function getImageUrlAttribute()
    {
        return FileManagementServicesClass::getFileAttribute($this->image);
    }

}
