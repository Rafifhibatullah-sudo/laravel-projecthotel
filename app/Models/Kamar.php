<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kamar extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_kamar',
        'tipe_kamar',
        'harga',
        'jumlah_kamar',
        'deskripsi',
        'foto',
    ];

    // Relasi Many-to-Many ke Model Fasilitas
    public function fasilitas()
    {
        return $this->belongsToMany(Fasilitas::class, 'kamar_fasilitas');
    }
    public function reservasi()
    {
        return $this->hasMany(Reservasi::class, 'kamar_id');
    }
}
