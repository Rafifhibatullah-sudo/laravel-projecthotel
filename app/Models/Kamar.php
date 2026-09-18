<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Kamar extends Model
{
    use HasFactory;

    protected $table = 'kamars'; // Menegaskan nama tabel di database

    protected $fillable = [
        'nama_kamar',
        'slug',
        'tipe_kamar',
        'harga',
        'jumlah_kamar',
        'deskripsi',
        'foto',
    ];

    /**
     * Boot function untuk generate slug otomatis
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($kamar) {
            if (empty($kamar->slug)) {
                $kamar->slug = Str::slug($kamar->nama_kamar);
            }
        });
    }

    /**
     * Relasi Many-to-Many ke Model Fasilitas melalui tabel pivot 'kamar_fasilitas'
     */
    public function fasilitas()
    {
        return $this->belongsToMany(Fasilitas::class, 'kamar_fasilitas', 'kamar_id', 'fasilitas_id');
    }

    /**
     * Menggunakan 'slug' sebagai pengganti ID pada Route Model Binding
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }
}