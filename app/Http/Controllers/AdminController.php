<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Karya;
use App\Models\User;
use App\Models\Komentar;
use App\Models\Pengaturan;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class AdminController extends Controller
{
    public function index()
    {
        // Tarik semua karya dari semua peserta, gunakan pagination biar nggak berat
        $karyas = Karya::with(['kategori', 'user'])
                    ->withCount('votes', 'komentars')
                    ->latest()
                    ->paginate(10); 
        
        // Tarik rekap user untuk statistik
        $totalUsers = User::where('role', 'pengunjung')->count();
        $totalPeserta = User::where('role', 'peserta')->count();
        $pengaturan = Pengaturan::where('key', 'token_peserta')->first();
        $currentToken = $pengaturan ? $pengaturan->value : 'BELUM-DISET';

        return Inertia::render('Admin/Dashboard', [
            'karyas' => $karyas,
            'total_users' => $totalUsers,
            'total_peserta' => $totalPeserta,
            'current_token' => $currentToken,
        ]);
    }

    public function updateToken(Request $request)
    {
        $request->validate([
            'token_baru' => 'required|string|min:4',
        ]);

        $pengaturan = Pengaturan::firstOrCreate(['key' => 'token_peserta']);
        $pengaturan->update(['value' => $request->token_baru]);

        return back()->with('message', 'Token rahasia pendaftaran berhasil diperbarui!');
    }

    public function exportLaporanPdf()
    {
        $karyas = Karya::with(['kategori', 'user'])
            ->withCount('votes')
            ->orderBy('votes_count', 'desc')
            ->get();

        $pdf = Pdf::loadView('laporan.karya_pdf', ['karyas' => $karyas]);

        return $pdf->download('Laporan_Pameran_PAKID.pdf');
    }

    public function destroyKarya($id)
    {
        $karya = Karya::findOrFail($id);

        // 1. Cek dan hapus file fisik di folder storage dulu!
        if ($karya->file_multimedia && Storage::disk('public')->exists($karya->file_multimedia)) {
            Storage::disk('public')->delete($karya->file_multimedia);
        }

        // 2. Baru hapus baris datanya di MySQL (Vote dan Komentar akan otomatis ikut terhapus berkat cascade)
        $karya->delete();

        return back();
    }

    public function destroyKomentar($id)
    {
        // Kalau komentar, kita nggak perlu ngurus file fisik, cukup hapus datanya aja
        $komentar = Komentar::findOrFail($id);
        $komentar->delete();

        return back();
    }
}
