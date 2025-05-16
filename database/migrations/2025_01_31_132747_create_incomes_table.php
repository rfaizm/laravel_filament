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
        Schema::create('incomes', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('image')->nullable(); // Alias Dokumen
            $table->string('no_invoice')->nullable(); // Alias Dokumen
            $table->text('description')->nullable(); // Deskripsi
            $table->string('source_of_income'); // Sumber pengeluaran
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
        Schema::dropIfExists('incomes');
    }
};
