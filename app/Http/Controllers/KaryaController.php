<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Karya;
use App\Models\Vote;
use Illuminate\Support\Facades\Storage;

class KaryaController extends Controller
{
    // Menampilkan form upload karya untuk peserta
    public function create()
    {
        // Ambil semua data kategori dari database
        $kategoris = Kategori::all();
        
        // Lempar data kategoris ke frontend Vue (nanti kita bikin file Vue-nya)
        return Inertia::render('Peserta/UploadKarya', [
            'kategoris' => $kategoris
        ]);
    }
    public function store(Request $request)
    {
        // 1. Validasi Input (Satpam Data)
        $validated = $request->validate([
            'judul_karya' => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategoris,id',
            'deskripsi_inovasi' => 'required|string',
            // max:102400 berarti maksimal 100MB (dalam Kilobyte)
            'file_multimedia' => 'required|file|mimes:jpg,jpeg,png,mp4,webm|max:102400', 
            'link_prototype' => 'nullable|url',
        ]);

        // 2. Logic Simpan File Fisik
        if ($request->hasFile('file_multimedia')) {
            // Simpan file ke folder storage/app/public/multimedia_karya
            $path = $request->file('file_multimedia')->store('multimedia_karya', 'public');
            // Ganti isi variabel dengan teks path-nya saja untuk disimpan ke database
            $validated['file_multimedia'] = $path;
        }

        // 3. Tambahkan ID Peserta yang sedang login
        $validated['user_id'] = auth()->id();

        // 4. Simpan semua data ke tabel 'karyas'
        Karya::create($validated);

        // 5. Kembalikan user ke panel galeri pribadi dengan pesan
        return redirect()->route('peserta.galeri')->with('message', 'Mantap! Karya berhasil diunggah.');
    }
    public function index()
    {
        // Ambil data karya beserta nama kategorinya, khusus untuk user yang login
        $karyas = \App\Models\Karya::with('kategori')
                    ->where('user_id', auth()->id())
                    ->get();

        return \Inertia\Inertia::render('Peserta/Galeri', [
            'karyas' => $karyas
        ]);
    }
    public function toggleVote(Request $request, $karya_id)
    {
        $user_id = auth()->id();
        
        // Cek dulu karya-nya ada nggak?
        $karya = \App\Models\Karya::findOrFail($karya_id);

        // Cari tahu apakah user ini udah pernah vote karya ini?
        $existingVote = \App\Models\Vote::where('user_id', $user_id)
                                        ->where('karya_id', $karya->id)
                                        ->first();

        if ($existingVote) {
            // Kalau udah ada, berarti dia mau batalin (Unvote)
            $existingVote->delete();
        } else {
            // Kalau belum, catat vote-nya!
            \App\Models\Vote::create([
                'user_id' => $user_id,
                'karya_id' => $karya->id,
            ]);
        }

        // Kembalikan ke halaman sebelumnya tanpa merusak posisi scroll
        return back();
    }

    public function leaderboard()
    {
        $topKaryas = Karya::with(['kategori', 'user'])
            ->withCount(['votes', 'komentars'])
            ->orderByDesc('votes_count')
            ->take(10)
            ->get();

        return Inertia::render('Leaderboard', [
            'topKaryas' => $topKaryas,
        ]);
    }

    public function show($id)
    {
        $userId = auth()->id();

        // Tarik 1 data karya spesifik, bawa semua relasinya termasuk data komentator
        $karya = \App\Models\Karya::with([
                'kategori', 
                'user', 
                'komentars.user' // Ajaib: Bawa relasi user dari dalam tabel komentar
            ])
            ->withCount('votes')
            ->with(['votes' => function ($query) use ($userId) {
                $query->where('user_id', $userId);
            }])
            ->findOrFail($id);

        return \Inertia\Inertia::render('Peserta/DetailKarya', [
            'karya' => $karya
        ]);
    }

    public function storeKomentar(Request $request, $karya_id)
    {
        // Pastikan karyanya ada
        \App\Models\Karya::findOrFail($karya_id);

        $request->validate([
            'isi_komentar' => 'required|string|max:1000',
        ]);

        \App\Models\Komentar::create([
            'user_id' => auth()->id(),
            'karya_id' => $karya_id,
            'isi_komentar' => $request->isi_komentar,
        ]);
        return back(); // Return back seamless ala Inertia
    }
    public function edit($id)
    {
        $karya = \App\Models\Karya::findOrFail($id);

        // Proteksi: Pastikan yang mau ngedit adalah pemilik aslinya!
        if ($karya->user_id !== auth()->id()) {
            abort(403, 'Lu nggak punya akses ngedit karya orang lain, Bro!');
        }

        return \Inertia\Inertia::render('Peserta/EditKarya', [
            'karya' => $karya,
            'kategoris' => \App\Models\Kategori::all() // Kirim ulang data kategori buat dropdown
        ]);
    }

    public function galeriPribadi()
    {
        $karyas = \App\Models\Karya::with(['kategori'])
                    ->withCount(['votes', 'komentars'])
                    ->where('user_id', auth()->id())
                    ->latest()
                    ->get();

        return \Inertia\Inertia::render('Peserta/GaleriPribadi', [
            'karyas' => $karyas
        ]);
    }

    public function update(Request $request, $id)
    {
        $karya = \App\Models\Karya::findOrFail($id);

        if ($karya->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'judul_karya' => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategoris,id',
            'deskripsi_inovasi' => 'required|string',
            // Gunakan mimes yang sama dengan store() agar konsisten
            'file_multimedia' => 'nullable|file|mimes:jpg,jpeg,png,mp4,webm|max:102400', 
            'link_prototype' => 'nullable|url',
        ]);

        $dataUpdate = [
            'judul_karya' => $request->judul_karya,
            'kategori_id' => $request->kategori_id,
            'deskripsi_inovasi' => $request->deskripsi_inovasi,
            'link_prototype' => $request->link_prototype,
        ];

        // Kalau peserta upload gambar baru, hapus gambar lama dari server biar storage nggak jebol!
        if ($request->hasFile('file_multimedia')) {
            if ($karya->file_multimedia && Storage::disk('public')->exists($karya->file_multimedia)) {
                Storage::disk('public')->delete($karya->file_multimedia);
            }
            $dataUpdate['file_multimedia'] = $request->file('file_multimedia')->store('multimedia_karya', 'public');
        }

        $karya->update($dataUpdate);

        return redirect()->route('peserta.galeri')->with('message', 'Mantap! Perubahan karya berhasil disimpan.');
    }
    public function destroy($id)
    {
        $karya = \App\Models\Karya::findOrFail($id);

        // KUNCI KEAMANAN
        if ($karya->user_id !== auth()->id()) {
            abort(403, 'Nggak punya akses buat ngehapus karya orang lain!');
        }

        // Sapu bersih file fisiknya dari storage
        if ($karya->file_multimedia && \Illuminate\Support\Facades\Storage::disk('public')->exists($karya->file_multimedia)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($karya->file_multimedia);
        }

        // Hapus datanya dari database
        $karya->delete();

        return back()->with('message', 'Karya berhasil dicabut dari pameran.');
    }
}