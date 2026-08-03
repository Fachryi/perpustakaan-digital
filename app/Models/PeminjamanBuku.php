<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class PeminjamanBuku extends Model
{
    use HasFactory;

    protected $table = 'peminjaman_buku';

    protected $fillable = [
        'user_id',
        'buku_id',
        'tanggal_pinjam',
        'tanggal_kembali',
        'status',
        'approval',
        'approval_by'
    ];

    protected $casts = [
        'tanggal_pinjam' => 'date',
        'tanggal_kembali' => 'date',
    ];

    // Relationship dengan User (peminjam)
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Relationship dengan Buku
    public function buku(): BelongsTo
    {
        return $this->belongsTo(Buku::class);
    }

    // Relationship dengan User (yang approve)
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approval_by');
    }

    // Relationship dengan denda terkait
    public function denda()
    {
        return $this->hasMany(Denda::class, 'peminjaman_buku_id');
    }

    // Check if borrowing is overdue
    public function isOverdue(): bool
    {
        if ($this->status !== 'dipinjam' || !$this->tanggal_kembali) {
            return false;
        }

        return Carbon::now()->isAfter($this->tanggal_kembali);
    }

    // Get days remaining or overdue
    public function getDaysRemaining(): int
    {
        if (!$this->tanggal_kembali) {
            return 0;
        }

        return Carbon::now()->diffInDays($this->tanggal_kembali, false);
    }

    // Scope untuk peminjaman aktif
    public function scopeActive($query)
    {
        return $query->where('status', 'dipinjam')
                    ->where('approval', 'approved');
    }

    // Scope untuk peminjaman yang perlu approval (not needed anymore, but keeping for compatibility)
    public function scopePending($query)
    {
        return $query->where('approval', 'pending');
    }
}
