<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Relasi ke tabel Karya.
     */
    public function karyas()
    {
        return $this->hasMany(Karya::class);
    }

    /**
     * Logic otomatis saat user dihapus.
     */
    protected static function booted()
    {
        static::deleting(function ($user) {
            // Sapu bersih semua file multimedia milik user ini sebelum datanya dihapus
            foreach ($user->karyas as $karya) {
                if ($karya->file_multimedia && \Illuminate\Support\Facades\Storage::disk('public')->exists($karya->file_multimedia)) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($karya->file_multimedia);
                }
            }
        });
    }
}
