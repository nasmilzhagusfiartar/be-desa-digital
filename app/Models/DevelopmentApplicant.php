<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\UUID;

class DevelopmentApplicant extends Model
{
    use SoftDeletes, UUID;

    protected $fillable = [
        'development_id',
        'user_id',
        'status'
    ];

    public function development()
    {
        return $this->belongsTo(Development::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
}
