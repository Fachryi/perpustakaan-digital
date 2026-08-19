<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header img {
            width: 80px;
            height: 80px;
            object-fit: contain;
        }
        .header h1 {
            margin: 5px 0;
            font-size: 18px;
        }
        .header p {
            margin: 2px 0 0;
            font-size: 11px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #333;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .footer {
            margin-top: 20px;
            text-align: right;
            font-size: 10px;
        }
        .badge {
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 10px;
        }
        .badge-success {
            background-color: #28a745;
            color: white;
        }
        .badge-warning {
            background-color: #ffc107;
            color: black;
        }
        .badge-danger {
            background-color: #dc3545;
            color: white;
        }
        .badge-primary {
            background-color: #007bff;
            color: white;
        }
        .signature {
            margin-top: 50px;
            page-break-inside: avoid;
        }
        .signature-box {
            width: 250px;
            text-align: center;
            float: right;
        }
        .signature-box .line {
            border-bottom: 1px solid #333;
            margin-top: 60px;
            margin-bottom: 5px;
        }
        .signature-box .name {
            font-weight: bold;
            font-size: 12px;
        }
        .signature-box .role {
            font-size: 11px;
            color: #555;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ public_path('assets/smp.png') }}">
        <h1>{{ $title }}</h1>
        <p>Periode: {{ \Carbon\Carbon::parse($tanggalAwal)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($tanggalAkhir)->format('d/m/Y') }}</p>
        <p>SMP Negeri 1 Parangloe, Kabupaten Gowa</p>
    </div>

    @if($jenisLaporan === 'denda')
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Siswa</th>
                <th>NIS</th>
                <th>Judul Buku</th>
                <th>Jenis Denda</th>
                <th>Hari Terlambat</th>
                <th>Jumlah Denda</th>
                <th>Status</th>
                <th>Tanggal Bayar</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $index => $row)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $row->peminjaman->user->nama ?? '-' }}</td>
                <td>{{ $row->peminjaman->user->nim_nip ?? '-' }}</td>
                <td>{{ $row->peminjaman->buku->judul ?? '-' }}</td>
                <td>
                    @if(($row->jenis_denda ?? '') === 'kehilangan')
                        <span class="badge badge-danger">Kehilangan</span>
                    @else
                        <span class="badge badge-warning">Keterlambatan</span>
                    @endif
                </td>
                <td>{{ ($row->hari_terlambat ?? 0) > 0 ? $row->hari_terlambat.' hari' : '-' }}</td>
                <td>Rp {{ number_format($row->jumlah, 0, ',', '.') }}</td>
                <td>
                    @if($row->status === 'paid')
                        <span class="badge badge-success">Lunas</span>
                    @else
                        <span class="badge badge-warning">Belum Bayar</span>
                    @endif
                </td>
                <td>{{ $row->tanggal_bayar ? \Carbon\Carbon::parse($row->tanggal_bayar)->format('d/m/Y') : '-' }}</td>
                <td>{{ $row->keterangan ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    @elseif($jenisLaporan === 'terlambat')
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Siswa</th>
                <th>NIS</th>
                <th>Judul Buku</th>
                <th>Tanggal Pinjam</th>
                <th>Tanggal Kembali</th>
                <th>Hari Terlambat</th>
                <th>Status Denda</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $index => $row)
            @php
                $hariTerlambat = \Carbon\Carbon::parse($row->tanggal_kembali)->diffInDays(now());
            @endphp
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $row->user->nama ?? '-' }}</td>
                <td>{{ $row->user->nim_nip ?? '-' }}</td>
                <td>{{ $row->buku->judul ?? '-' }}</td>
                <td>{{ $row->tanggal_pinjam ? \Carbon\Carbon::parse($row->tanggal_pinjam)->format('d/m/Y') : '-' }}</td>
                <td>{{ $row->tanggal_kembali ? \Carbon\Carbon::parse($row->tanggal_kembali)->format('d/m/Y') : '-' }}</td>
                <td>{{ $hariTerlambat }} hari</td>
                <td>
                    @if($row->denda && $row->denda->count() > 0)
                        @if($row->denda->first()->status === 'paid')
                            <span class="badge badge-success">Lunas</span>
                        @else
                            <span class="badge badge-warning">Belum Bayar</span>
                        @endif
                    @else
                        <span class="badge badge-primary">Belum Dikenakan</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    @elseif($jenisLaporan === 'pengembalian')
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Siswa</th>
                <th>NIS</th>
                <th>Judul Buku</th>
                <th>Pengarang</th>
                <th>Tanggal Pinjam</th>
                <th>Tanggal Kembali</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $index => $row)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $row->user->nama ?? '-' }}</td>
                <td>{{ $row->user->nim_nip ?? '-' }}</td>
                <td>{{ $row->buku->judul ?? '-' }}</td>
                <td>{{ $row->buku->pengarang ?? '-' }}</td>
                <td>{{ $row->tanggal_pinjam ? \Carbon\Carbon::parse($row->tanggal_pinjam)->format('d/m/Y') : '-' }}</td>
                <td>{{ $row->tanggal_kembali ? \Carbon\Carbon::parse($row->tanggal_kembali)->format('d/m/Y') : '-' }}</td>
                <td>
                    <span class="badge badge-success">Dikembalikan</span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    @else
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Siswa</th>
                <th>NIS</th>
                <th>Judul Buku</th>
                <th>Pengarang</th>
                <th>Tanggal Pinjam</th>
                <th>Tanggal Kembali</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $index => $row)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $row->user->nama ?? '-' }}</td>
                <td>{{ $row->user->nim_nip ?? '-' }}</td>
                <td>{{ $row->buku->judul ?? '-' }}</td>
                <td>{{ $row->buku->pengarang ?? '-' }}</td>
                <td>{{ $row->tanggal_pinjam ? \Carbon\Carbon::parse($row->tanggal_pinjam)->format('d/m/Y') : '-' }}</td>
                <td>{{ $row->tanggal_kembali ? \Carbon\Carbon::parse($row->tanggal_kembali)->format('d/m/Y') : '-' }}</td>
                <td>
                    @if($row->status === 'returned')
                        <span class="badge badge-success">Dikembalikan</span>
                    @elseif($row->status === 'active')
                        <span class="badge badge-primary">Aktif</span>
                    @elseif($row->status === 'overdue')
                        <span class="badge badge-danger">Terlambat</span>
                    @else
                        <span class="badge badge-warning">{{ ucfirst($row->status) }}</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <div class="footer">
        <p>Dicetak pada: {{ now()->format('d/m/Y H:i:s') }}</p>
        <p>Total Data: {{ $data->count() }}</p>
    </div>

    <div class="signature">
        <div class="signature-box">
            <div class="line"></div>
            <div class="name">Staff Perpustakaan</div>
            <div class="role">SMP Negeri 1 Parangloe</div>
        </div>
        <div style="clear: both;"></div>
    </div>
</body>
</html>
