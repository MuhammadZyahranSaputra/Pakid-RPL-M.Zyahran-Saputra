<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class KategoriSeeder extends Seeder
{
    public function run(): void
    {
        $kategoris = [
            'Internet of Things (IoT)', 
            'Artificial Intelligence (AI)', 
            'Web Development', 
            'Teknologi Pendidikan'
        ];

        foreach ($kategoris as $kategori) {
            DB::table('kategoris')->updateOrInsert(
                ['slug' => Str::slug($kategori)],
                [
                    'nama_kategori' => $kategori,
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
        }
    }
}