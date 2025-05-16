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
        Schema::create('invoice', function (Blueprint $table) {
            $table->id();
            $table->string('customer_name');
            $table->date('date');
            $table->string('no_invoice')->unique();
            $table->string('no_invoice_update')->unique()->nullable();
            $table->json('items')->nullable(); // Simpan list item sebagai JSON
            $table->bigInteger('down_payment');
            $table->string('status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice');
    }
};
