<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\UUID;

class ProfileImage extends Model
{
    use SoftDeletes, UUID;

    protected $fillable = [
        'profile_id',
        'image',
    ];

    public function profile()
    {
        return $this->belongsTo(Profile::class);
    }
}
