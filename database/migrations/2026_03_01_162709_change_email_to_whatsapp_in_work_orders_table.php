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
        Schema::table('work_orders', function (Blueprint $table) {
            // Menghapus kolom email jika ada
            if (Schema::hasColumn('work_orders', 'email')) {
                $table->dropColumn('email');
            }

            // Menambahkan kolom whatsapp
            // Gunakan string karena nomor telepon bisa diawali angka 0 atau +
            $table->string('whatsapp', 20)->after('nama_pelapor')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('work_orders', function (Blueprint $table) {
            // Mengembalikan kolom email jika rollback
            $table->string('email')->after('nama_pelapor')->nullable();
            
            // Menghapus kolom whatsapp
            $table->dropColumn('whatsapp');
        });
    }
};