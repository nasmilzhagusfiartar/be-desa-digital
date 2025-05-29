<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\UUID;

class Profile extends Model
{
    use SoftDeletes, UUID;

    protected $fillable = [
        'thumbnail',
        'name',
        'about',
        'headman',
        'people',
        'agriculture_area',
        'total_area',
    ];

    protected $casts = [
        'agriculture_area' => 'decimal:2',
        'total_area' => 'decimal:2',
    ];

    public function profileImages()
    {
        return $this->hasMany(ProfileImage::class);
    }
}
