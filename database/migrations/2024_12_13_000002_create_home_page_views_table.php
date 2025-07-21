<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('home_page_views', function (Blueprint $table) {
            $table->id();
            $table->string('view_id', 36)->unique()->index();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('user_ip', 45);
            $table->text('user_agent')->nullable();
            $table->enum('view_type', ['guest', 'registered', 'admin'])->default('guest');
            $table->timestamp('viewed_at');
            $table->json('metadata')->nullable(); // Para dados de analytics
            $table->timestamps();

            // Índices para analytics
            $table->index(['viewed_at']);
            $table->index(['user_id', 'viewed_at']);
            $table->index(['user_ip', 'viewed_at']);
            $table->index(['view_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('home_page_views');
    }
}; 