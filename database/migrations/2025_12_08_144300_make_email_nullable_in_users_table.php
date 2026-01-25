<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Make the email column nullable so users can register without an email address
        DB::statement("ALTER TABLE `users` MODIFY `email` VARCHAR(255) NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert email to NOT NULL. Note: this will fail if any NULL emails exist.
        DB::statement("ALTER TABLE `users` MODIFY `email` VARCHAR(255) NOT NULL");
    }
};
