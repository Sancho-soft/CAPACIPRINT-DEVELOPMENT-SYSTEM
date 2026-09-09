<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('machines', function (Blueprint $table) {
            $table->date('next_maintenance_date')->nullable()->after('notes');
            $table->date('last_maintenance_date')->nullable()->after('notes');
        });
    }

    public function down(): void
    {
        Schema::table('machines', function (Blueprint $table) {
            $table->dropColumn(['next_maintenance_date', 'last_maintenance_date']);
        });
    }
};
