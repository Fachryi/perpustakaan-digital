<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileBuku extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function getFileUrlAttribute(): string
    {
        if (empty($this->file_name)) {
            return '#';
        }
        if (str_starts_with($this->file_name, 'http://') || str_starts_with($this->file_name, 'https://')) {
            return $this->file_name;
        }
        return \Illuminate\Support\Facades\Storage::url($this->file_name);
    }
}
