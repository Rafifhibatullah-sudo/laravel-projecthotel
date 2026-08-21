<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservasi extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_booking',
        'kamar_id',
        'nama_pemesan',
        'email',
        'no_hp',
        'check_in',
        'check_out',
        'jumlah_kamar',
        'total_harga',
        'status',
        'catatan',
    ];

    // Relasi ke Model Kamar
    public function kamar()
    {
        return $table = $this->belongsTo(Kamar::class, 'kamar_id');
    }
}