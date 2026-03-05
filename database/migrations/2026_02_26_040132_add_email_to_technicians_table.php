<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('technicians', function (Blueprint $table) {
            // Menambahkan kolom email setelah kolom nama (opsional)
            // Kita gunakan unique() agar tidak ada email ganda
            $table->string('email')->unique()->nullable()->after('name');
        });
    }

    public function down(): void
    {
        Schema::table('technicians', function (Blueprint $table) {
            $table->dropColumn('email');
        });
    }
};