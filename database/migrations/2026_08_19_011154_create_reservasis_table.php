<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reservasis', function (Blueprint $table) {
            $table->id();
            $table->string('kode_booking')->unique();
            $table->foreignId('kamar_id')->constrained('kamars')->onDelete('cascade');
            $table->string('nama_pemesan');
            $table->string('email');
            $table->string('no_hp');
            $table->dateTime('check_in'); // Diubah ke dateTime agar menyimpan tanggal & jam
            $table->time('jam_check_in'); // Jam check-in murni dari form pemesan
            $table->dateTime('check_out'); // Diubah ke dateTime agar menyimpan tanggal & jam
            $table->time('jam_check_out'); // Jam check-out murni dari form pemesan
            $table->dateTime('checkout_real')->nullable(); // Jam checkout aktual
            $table->integer('jumlah_kamar')->default(1);
            $table->bigInteger('total_harga');
            $table->enum('status', ['pending', 'confirmed', 'in', 'out', 'cancelled'])->default('pending');
            $table->string('bukti_pembayaran')->nullable();
            $table->string('metode_pembayaran')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservasis');
    }
};