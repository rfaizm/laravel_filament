<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            DB::statement('ALTER TABLE notifications ALTER COLUMN data TYPE jsonb USING (data::jsonb)');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            DB::statement('ALTER TABLE notifications ALTER COLUMN data TYPE text USING (data::text)');
        });
    }
};
