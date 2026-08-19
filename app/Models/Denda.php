<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Denda extends Model
{
    use HasFactory;

    protected $table = 'denda';

    protected $fillable = [
        'peminjaman_buku_id',
        'jumlah',
        'status',
        'tanggal_bayar',
        'keterangan',
        'jenis_denda',
        'hari_terlambat',
    ];

    protected $casts = [
        'jumlah' => 'decimal:2',
        'tanggal_bayar' => 'date',
    ];

    public function peminjaman(): BelongsTo
    {
        return $this->belongsTo(PeminjamanBuku::class, 'peminjaman_buku_id');
    }

    public function getFormattedAmountAttribute(): string
    {
        return 'Rp ' . number_format($this->jumlah, 0, ',', '.');
    }
}
