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
        Schema::create('design_proofs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('print_request_id')->constrained('print_requests')->onDelete('cascade');
            $table->foreignId('designer_id')->nullable()->constrained('users')->nullOnDelete();
            $table->integer('version')->default(1);
            $table->string('proof_file_path');
            $table->string('proof_file_name');
            $table->string('proof_file_size')->nullable();
            $table->string('production_file_path')->nullable();
            $table->string('production_file_name')->nullable();
            $table->text('designer_notes')->nullable();
            $table->text('customer_feedback')->nullable();
            $table->string('status')->default('pending_review'); // pending_review, approved, revision_requested
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('design_proofs');
    }
};
