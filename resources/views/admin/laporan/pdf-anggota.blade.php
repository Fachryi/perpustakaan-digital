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
        .badge-danger  { background-color: #e74c3c; color: white; }
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
                <th>NIS/NIP</th>
                <th>Nama Anggota</th>
                <th>Kelas</th>
                <th>Jml Pinjam</th>
                <th>Total Denda</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $index => $siswa)
            @php
                $totalDenda   = $siswa->peminjamanBuku->flatMap->denda->sum('jumlah');
                $masihPinjam  = $siswa->peminjamanBuku->where('status','dipinjam')->count() > 0;
            @endphp
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $siswa->nim_nip }}</td>
                <td>{{ $siswa->nama }}</td>
                <td>{{ $siswa->kelas->nama ?? '-' }}</td>
                <td>{{ $siswa->peminjamanBuku->count() }}x</td>
                <td>Rp {{ number_format($totalDenda, 0, ',', '.') }}</td>
                <td>
                    @if($masihPinjam)
                        <span class="badge badge-danger">Meminjam</span>
                    @else
                        <span class="badge badge-success">Bebas</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Total Anggota: {{ $data->count() }}</p>
    </div>

    <div class="signature-box">
        <div class="line"></div>
        <div class="name">Staff Perpustakaan</div>
        <div style="font-size:10px; color:#555;">SMP Negeri 1 Parangloe</div>
    </div>
    <div style="clear:both;"></div>
</body>
</html>
