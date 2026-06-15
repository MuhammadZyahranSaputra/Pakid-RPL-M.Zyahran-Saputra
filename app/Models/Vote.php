<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    // 1. Buka Gembok Mass Assignment
    protected $fillable = [
        'user_id',
        'karya_id',
    ];

    // 2. Relasi Balik ke User (Siapa yang nge-vote?)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 3. Relasi Balik ke Karya (Karya apa yang di-vote?)
    public function karya()
    {
        return $this->belongsTo(Karya::class);
    }
}