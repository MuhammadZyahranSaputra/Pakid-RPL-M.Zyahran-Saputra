<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Karya;
use App\Models\Kategori;
use App\Models\Vote;
use App\Models\Komentar;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DummyDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Persiapan Kategori
        $kategoris = Kategori::all();
        if ($kategoris->isEmpty()) {
            $this->call(KategoriSeeder::class);
            $kategoris = Kategori::all();
        }

        // 2. Ambil atau Buat 10 Peserta
        $pesertas = [];
        for ($i = 1; $i <= 10; $i++) {
            $pesertas[] = User::firstOrCreate(
                ['email' => "peserta$i@pakid.com"],
                [
                    'name' => "Inovator Muda $i",
                    'password' => 'password',
                    'role' => 'peserta',
                ]
            );
        }

        // 3. Ambil atau Buat 20 Pengunjung
        $pengunjungs = [];
        for ($i = 1; $i <= 20; $i++) {
            $pengunjungs[] = User::firstOrCreate(
                ['email' => "pengunjung$i@pakid.com"],
                [
                    'name' => "Pengunjung $i",
                    'password' => 'password',
                    'role' => 'pengunjung',
                ]
            );
        }

        $dataKarya = [
            ['judul' => 'Smart Agri-Tech IoT', 'keyword' => 'agriculture'],
            ['judul' => 'Health-AI Diagnostic', 'keyword' => 'health'],
            ['judul' => 'LocalCraft Marketplace', 'keyword' => 'craft'],
            ['judul' => 'Smart City Monitoring', 'keyword' => 'city'],
            ['judul' => 'VR Campus Tour', 'keyword' => 'technology'],
            ['judul' => 'SecureVote Blockchain', 'keyword' => 'coding'],
            ['judul' => 'EduKids Gamification', 'keyword' => 'education'],
            ['judul' => 'Eco-Waste Sorter', 'keyword' => 'robot'],
            ['judul' => 'Fleet-Track Management', 'keyword' => 'logistics'],
            ['judul' => 'Cyber-Shield Dashboard', 'keyword' => 'cybersecurity'],
        ];

        if (!Storage::disk('public')->exists('multimedia_karya')) {
            Storage::disk('public')->makeDirectory('multimedia_karya');
        }

        $placeholders = [
            'https://images.unsplash.com/photo-1518770660439-4636190af475',
            'https://images.unsplash.com/photo-1485827404703-89b55fcc595e',
            'https://images.unsplash.com/photo-1526374965328-7f61d4dc18c5',
            'https://images.unsplash.com/photo-1550751827-4bd374c3f58b',
            'https://images.unsplash.com/photo-1519389950473-47ba0277781c',
            'https://images.unsplash.com/photo-1558494949-ef010cbdcc51',
            'https://images.unsplash.com/photo-1451187580459-43490279c0fa',
            'https://images.unsplash.com/photo-1531297484001-80022131f5a1',
            'https://images.unsplash.com/photo-1504384308090-c894fdcc538d',
            'https://images.unsplash.com/photo-1517694712202-14dd9538aa97'
        ];

        foreach ($dataKarya as $index => $item) {
            $filename = 'dummy_' . $index . '.jpg';
            $path = 'multimedia_karya/' . $filename;
            
            if (!Storage::disk('public')->exists($path)) {
                $imageUrl = $placeholders[$index] . "?auto=format&fit=crop&w=1200&q=80";
                try {
                    $response = Http::withoutVerifying()->timeout(30)->get($imageUrl);
                    if ($response->successful()) {
                        Storage::disk('public')->put($path, $response->body());
                    }
                } catch (\Exception $e) {
                    $this->command->warn("Fallback image used for index $index.");
                }
            }

            // Buat Karya
            $karya = Karya::updateOrCreate(
                ['judul_karya' => $item['judul']],
                [
                    'user_id' => $pesertas[$index]->id,
                    'kategori_id' => $kategoris->random()->id,
                    'deskripsi_inovasi' => "Ini adalah deskripsi untuk inovasi {$item['judul']}. Fokus pada solusi masa depan yang efisien dan berdampak positif bagi masyarakat luas.",
                    'file_multimedia' => $path,
                    'link_prototype' => 'https://figma.com',
                ]
            );

            // Bersihkan data lama
            $karya->votes()->delete();
            $karya->komentars()->delete();

            // Vote unik (maksimal 20 sesuai jumlah pengunjung)
            $voteCount = rand(5, 20);
            $voters = collect($pengunjungs)->shuffle()->take($voteCount);
            foreach ($voters as $voter) {
                Vote::create(['user_id' => $voter->id, 'karya_id' => $karya->id]);
            }

            // Komentar
            for ($c = 0; $c < rand(2, 5); $c++) {
                Komentar::create([
                    'user_id' => collect($pengunjungs)->random()->id,
                    'karya_id' => $karya->id,
                    'isi_komentar' => "Luar biasa inovasi {$item['judul']} ini! Sangat menginspirasi."
                ]);
            }
        }

        $this->command->info('Data Dummy (10 Karya) Berhasil Dimasukkan! 🔥');
    }
}
