<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Komentar extends Model
{
    // Buka gembok
    protected $fillable = ['user_id', 'karya_id', 'isi_komentar'];

    // Komentar ini punya siapa?
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Komentar ini ada di karya mana?
    public function karya()
    {
        return $this->belongsTo(Karya::class);
    }
}