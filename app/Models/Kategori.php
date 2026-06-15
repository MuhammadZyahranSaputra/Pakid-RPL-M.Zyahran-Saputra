<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    // Mengizinkan mass assignment untuk field ini
    protected $fillable = ['nama_kategori', 'slug'];

    // Relasi: Satu Kategori bisa dimiliki oleh banyak Karya
    public function karyas()
    {
        return $this->hasMany(Karya::class);
    }
}