<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengaturan extends Model
{
    use HasFactory;

    // INI KODE TERAKHIR YANG LU BUTUHIN: Buka izin akses kolom
    protected $fillable = [
        'key',
        'value',
    ];
}
