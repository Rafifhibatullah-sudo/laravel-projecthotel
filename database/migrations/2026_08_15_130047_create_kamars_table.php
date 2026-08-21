<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('kamars', function (Blueprint $table) {
        $table->id();                           // Primary Key auto-increment
        $table->string('nama_kamar');          // Kolom teks pendek (cth: Deluxe Room)
        $table->string('tipe_kamar');          // Kolom teks pendek (cth: King/Twin)
        $table->integer('harga');              // Kolom angka bulat untuk harga
        $table->integer('jumlah_kamar');       // Kolom angka bulat untuk stok kamar
        $table->text('deskripsi')->nullable(); // Kolom teks panjang, boleh kosong (nullable)
        $table->string('foto')->nullable();      // Simpan nama/path file gambar kamar
        $table->timestamps();                  // Otomatis buat kolom created_at & updated_at
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kamars');
    }
};
