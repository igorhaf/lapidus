<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('home_contacts', function (Blueprint $table) {
            $table->id();
            $table->string('contact_id', 36)->unique()->index(); // Value Object ContactId
            $table->string('name', 100);
            $table->string('email', 255);
            $table->string('phone', 20)->nullable();
            $table->string('subject', 200);
            $table->text('message');
            $table->enum('preferred_contact', ['email', 'phone', 'whatsapp'])->nullable();
            $table->boolean('newsletter')->default(false);
            $table->enum('status', ['pending', 'read', 'responded', 'closed'])->default('pending');
            $table->string('user_ip', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Índices para performance
            $table->index(['status', 'created_at']);
            $table->index(['email']);
            $table->index(['created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('home_contacts');
    }
}; 