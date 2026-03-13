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
        Schema::create('categories', function (Blueprint $table) {
            $table->id(); // Vytvorí BIGINT UNSIGNED AUTO_INCREMENT PK
            $table->string('name', 64)->unique(); // Názov kategórie, max 64 znakov, unikátny
            $table->timestamps(); // Vytvorí created_at a updated_at
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
