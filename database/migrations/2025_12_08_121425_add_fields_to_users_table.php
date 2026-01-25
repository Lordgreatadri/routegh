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
        Schema::table('users', function (Blueprint $table) {
            $table->uuid('uuid')->unique()->after('id');
            $table->string('phone_number', 20)->nullable()->after('email');
            $table->string('company_name')->nullable()->after('phone_number');
            $table->boolean('is_active')->default(true)->after('company_name');
            $table->timestamp('last_login_at')->nullable()->after('is_active');
            $table->string('last_login_ip', 45)->nullable()->after('last_login_at');
            
            // Add indexes
            $table->index('uuid');
            $table->index('email');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'uuid',
                'phone_number',
                'company_name',
                'is_active',
                'last_login_at',
                'last_login_ip'
            ]);
            
            // Drop indexes
            $table->dropIndex(['uuid']);
            $table->dropIndex(['email']);
            $table->dropIndex(['is_active']);
        });
    }
};
