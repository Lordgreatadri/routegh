<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $tableNames = config('permission.table_names');

        // Add UUID to roles table
        Schema::table($tableNames['roles'], function (Blueprint $table) {
            $table->uuid('uuid')->nullable()->after('id');
            $table->index('uuid');
        });

        // Add UUID to permissions table
        Schema::table($tableNames['permissions'], function (Blueprint $table) {
            $table->uuid('uuid')->nullable()->after('id');
            $table->index('uuid');
        });

        // Generate UUIDs for existing records
        DB::table($tableNames['roles'])->get()->each(function ($role) use ($tableNames) {
            DB::table($tableNames['roles'])
                ->where('id', $role->id)
                ->update(['uuid' => Str::uuid()]);
        });

        DB::table($tableNames['permissions'])->get()->each(function ($permission) use ($tableNames) {
            DB::table($tableNames['permissions'])
                ->where('id', $permission->id)
                ->update(['uuid' => Str::uuid()]);
        });

        // Make UUID unique after population
        Schema::table($tableNames['roles'], function (Blueprint $table) {
            $table->uuid('uuid')->unique()->change();
        });

        Schema::table($tableNames['permissions'], function (Blueprint $table) {
            $table->uuid('uuid')->unique()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tableNames = config('permission.table_names');

        Schema::table($tableNames['roles'], function (Blueprint $table) {
            $table->dropColumn('uuid');
        });

        Schema::table($tableNames['permissions'], function (Blueprint $table) {
            $table->dropColumn('uuid');
        });
    }
};
