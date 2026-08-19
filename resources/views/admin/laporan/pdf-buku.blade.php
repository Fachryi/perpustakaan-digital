<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $title }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 11px; }
        .header { text-align: center; margin-bottom: 18px; border-bottom: 2px solid #333; padding-bottom: 10px; }
        .header img { width: 70px; height: 70px; object-fit: contain; }
        .header h1 { margin: 4px 0; font-size: 16px; }
        .header p  { margin: 2px 0; font-size: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ccc; padding: 6px 8px; text-align: left; vertical-align: top; }
        th { background-color: #2c3e50; color: white; font-size: 10px; }
        tr:nth-child(even) { background-color: #f7f7f7; }
        .badge { padding: 2px 6px; border-radius: 3px; font-size: 9px; font-weight: bold; }
        .badge-success { background-color: #27ae60; color: white; }
        .badge-warning { background-color: #f39c12; color: black; }
        .footer { margin-top: 20px; text-align: right; font-size: 9px; color: #777; }
        .signature-box { width: 220px; text-align: center; float: right; margin-top: 40px; }
        .signature-box .line { border-bottom: 1px solid #333; margin-top: 55px; margin-bottom: 4px; }
        .signature-box .name { font-weight: bold; font-size: 11px; }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ public_path('assets/smp.png') }}">
        <h1>{{ $title }}</h1>
        <p>SMP Negeri 1 Parangloe, Kabupaten Gowa</p>
        <p>Dicetak: {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Buku</th>
                <th>Judul</th>
                <th>Pengarang</th>
                <th>Penerbit</th>
                <th>Tahun</th>
                <th>Jenis</th>
                <th>Kategori</th>
                <th>Kelas</th>
                <th>Stok</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $index => $buku)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $buku->kode_buku ?? '-' }}</td>
                <td>{{ $buku->judul }}</td>
                <td>{{ $buku->pengarang ?? '-' }}</td>
                <td>{{ $buku->penerbit ?? '-' }}</td>
                <td>{{ $buku->tahun_terbit ?? '-' }}</td>
                <td>{{ $buku->jenis->nama ?? '-' }}</td>
                <td>{{ $buku->kategori->nama ?? '-' }}</td>
                <td>{{ $buku->kelas->nama ?? 'Semua' }}</td>
                <td>{{ $buku->jumlah }}</td>
                <td>
                    @if($buku->status === 'tersedia')
                        <span class="badge badge-success">Tersedia</span>
                    @else
                        <span class="badge badge-warning">{{ ucfirst($buku->status) }}</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Total Buku: {{ $data->count() }}</p>
    </div>

    <div class="signature-box">
        <div class="line"></div>
        <div class="name">Staff Perpustakaan</div>
        <div style="font-size:10px; color:#555;">SMP Negeri 1 Parangloe</div>
    </div>
    <div style="clear:both;"></div>
</body>
</html>
