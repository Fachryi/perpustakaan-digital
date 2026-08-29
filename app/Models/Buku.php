<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Buku extends Model
{
    use HasFactory;

    protected $table = 'buku';

    protected $fillable = ['kelas_id', 'file', 'pengarang', 'judul', 'sinopsis', 'jumlah', 'penerbit', 'tahun_terbit', 'jenis_id', 'kategori_id', 'view', 'status', 'foto', 'abstrak', 'kode_buku', 'user_id'];

    protected $casts = [
        'view' => 'integer',
    ];

    public function getFotoUrlAttribute(): string
    {
        if (empty($this->foto)) {
            return '/images/book-placeholder.png';
        }
        if (str_starts_with($this->foto, 'http://') || str_starts_with($this->foto, 'https://')) {
            return $this->foto;
        }
        return \Illuminate\Support\Facades\Storage::url($this->foto);
    }

    // Relationship dengan User (author)
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function fileBuku()
    {
        return $this->hasOne(FileBuku::class, 'buku_id');
    }

    public function file()
    {
        return $this->hasOne(FileBuku::class, 'buku_id');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relationship dengan Jenis
    public function jenis(): BelongsTo
    {
        return $this->belongsTo(Jenis::class);
    }

    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class);
    }

    // Relationship dengan Kategori
    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }

    // Relationship dengan PeminjamanBuku
    public function peminjaman(): HasMany
    {
        return $this->hasMany(PeminjamanBuku::class, 'buku_id');
    }

    // Get current active borrowing
    public function currentBorrowing()
    {
        return $this->peminjaman()->where('status', 'dipinjam')->where('approval', 'approved')->first();
    }

    // Check if book is available for borrowing
    public function isAvailable(): bool
    {
        return $this->status === 'tersedia' && $this->jumlah > 0;
    }

    // Scope untuk buku yang diterima
    public function scopeAvailable($query)
    {
        return $query->where('status', 'tersedia')->where('jumlah', '>', 0);
    }

    // Increment view count
    public function incrementView()
    {
        $this->increment('view');
    }

    /**
     * Generate kode_buku otomatis berdasarkan kategori (e.g. 001-01, 002-01, 003-01)
     */
    public static function generateKodeBuku($kategoriId)
    {
        $kategori = Kategori::find($kategoriId);
        $prefix = '001';
        if ($kategori) {
            if ($kategori->nama === 'Mapel') {
                $prefix = '001';
            } elseif ($kategori->nama === 'Cerita') {
                $prefix = '002';
            } elseif ($kategori->nama === 'Novel') {
                $prefix = '003';
            }
        }

        $existingCodes = self::where('kode_buku', 'LIKE', $prefix . '-%')->pluck('kode_buku');
        $maxNum = 0;
        foreach ($existingCodes as $code) {
            $parts = explode('-', $code);
            if (isset($parts[1]) && is_numeric($parts[1])) {
                $num = (int) $parts[1];
                if ($num > $maxNum) {
                    $maxNum = $num;
                }
            }
        }

        $nextNum = $maxNum + 1;
        $suffix = str_pad($nextNum, 2, '0', STR_PAD_LEFT);

        return $prefix . '-' . $suffix;
    }
}

