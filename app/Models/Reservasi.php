<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservasi extends Model
{
    use HasFactory;

    protected $table = 'reservasis';

    protected $fillable = [
        'kode_booking',
        'kamar_id',
        'nama_pemesan',
        'email',
        'no_hp',
        'check_in',
        'jam_check_in',
        'check_out',
        'jam_check_out',
        'checkout_real',
        'jumlah_kamar',
        'total_harga',
        'status',
        'catatan',
        'bukti_pembayaran',
        'metode_pembayaran', 
    ];

    protected $casts = [
        'check_in'      => 'datetime',
        'check_out'     => 'datetime',
        'checkout_real' => 'datetime',
    ];

    /**
     * Relasi ke Model Kamar
     */
    public function kamar()
    {
        return $this->belongsTo(Kamar::class, 'kamar_id');
    }
}