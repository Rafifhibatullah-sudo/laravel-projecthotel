<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kamars', function (Blueprint $table) {
            $table->string('slug')->nullable()->unique()->after('nama_kamar');
        });
    }

    public function down(): void
    {
        Schema::table('kamars', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};