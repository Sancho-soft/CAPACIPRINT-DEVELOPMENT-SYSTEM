<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('print_requests', function (Blueprint $table) {
            if (!Schema::hasColumn('print_requests', 'proof_file_path')) {
                $table->string('proof_file_path')->nullable()->after('design_file_path');
                $table->enum('proof_status', ['pending_proof', 'proof_uploaded', 'proof_approved', 'revision_requested'])->default('pending_proof')->after('proof_file_path');
                $table->text('proof_notes')->nullable()->after('proof_status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('print_requests', function (Blueprint $table) {
            $table->dropColumn(['proof_file_path', 'proof_status', 'proof_notes']);
        });
    }
};
