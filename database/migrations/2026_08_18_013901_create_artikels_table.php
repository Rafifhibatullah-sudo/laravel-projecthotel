<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('artikels', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('slug')->unique();
            $table->string('kategori'); // Misal: Promo, Event, Tips Liburan
            $table->text('isi');
            $table->string('gambar')->nullable();
            $table->enum('status', ['publish', 'draft'])->default('publish'); // Status tayang
            $table->unsignedBigInteger('views')->default(0); // Hitung berapa kali dibaca
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('artikels');
    }
};