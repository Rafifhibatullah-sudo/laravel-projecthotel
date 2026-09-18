<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Kamar extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_kamar',
        'slug',
        'tipe_kamar',
        'harga',
        'jumlah_kamar',
        'deskripsi',
        'foto',
    ];

    // Otomatis generate slug saat menyimpan jika belum diisi
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($kamar) {
            if (empty($kamar->slug)) {
                $kamar->slug = Str::slug($kamar->nama_kamar);
            }
        });
    }

    // UBAH BAGIAN INI: Parameter kedua diganti ke 'kamar_fasilitas'
    public function fasilitas()
    {
        return $this->belongsToMany(Fasilitas::class, 'kamar_fasilitas', 'kamar_id', 'fasilitas_id');
    }
    
    public function getRouteKeyName()
    {
        return 'slug';
    }
}