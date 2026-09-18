<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fasilitas extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_fasilitas',
        'deskripsi',
    ];

    // Relasi Many-to-Many ke Model Kamar
    public function kamars()
    {
        return $this->belongsToMany(Kamar::class, 'kamar_fasilitas', 'fasilitas_id', 'kamar_id');
    }
}
