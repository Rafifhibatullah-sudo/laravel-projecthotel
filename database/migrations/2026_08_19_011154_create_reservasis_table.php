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
            $table->date('check_in');
            $table->date('check_out');
            $table->integer('jumlah_kamar')->default(1);
            $table->bigInteger('total_harga');
            // Menambahkan status 'in' dan 'out'
            $table->enum('status', ['pending', 'confirmed', 'in', 'out', 'cancelled'])->default('pending');
            $table->string('bukti_pembayaran')->nullable(); // Menampung file bukti transfer/bayar
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
