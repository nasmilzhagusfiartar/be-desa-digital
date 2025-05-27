<?php

namespace App\Models;

use App\Traits\UUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SocialAssistanceRecipient extends Model
{
    use HasFactory, SoftDeletes, UUID;

    protected $fillable = [
        'social_assistance_id',
        'head_of_family_id',
        'amount',
        'reason',
        'bank',
        'account_number',
        'proof',
        'status',
    ];

    /**
     * Scope untuk pencarian global.
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('reason', 'like', '%' . $search . '%')
              ->orWhere('bank', 'like', '%' . $search . '%')
              ->orWhere('account_number', 'like', '%' . $search . '%')
              ->orWhere('status', 'like', '%' . $search . '%')
              ->orWhereHas('headOfFamily', function ($q) use ($search) {
                  $q->whereHas('user', function ($q) use ($search) {
                      $q->where('name', 'like', '%' . $search . '%')
                        ->orWhere('email', 'like', '%' . $search . '%');
                  });
              });
        });
    }

    /**
     * Relasi ke model SocialAssistance.
     */
    public function socialAssistance()
    {
        return $this->belongsTo(SocialAssistance::class);
    }

    /**
     * Relasi ke model HeadOfFamily.
     */
    public function headOfFamily()
    {
        return $this->belongsTo(HeadOfFamily::class);
    }
}
