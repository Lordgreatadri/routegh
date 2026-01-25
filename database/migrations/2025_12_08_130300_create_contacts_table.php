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
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('upload_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('contact_group_id')->nullable()->constrained('contact_groups')->onDelete('set null');
            $table->string('name')->nullable();
            $table->string('phone', 20);
            $table->json('meta')->nullable()->comment('Extra data for future expansion');
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('user_id');
            $table->index('upload_id');
            $table->index('contact_group_id');
            $table->index('phone');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};
