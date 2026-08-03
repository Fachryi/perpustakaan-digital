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

    protected $fillable = ['kelas_id', 'file', 'pengarang', 'judul', 'sinopsis', 'jumlah', 'penerbit', 'tahun_terbit', 'jenis_id', 'view', 'status', 'file', 'foto', 'abstrak'];

    protected $casts = [
        'view' => 'integer',
    ];

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

    // public function toSearchableArray(): array
    // {
    //     return [
    //     'judul' => $this->judul,
    //     'sinopsis' => $this->sinopsis,
    //     'jenis_id' => $this->jenis_id,
    //     'kelas_id' => $this->kelas_id
    //     ];
    // }

    // public function shouldBeSearchable(): bool
    // {
    //     return $this->status === 'tersedia';
    // }
}
