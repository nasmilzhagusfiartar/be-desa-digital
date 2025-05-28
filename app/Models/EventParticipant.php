<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\UUID;

class EventParticipant extends Model
{
    use HasFactory, SoftDeletes, UUID;

    protected $fillable = [
        'event_id',
        'head_of_family_id',
        'quantity',
        'total_price',
        'payment_status',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'total_price' => 'decimal:2',
    ];

    public function scopeSearch($query, $search)
    {
        return $query->whereHas('headOfFamily', function ($query) use ($search) {
            $query->whereHas('user', function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
            });
        });
    }
    

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function headOfFamily()
    {
        return $this->belongsTo(HeadOfFamily::class);
    }
}
