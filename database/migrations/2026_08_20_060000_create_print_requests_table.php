<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('print_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('service');
            $table->unsignedInteger('quantity');
            $table->string('size');
            $table->string('material');
            $table->string('finishing')->default('None');
            $table->date('deadline')->nullable();
            $table->string('preferred_branch')->nullable();
            $table->text('additional_instructions')->nullable();
            $table->string('design_file_path')->nullable();
            $table->string('design_file_name')->nullable();
            $table->string('design_file_size')->nullable();
            $table->string('collection_mode')->default('pickup'); // pickup | shipping
            $table->string('status')->default('submitted');
            // submitted | quotation | payment | branch_recommended | production | completed | ready_for_pickup | claimed
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('print_requests');
    }
};
