<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Invalidate any active sessions for super_admin users
        $superAdminUserIds = DB::table('users')
            ->where('role', 'super_admin')
            ->orWhere('email', 'superadmin@capaciprint.com')
            ->pluck('id')
            ->toArray();

        if (!empty($superAdminUserIds) && Schema::hasTable('sessions')) {
            DB::table('sessions')
                ->whereIn('user_id', $superAdminUserIds)
                ->delete();
        }

        // 2. Delete any super_admin user records
        DB::table('users')
            ->where('role', 'super_admin')
            ->orWhere('email', 'superadmin@capaciprint.com')
            ->delete();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Role was intentionally removed across system
    }
};
