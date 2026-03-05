<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('work_orders', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // WO-20240101-0001
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('item_name');   // nama barang/alat
            $table->string('location');    // lokasi/unit
            $table->text('description');   // deskripsi kerusakan
            $table->enum('priority', ['high', 'medium', 'low'])->default('medium');
            $table->enum('status', ['submitted', 'in_progress', 'completed', 'broken_total'])
                  ->default('submitted');
            $table->foreignId('technician_id')->nullable()->constrained('technicians')->nullOnDelete();
            $table->unsignedTinyInteger('estimated_days')->nullable(); // estimasi hari pengerjaan
            $table->text('admin_notes')->nullable(); // catatan admin
            $table->timestamps();

            // Index untuk sorting & filtering
            $table->index(['priority', 'status', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('work_orders');
    }
};