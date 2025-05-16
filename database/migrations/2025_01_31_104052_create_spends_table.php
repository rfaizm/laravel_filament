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
        Schema::create('spendings', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('image')->nullable(); // Dokumen (opsional
            $table->text('description')->nullable(); // Deskripsi
            $table->string('source_of_spending'); // Sumber pengeluaran
            $table->bigInteger('total'); // Jumlah pengeluaran
            $table->foreignId('categories_id')->constrained('categories')->onDelete('cascade'); // Kategori pengeluaran
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spends');
    }
};
