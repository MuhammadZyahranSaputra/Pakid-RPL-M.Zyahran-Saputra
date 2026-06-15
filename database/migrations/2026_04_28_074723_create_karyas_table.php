<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. BANGUN TABEL KATEGORI DULU (Wajib di atas karena jadi referensi)
        Schema::create('kategoris', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kategori');
            $table->string('slug')->unique();
            $table->timestamps();
        });

        // 2. BARU BANGUN TABEL KARYA
        Schema::create('karyas', function (Blueprint $table) {
            $table->id();
            
            // Relasi ke tabel users dan kategoris
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('kategori_id')->constrained('kategoris')->onDelete('cascade');
            
            // Kolom data inovasi
            $table->string('judul_karya');
            $table->text('deskripsi_inovasi');
            $table->string('file_multimedia');
            $table->string('link_prototype')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        // Drop harus kebalikan dari urutan pembuatan (Karya dulu, baru Kategori)
        Schema::dropIfExists('karyas');
        Schema::dropIfExists('kategoris');
    }
};