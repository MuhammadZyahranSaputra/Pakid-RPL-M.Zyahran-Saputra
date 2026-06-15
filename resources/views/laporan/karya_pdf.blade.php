<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Pameran PAKID</title>
    <style>
        body { font-family: sans-serif; color: #333; }
        .header { text-align: center; border-bottom: 2px solid #333; padding-bottom: 10px; margin-bottom: 20px; }
        .header h1 { margin: 0; font-size: 24px; color: #4338ca; }
        .header p { margin: 5px 0 0; font-size: 14px; color: #666; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; font-size: 12px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #f3f4f6; color: #111; text-transform: uppercase; }
        tr:nth-child(even) { background-color: #fafafa; }
        .rank { text-align: center; font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Laporan Rekapitulasi Pameran PAKID</h1>
        <p>Data Diekspor Pada: {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="30%">Judul Karya</th>
                <th width="20%">Kategori</th>
                <th width="25%">Pemilik Inovasi</th>
                <th width="20%">Perolehan Vote</th>
            </tr>
        </thead>
        <tbody>
            @forelse($karyas as $index => $karya)
                <tr>
                    <td class="rank">{{ $index + 1 }}</td>
                    <td><strong>{{ $karya->judul_karya }}</strong></td>
                    <td>{{ $karya->kategori->nama_kategori ?? '-' }}</td>
                    <td>{{ $karya->user->name ?? '-' }}</td>
                    <td class="rank">{{ $karya->votes_count }} Suka</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="rank">Belum ada data karya.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
