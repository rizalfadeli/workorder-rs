<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::table('work_orders', function (Blueprint $table) {

            $table->string('email')->after('description');

            // jika berita acara berupa file PDF (rekomendasi)
            $table->string('berita_acara')->nullable()->after('email');

            // kalau mau simpan sebagai text, pakai ini:
            // $table->longText('berita_acara')->nullable()->after('email');

        });
    }

    public function down(): void
    {
        Schema::table('work_orders', function (Blueprint $table) {
            $table->dropColumn(['email', 'berita_acara']);
        });
    }
};