<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('work_orders', function (Blueprint $table) {
            // Kita tambahkan kolom ttd_admin (nullable agar tidak error jika kosong)
            $table->string('ttd_admin')->nullable()->after('admin_notes');
        });
    }

    public function down(): void
    {
        Schema::table('work_orders', function (Blueprint $table) {
            $table->dropColumn('ttd_admin');
        });
    }
};