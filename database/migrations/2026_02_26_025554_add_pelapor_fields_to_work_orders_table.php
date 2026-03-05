<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('work_orders', function (Blueprint $table) {
            $table->string('nama_pelapor')->nullable()->after('email');
            $table->string('tanda_tangan')->nullable()->after('nama_pelapor');
        });
    }

    public function down(): void
    {
        Schema::table('work_orders', function (Blueprint $table) {
            $table->dropColumn(['nama_pelapor', 'tanda_tangan']);
        });
    }
};