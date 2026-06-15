<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Karya extends Model
{
    protected $fillable = [
        'user_id',
        'kategori_id',
        'judul_karya',
        'deskripsi_inovasi',
        'file_multimedia',
        'link_prototype'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

    // --- TAMBAHAN BARU: Relasi ke tabel votes ---
    public function votes()
    {
        // Satu Karya bisa memiliki banyak Vote (One-to-Many)
        return $this->hasMany(Vote::class);
    }
    public function komentars()
    {
        // Satu Karya bisa punya banyak Komentar
        return $this->hasMany(Komentar::class);
    }
}