<?php

namespace App\Models;

use App\Traits\UUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, UUID, SoftDeletes, HasRoles;

    /**
     * Atribut yang bisa diisi secara massal.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * Atribut yang disembunyikan saat serialisasi.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Atribut yang harus di-cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Scope pencarian user berdasarkan nama atau email.
     */
    public function scopeSearch($query, $search)
    {
        return $query->where('name', 'like', '%' . $search . '%')
                     ->orWhere('email', 'like', '%' . $search . '%');
    }

    /**
     * Relasi user dengan satu kepala keluarga.
     */
    public function headOfFamily()
    {
        return $this->hasOne(HeadOfFamily::class);
    }

    /**
     * Relasi user dengan satu anggota keluarga.
     */
    public function familyMember()
    {
        return $this->hasOne(FamilyMember::class);
    }

    /**
     * Relasi user dengan banyak pemohon pembangunan.
     */
    public function developmentApplicants()
    {
        return $this->hasMany(DevelopmentApplicant::class);
    }
}
