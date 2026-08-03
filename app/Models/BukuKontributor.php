<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BukuKontributor extends Model
{
    use HasFactory;
    public $table = 'buku_kontributor';
    protected $guarded = ['id'];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
