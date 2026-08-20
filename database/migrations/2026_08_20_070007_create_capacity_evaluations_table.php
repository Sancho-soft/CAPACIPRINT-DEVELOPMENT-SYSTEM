<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('capacity_evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('print_request_id')->constrained()->onDelete('cascade');
            $table->foreignId('branch_id')->constrained()->onDelete('cascade');
            $table->foreignId('evaluated_by')->nullable()->constrained('users')->onDelete('set null');
            // Scores (0-100)
            $table->integer('machine_score')->default(0);
            $table->integer('material_score')->default(0);
            $table->integer('employee_score')->default(0);
            $table->integer('workload_score')->default(0);
            $table->integer('deadline_score')->default(0);
            $table->integer('total_score')->default(0);
            // Results
            $table->string('capacity_status')->default('not_qualified');
            // qualified | near_capacity | not_qualified | over_capacity | unavailable
            $table->integer('available_machines')->default(0);
            $table->decimal('current_workload_pct', 5, 2)->default(0);
            $table->date('estimated_completion')->nullable();
            $table->boolean('deadline_feasible')->default(false);
            $table->text('evaluation_notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('capacity_evaluations');
    }
};
