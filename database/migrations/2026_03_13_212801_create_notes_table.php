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
        Schema::create('notes', function (Blueprint $table) {
            $table->id();
            // Cudzí kľúč na users.id (ak sa zmaže user, zmažú sa aj jeho notes)
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title', 128);
            $table->text('body')->nullable();
            $table->enum('status', ['draft', 'archived', 'published'])->default('draft');
            $table->boolean('is_pinned')->default(false);
            $table->timestamps();
            $table->softDeletes(); // Pridá stĺpec deleted_at pre logické mazanie

            // Indexy pre rýchlejšie vyhľadávanie
            $table->index('status');
            $table->index('is_pinned');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notes');
    }
};
